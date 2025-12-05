<?php
// app/Services/FormularioStatsService.php

namespace App\Services;

use App\Models\Formulario;
use Carbon\Carbon;

class FormularioStatsService
{
    /**
     * Mapeo de estados a nombres legibles
     */
    public static function getEstadosNombres()
    {
        return [
            '0' => 'Anulado',
            '1' => 'Emitido',
            '2' => 'Vencido',
            '3' => 'Estado 3',
        ];
    }

    /**
     * Mapeo de tipos de comercio
     */
    public static function getTiposNombres()
    {
        return [
            'I' => 'Interno',
            'E' => 'Externo',
            'T' => 'Todos'
        ];
    }

    /**
     * Consulta de estadísticas de la semana (desde el día anterior)
     */
    public static function getStatsSemana()
    {
        $hoy = Carbon::now();
        $ayer = $hoy->copy()->subDay();
        $semanaPasada = $ayer->copy()->subDays(6);

        // Comercio Interno - Estados activos (1, 2) para totales
        $internosTotal = Formulario::where('tipo_form_comercio', 'I')
            ->whereIn('estado_formulario', ['1', '2'])
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $internosEmitidos = Formulario::where('tipo_form_comercio', 'I')
            ->where('estado_formulario', '1')
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $internosVencidos = Formulario::where('tipo_form_comercio', 'I')
            ->where('estado_formulario', '2')
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Comercio Interno - Anulados (separado, no cuenta en total)
        $internosAnulados = Formulario::where('tipo_form_comercio', 'I')
            ->where('estado_formulario', '0')
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Comercio Externo - Estados activos (1, 2) para totales
        $externosTotal = Formulario::where('tipo_form_comercio', 'E')
            ->whereIn('estado_formulario', ['1', '2'])
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $externosEmitidos = Formulario::where('tipo_form_comercio', 'E')
            ->where('estado_formulario', '1')
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $externosVencidos = Formulario::where('tipo_form_comercio', 'E')
            ->where('estado_formulario', '2')
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Comercio Externo - Anulados (separado, no cuenta en total)
        $externosAnulados = Formulario::where('tipo_form_comercio', 'E')
            ->where('estado_formulario', '0')
            ->whereBetween('fecha_creacion', [
                $semanaPasada->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Totales del día actual (solo activos)
        $hoyInterno = Formulario::where('tipo_form_comercio', 'I')
            ->whereIn('estado_formulario', ['1', '2'])
            ->whereDate('fecha_creacion', $hoy->format('Y-m-d'))
            ->count();

        $hoyExterno = Formulario::where('tipo_form_comercio', 'E')
            ->whereIn('estado_formulario', ['1', '2'])
            ->whereDate('fecha_creacion', $hoy->format('Y-m-d'))
            ->count();

        return [
            'periodo' => [
                'inicio' => $semanaPasada->format('d/m/Y'),
                'fin' => $ayer->format('d/m/Y'),
                'hoy' => $hoy->format('d/m/Y'),
            ],
            'comercioInterno' => [
                'total' => $internosTotal,
                'emitidos' => $internosEmitidos,
                'vencidos' => $internosVencidos,
                'anulados' => $internosAnulados,
                'hoy' => $hoyInterno,
            ],
            'comercioExterno' => [
                'total' => $externosTotal,
                'emitidos' => $externosEmitidos,
                'vencidos' => $externosVencidos,
                'anulados' => $externosAnulados,
                'hoy' => $hoyExterno,
            ],
            'totalGeneral' => $internosTotal + $externosTotal,
            'totalAnulados' => $internosAnulados + $externosAnulados,
        ];
    }

    /**
     * Consulta de estadísticas del año (desde el inicio del año hasta ayer)
     */
    public static function getStatsAnual()
    {
        $hoy = Carbon::now();
        $ayer = $hoy->copy()->subDay();
        $inicioAno = Carbon::create($hoy->year, 1, 1, 0, 0, 0);

        // Comercio Interno anual - Estados activos (1, 2)
        $anualInternoTotal = Formulario::where('tipo_form_comercio', 'I')
            ->whereIn('estado_formulario', ['1', '2'])
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $anualInternoEmitidos = Formulario::where('tipo_form_comercio', 'I')
            ->where('estado_formulario', '1')
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $anualInternoVencidos = Formulario::where('tipo_form_comercio', 'I')
            ->where('estado_formulario', '2')
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Comercio Interno anual - Anulados
        $anualInternoAnulados = Formulario::where('tipo_form_comercio', 'I')
            ->where('estado_formulario', '0')
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Comercio Externo anual - Estados activos (1, 2)
        $anualExternoTotal = Formulario::where('tipo_form_comercio', 'E')
            ->whereIn('estado_formulario', ['1', '2'])
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $anualExternoEmitidos = Formulario::where('tipo_form_comercio', 'E')
            ->where('estado_formulario', '1')
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        $anualExternoVencidos = Formulario::where('tipo_form_comercio', 'E')
            ->where('estado_formulario', '2')
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Comercio Externo anual - Anulados
        $anualExternoAnulados = Formulario::where('tipo_form_comercio', 'E')
            ->where('estado_formulario', '0')
            ->whereBetween('fecha_creacion', [
                $inicioAno->format('Y-m-d 00:00:00'),
                $ayer->format('Y-m-d 23:59:59')
            ])
            ->count();

        // Promedio mensual (solo activos)
        $mesesTranscurridos = $hoy->month;
        $promedioMensualInterno = $mesesTranscurridos > 0 ? round($anualInternoTotal / $mesesTranscurridos) : 0;
        $promedioMensualExterno = $mesesTranscurridos > 0 ? round($anualExternoTotal / $mesesTranscurridos) : 0;

        return [
            'periodo' => [
                'inicio' => $inicioAno->format('d/m/Y'),
                'fin' => $ayer->format('d/m/Y'),
                'ano_actual' => $hoy->year,
                'meses_transcurridos' => $mesesTranscurridos,
            ],
            'comercioInterno' => [
                'total' => $anualInternoTotal,
                'emitidos' => $anualInternoEmitidos,
                'vencidos' => $anualInternoVencidos,
                'anulados' => $anualInternoAnulados,
                'promedio_mensual' => $promedioMensualInterno,
            ],
            'comercioExterno' => [
                'total' => $anualExternoTotal,
                'emitidos' => $anualExternoEmitidos,
                'vencidos' => $anualExternoVencidos,
                'anulados' => $anualExternoAnulados,
                'promedio_mensual' => $promedioMensualExterno,
            ],
            'totalGeneral' => $anualInternoTotal + $anualExternoTotal,
            'totalAnulados' => $anualInternoAnulados + $anualExternoAnulados,
            'promedioMensualGeneral' => $promedioMensualInterno + $promedioMensualExterno,
        ];
    }

    /**
     * Obtiene estadísticas de los últimos 6 meses por mes (solo activos)
     */
    public static function getStatsUltimosMeses($meses = 6)
    {
        $hoy = Carbon::now();
        $inicio = $hoy->copy()->subMonths($meses)->startOfMonth();
        $fin = $hoy->copy()->subDay()->endOfDay();

        $datosMensuales = Formulario::selectRaw('
                YEAR(fecha_creacion) as ano,
                MONTH(fecha_creacion) as mes,
                tipo_form_comercio,
                estado_formulario,
                COUNT(*) as total
            ')
            ->whereBetween('fecha_creacion', [$inicio, $fin])
            ->whereIn('estado_formulario', ['0', '1', '2']) // Incluimos anulados para análisis
            ->groupBy('ano', 'mes', 'tipo_form_comercio', 'estado_formulario')
            ->orderBy('ano', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        // Formatear datos para la vista
        $mesesData = [];
        $nombresMeses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subMonths($i);
            $ano = $fecha->year;
            $mes = $fecha->month;
            $key = $ano . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT);

            $mesesData[$key] = [
                'label' => $nombresMeses[$mes] . ' ' . $ano,
                'interno_activos' => 0,
                'interno_anulados' => 0,
                'externo_activos' => 0,
                'externo_anulados' => 0,
                'total_activos' => 0,
                'total_anulados' => 0,
            ];
        }

        foreach ($datosMensuales as $dato) {
            $key = $dato->ano . '-' . str_pad($dato->mes, 2, '0', STR_PAD_LEFT);

            if (isset($mesesData[$key])) {
                if ($dato->tipo_form_comercio === 'I') {
                    if ($dato->estado_formulario === '0') {
                        $mesesData[$key]['interno_anulados'] += $dato->total;
                    } else {
                        $mesesData[$key]['interno_activos'] += $dato->total;
                    }
                } elseif ($dato->tipo_form_comercio === 'E') {
                    if ($dato->estado_formulario === '0') {
                        $mesesData[$key]['externo_anulados'] += $dato->total;
                    } else {
                        $mesesData[$key]['externo_activos'] += $dato->total;
                    }
                }

                $mesesData[$key]['total_activos'] = $mesesData[$key]['interno_activos'] + $mesesData[$key]['externo_activos'];
                $mesesData[$key]['total_anulados'] = $mesesData[$key]['interno_anulados'] + $mesesData[$key]['externo_anulados'];
            }
        }

        return array_values($mesesData);
    }

    /**
     * Consulta personalizada
     */
    public static function consultaPersonalizada($fechaInicio, $fechaFin, $tipo = 'I', $estados = ['1', '2'])
    {
        $inicio = Carbon::parse($fechaInicio)->startOfDay();
        $fin = Carbon::parse($fechaFin)->endOfDay();

        // Validar estados
        $estadosValidos = is_array($estados) ? $estados : ['1', '2'];

        // Consulta base
        $query = Formulario::whereBetween('fecha_creacion', [$inicio, $fin])
            ->whereIn('estado_formulario', $estadosValidos);

        if ($tipo !== 'T') {
            $query->where('tipo_form_comercio', $tipo);
        }

        // Total general
        $total = $query->count();

        // Por estado
        $queryEstado = Formulario::selectRaw('estado_formulario, COUNT(*) as total')
            ->whereBetween('fecha_creacion', [$inicio, $fin])
            ->whereIn('estado_formulario', $estadosValidos);

        if ($tipo !== 'T') {
            $queryEstado->where('tipo_form_comercio', $tipo);
        }

        $porEstado = $queryEstado->groupBy('estado_formulario')
            ->orderBy('estado_formulario')
            ->get()
            ->keyBy('estado_formulario');

        // Por día
        $queryDia = Formulario::selectRaw('DATE(fecha_creacion) as fecha, COUNT(*) as total')
            ->whereBetween('fecha_creacion', [$inicio, $fin])
            ->whereIn('estado_formulario', $estadosValidos);

        if ($tipo !== 'T') {
            $queryDia->where('tipo_form_comercio', $tipo);
        }

        $porDia = $queryDia->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Por tipo (si se seleccionó "Todos")
        $porTipo = null;
        if ($tipo === 'T') {
            $porTipo = Formulario::selectRaw('tipo_form_comercio, COUNT(*) as total')
                ->whereBetween('fecha_creacion', [$inicio, $fin])
                ->whereIn('estado_formulario', $estadosValidos)
                ->groupBy('tipo_form_comercio')
                ->orderBy('tipo_form_comercio')
                ->get();
        }

        return [
            'total' => $total,
            'fechaInicio' => $inicio->format('d/m/Y'),
            'fechaFin' => $fin->format('d/m/Y'),
            'porEstado' => $porEstado,
            'porDia' => $porDia,
            'estados' => $estadosValidos,
            'tipo' => $tipo,
            'porTipo' => $porTipo,
        ];
    }
}
