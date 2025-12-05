<?php

namespace App\Http\Controllers;

use App\Models\Formulario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    /**
     * Muestra el dashboard de estadísticas
     */
    public function index()
    {
        // Fechas para la consulta de la semana
        $hoy = Carbon::now();
        $ayer = $hoy->copy()->subDay();
        $semanaPasada = $ayer->copy()->subWeek();

        // CONSULTA PARA COMERCIO INTERNO (I)
        $comercioInterno = [
            'total' => Formulario::comercioInterno()
                ->activos()
                ->lastWeekFromYesterday()
                ->count(),
            'emitidos' => Formulario::comercioInterno()
                ->emitidos()
                ->lastWeekFromYesterday()
                ->count(),
            'vencidos' => Formulario::comercioInterno()
                ->vencidos()
                ->lastWeekFromYesterday()
                ->count(),
        ];

        // CONSULTA PARA COMERCIO EXTERNO (E)
        $comercioExterno = [
            'total' => Formulario::comercioExterno()
                ->activos()
                ->lastWeekFromYesterday()
                ->count(),
            'emitidos' => Formulario::comercioExterno()
                ->emitidos()
                ->lastWeekFromYesterday()
                ->count(),
            'vencidos' => Formulario::comercioExterno()
                ->vencidos()
                ->lastWeekFromYesterday()
                ->count(),
        ];

        // Total general (ambos tipos)
        $totalGeneral = $comercioInterno['total'] + $comercioExterno['total'];

        // Estadísticas del día actual
        $hoyInterno = Formulario::comercioInterno()
            ->activos()
            ->whereDate('fecha_creacion', $hoy->format('Y-m-d'))
            ->count();

        $hoyExterno = Formulario::comercioExterno()
            ->activos()
            ->whereDate('fecha_creacion', $hoy->format('Y-m-d'))
            ->count();

        return view('estadisticas.index', [
            'comercioInterno' => $comercioInterno,
            'comercioExterno' => $comercioExterno,
            'totalGeneral' => $totalGeneral,
            'hoyInterno' => $hoyInterno,
            'hoyExterno' => $hoyExterno,
            'fechaInicio' => $semanaPasada->format('d/m/Y'),
            'fechaFin' => $ayer->format('d/m/Y'),
            'hoy' => $hoy->format('d/m/Y'),
        ]);
    }

    /**
     * Procesa la consulta personalizada por fechas
     */
    public function consultaPersonalizada(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_form_comercio' => 'string|in:I,E,T',
            'incluir_estado3' => 'boolean',
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
        $tipo = $request->tipo_form_comercio ?: 'I';

        // Definir estados
        $estados = ['1', '2'];
        if ($request->has('incluir_estado3') && $request->incluir_estado3) {
            $estados[] = '3';
        }

        // Consulta según el tipo seleccionado
        $query = Formulario::query()
            ->whereBetween('fecha_creacion', [$fechaInicio, $fechaFin])
            ->whereIn('estado_formulario', $estados);

        // Filtrar por tipo si no es "T" (todos)
        if ($tipo !== 'T') {
            $query->where('tipo_form_comercio', $tipo);
        }

        // Totales
        $total = $query->count();

        // Detalle por estado
        $porEstado = Formulario::selectRaw('estado_formulario, COUNT(*) as total')
            ->whereBetween('fecha_creacion', [$fechaInicio, $fechaFin])
            ->whereIn('estado_formulario', $estados);

        if ($tipo !== 'T') {
            $porEstado->where('tipo_form_comercio', $tipo);
        }

        $porEstado = $porEstado->groupBy('estado_formulario')
            ->orderBy('estado_formulario')
            ->get()
            ->keyBy('estado_formulario');

        // Datos por día
        $datosPorDia = Formulario::selectRaw('DATE(fecha_creacion) as fecha, COUNT(*) as total')
            ->whereBetween('fecha_creacion', [$fechaInicio, $fechaFin])
            ->whereIn('estado_formulario', $estados);

        if ($tipo !== 'T') {
            $datosPorDia->where('tipo_form_comercio', $tipo);
        }

        $datosPorDia = $datosPorDia->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Si se seleccionó "Todos", mostrar también distribución por tipo
        if ($tipo === 'T') {
            $porTipo = Formulario::selectRaw('tipo_form_comercio, COUNT(*) as total')
                ->whereBetween('fecha_creacion', [$fechaInicio, $fechaFin])
                ->whereIn('estado_formulario', $estados)
                ->groupBy('tipo_form_comercio')
                ->orderBy('tipo_form_comercio')
                ->get();
        }

        return view('estadisticas.resultado', [
            'total' => $total,
            'fechaInicio' => $fechaInicio->format('d/m/Y'),
            'fechaFin' => $fechaFin->format('d/m/Y'),
            'porEstado' => $porEstado,
            'datosPorDia' => $datosPorDia,
            'estados' => $estados,
            'tipo' => $tipo,
            'porTipo' => $tipo === 'T' ? $porTipo : null,
            'incluirEstado3' => in_array('3', $estados),
        ]);
    }

    /**
     * Consulta rápida de la semana anterior (desde el día anterior)
     */
    public function consultaSemana()
    {
        $hoy = Carbon::now();
        $ayer = $hoy->copy()->subDay();
        $semanaPasada = $ayer->copy()->subWeek();

        // Totales por tipo de comercio
        $internos = Formulario::comercioInterno()
            ->activos()
            ->lastWeekFromYesterday()
            ->count();

        $externos = Formulario::comercioExterno()
            ->activos()
            ->lastWeekFromYesterday()
            ->count();

        $total = $internos + $externos;

        return response()->json([
            'success' => true,
            'total' => $total,
            'internos' => $internos,
            'externos' => $externos,
            'periodo' => $semanaPasada->format('d/m/Y') . ' - ' . $ayer->format('d/m/Y'),
            'mensaje' => "Total de formularios activos de la semana pasada: {$total}"
        ]);
    }
}
