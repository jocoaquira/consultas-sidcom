<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActualizacionOperadorController extends Controller
{
    public function index(Request $request)
    {
        // USAR DB::table() en lugar de Eloquent con relaciones
        $query = DB::table('actualizacion_operadors as a')
            ->leftJoin('operador_minero as o', 'a.operador_minero_id', '=', 'o.id_operador_minero')
            ->select(
                'a.id',
                'a.operador_minero_id',
                'a.tipo_actualizacion',
                'a.fecha',
                'a.observaciones',
                'a.created_at',
                'a.updated_at',
                'o.razon_social',
                'o.nit',
                'o.nim',
                'o.estado_operador_minero'
            )
            ->orderBy('a.fecha', 'desc');

        // Aplicar filtros si existen
        if ($request->filled('tipo_filtro')) {
            $query->where('a.tipo_actualizacion', 'LIKE', "%{$request->tipo_filtro}%");
        }

        if ($request->filled('fecha_desde')) {
            $query->where('a.fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('a.fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('operador_minero_id')) {
            $query->where('a.operador_minero_id', $request->operador_minero_id);
        }

        $actualizaciones = $query->paginate(10);

        // Para el dropdown de filtros
        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        return view('actualizacion-operadors.index', compact('actualizaciones', 'operadores'));
    }

    public function create()
    {
        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        $tipos = [
            'SEPREC' => 'SEPREC',
            'RUEX' => 'RUEX',
            'NIM' => 'NIM',
            'CARTA_PODER' => 'Carta de Poder',
            'IDOM' => 'IDOM',
            'NIT' => 'NIT',
            'REPRESENTANTE_LEGAL' => 'Representante Legal',
            'DIRECCION' => 'Dirección',
            'CONTRATO_DE_ARRENDAMIENTO' => 'Contrato de Arrendamiento',
            'ARCHIVO_OBRADOS'=> 'Archivo de Obrados',
            'OTROS' => 'Otros',
        ];

        return view('actualizacion-operadors.create', compact('operadores', 'tipos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operador_minero_id' => 'required|exists:operador_minero,id_operador_minero',
            'tipos' => 'required|array|min:1',
            'tipos.*' => 'in:SEPREC,RUEX,NIM,CARTA_PODER,IDOM,NIT,REPRESENTANTE_LEGAL,DIRECCION,CONTRATO_DE_ARRENDAMIENTO,ARCHIVO_OBRADOS,OTROS',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Insertar directamente
            DB::table('actualizacion_operadors')->insert([
                'operador_minero_id' => $validated['operador_minero_id'],
                'tipo_actualizacion' => implode(',', $validated['tipos']),
                'fecha' => $validated['fecha'],
                'observaciones' => $validated['observaciones'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('actualizacion-operadors.index')
                ->with('success', 'Actualización creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $actualizacion = DB::table('actualizacion_operadors as a')
            ->leftJoin('operador_minero as o', 'a.operador_minero_id', '=', 'o.id_operador_minero')
            ->select('a.*', 'o.*')
            ->where('a.id', $id)
            ->first();

        if (!$actualizacion) {
            return redirect()->route('actualizacion-operadors.index')
                ->with('error', 'Actualización no encontrada.');
        }

        return view('actualizacion-operadors.show', compact('actualizacion'));
    }

    public function edit($id)
    {
        $actualizacion = DB::table('actualizacion_operadors')
            ->where('id', $id)
            ->first();

        if (!$actualizacion) {
            return redirect()->route('actualizacion-operadors.index')
                ->with('error', 'Actualización no encontrada.');
        }

        $operadores = DB::table('operador_minero')
            ->orderBy('razon_social')
            ->get();

        $tipos = [
            'SEPREC' => 'SEPREC',
            'RUEX' => 'RUEX',
            'NIM' => 'NIM',
            'CARTA_PODER' => 'Carta de Poder',
            'IDOM' => 'IDOM',
            'NIT' => 'NIT',
            'REPRESENTANTE_LEGAL' => 'Representante Legal',
            'DIRECCION' => 'Dirección',
            'CONTRATO_DE_ARRENDAMIENTO' => 'Contrato de Arrendamiento',
            'ARCHIVO_OBRADOS'=> 'Archivo de Obrados',
            'OTROS' => 'Otros',
        ];

        $tiposSeleccionados = $actualizacion->tipo_actualizacion
            ? explode(',', $actualizacion->tipo_actualizacion)
            : [];

        return view('actualizacion-operadors.edit', compact('actualizacion', 'operadores', 'tipos', 'tiposSeleccionados'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'operador_minero_id' => 'required|exists:operador_minero,id_operador_minero',
            'tipos' => 'required|array|min:1',
            'tipos.*' => 'in:SEPREC,RUEX,NIM,CARTA_PODER,IDOM,NIT,REPRESENTANTE_LEGAL,DIRECCION,CONTRATO_DE_ARRENDAMIENTO,ARCHIVO_OBRADOS,OTROS',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            DB::table('actualizacion_operadors')
                ->where('id', $id)
                ->update([
                    'operador_minero_id' => $validated['operador_minero_id'],
                    'tipo_actualizacion' => implode(',', $validated['tipos']),
                    'fecha' => $validated['fecha'],
                    'observaciones' => $validated['observaciones'] ?? null,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return redirect()->route('actualizacion-operadors.index')
                ->with('success', 'Actualización actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('actualizacion_operadors')->where('id', $id)->delete();

            return redirect()->route('actualizacion-operadors.index')
                ->with('success', 'Actualización eliminada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
