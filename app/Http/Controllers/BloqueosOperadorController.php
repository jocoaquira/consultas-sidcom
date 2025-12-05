<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BloqueosOperadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Usar DB::table() con join
        $query = DB::table('bloqueo_operadors as b')
            ->leftJoin('operador_minero as o', 'b.operador_minero_id', '=', 'o.id_operador_minero')
            ->select(
                'b.id',
                'b.operador_minero_id',
                'b.estado',
                'b.motivo',
                'b.fecha',
                'b.created_at',
                'b.updated_at',
                'o.razon_social',

            )
            ->orderBy('b.fecha', 'desc');

        // Aplicar filtros
        if ($request->filled('estado_filtro')) {
            $query->where('b.estado', $request->estado_filtro);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('b.fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('b.fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('operador_minero_id')) {
            $query->where('b.operador_minero_id', $request->operador_minero_id);
        }

        if ($request->filled('motivo_filtro')) {
            $query->where('b.motivo', 'LIKE', "%{$request->motivo_filtro}%");
        }

        $bloqueos = $query->paginate(20);

        // Para el dropdown de filtros
        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        return view('bloqueo-operadors.index', compact('bloqueos', 'operadores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        return view('bloqueo-operadors.create', compact('operadores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'operador_minero_id' => 'required|exists:operador_minero,id_operador_minero',
            'estado' => 'required|in:activo,bloqueado',
            'motivo' => 'required|string|min:10|max:500',
            'fecha' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Insertar bloqueo
            DB::table('bloqueo_operadors')->insert([
                'operador_minero_id' => $request->operador_minero_id,
                'estado' => $request->estado,
                'motivo' => $request->motivo,
                'fecha' => $request->fecha,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('bloqueo-operadors.index')
                ->with('success', 'Registro de ' . ($request->estado === 'bloqueado' ? 'bloqueo' : 'desbloqueo') . ' creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bloqueo = DB::table('bloqueo_operadors as b')
            ->leftJoin('operador_minero as o', 'b.operador_minero_id', '=', 'o.id_operador_minero')
            ->select('b.*', 'o.razon_social')
            ->where('b.id', $id)
            ->first();

        if (!$bloqueo) {
            return redirect()->route('bloqueo-operadors.index')
                ->with('error', 'Registro no encontrado.');
        }

        return view('bloqueo-operadors.show', compact('bloqueo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bloqueo = DB::table('bloqueo_operadors')
            ->where('id', $id)
            ->first();

        if (!$bloqueo) {
            return redirect()->route('bloqueo-operadors.index')
                ->with('error', 'Registro no encontrado.');
        }

        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        return view('bloqueo-operadors.edit', compact('bloqueo', 'operadores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:activo,bloqueado',
            'motivo' => 'required|string|min:10|max:500',
            'fecha' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar bloqueo
            DB::table('bloqueo_operadors')
                ->where('id', $id)
                ->update([
                    'estado' => $request->estado,
                    'motivo' => $request->motivo,
                    'fecha' => $request->fecha,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return redirect()->route('bloqueo-operadors.index')
                ->with('success', 'Registro actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::table('bloqueo_operadors')->where('id', $id)->delete();

            return redirect()->route('bloqueo-operadors.index')
                ->with('success', 'Registro eliminado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Historial de un operador específico
     */
    public function historial($operadorId)
    {
        // Verificar que el operador existe
        $operador = DB::table('operador_minero')
            ->where('id_operador_minero', $operadorId)
            ->first();

        if (!$operador) {
            return redirect()->route('bloqueo-operadors.index')
                ->with('error', 'Operador no encontrado.');
        }

        // Obtener historial
        $bloqueos = DB::table('bloqueo_operadors as b')
            ->select('b.*')
            ->where('b.operador_minero_id', $operadorId)
            ->orderBy('b.fecha', 'desc')
            ->paginate(15);

        return view('bloqueo-operadors.historial', compact('operador', 'bloqueos'));
    }

    /**
     * Bloquear operador rápido (modal)
     */
    public function bloquearRapido(Request $request)
    {
        $request->validate([
            'operador_minero_id' => 'required|exists:operador_minero,id_operador_minero',
            'motivo' => 'required|string|min:10',
        ]);

        try {
            DB::beginTransaction();

            // Insertar bloqueo
            $bloqueoId = DB::table('bloqueo_operadors')->insertGetId([
                'operador_minero_id' => $request->operador_minero_id,
                'estado' => 'bloqueado',
                'motivo' => $request->motivo,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener datos del bloqueo creado
            $bloqueo = DB::table('bloqueo_operadors as b')
                ->leftJoin('operador_minero as o', 'b.operador_minero_id', '=', 'o.id_operador_minero')
                ->select('b.*', 'o.razon_social')
                ->where('b.id', $bloqueoId)
                ->first();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Operador bloqueado exitosamente.',
                'bloqueo' => $bloqueo
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desbloquear operador rápido (modal)
     */
    public function desbloquearRapido(Request $request)
    {
        $request->validate([
            'operador_minero_id' => 'required|exists:operador_minero,id_operador_minero',
            'motivo' => 'required|string|min:10',
        ]);

        try {
            DB::beginTransaction();

            // Insertar desbloqueo
            $bloqueoId = DB::table('bloqueo_operadors')->insertGetId([
                'operador_minero_id' => $request->operador_minero_id,
                'estado' => 'activo',
                'motivo' => $request->motivo,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener datos del bloqueo creado
            $bloqueo = DB::table('bloqueo_operadors as b')
                ->leftJoin('operador_minero as o', 'b.operador_minero_id', '=', 'o.id_operador_minero')
                ->select('b.*', 'o.razon_social')
                ->where('b.id', $bloqueoId)
                ->first();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Operador desbloqueado exitosamente.',
                'bloqueo' => $bloqueo
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
