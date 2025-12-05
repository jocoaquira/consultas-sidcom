<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Services\FormularioStatsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Página principal del sistema (dashboard)
     */
    public function index()
    {
        try {
            // Estadísticas de la semana
            $statsSemana = FormularioStatsService::getStatsSemana();

            // Estadísticas anuales
            $statsAnual = FormularioStatsService::getStatsAnual();

            // Estadísticas de los últimos 6 meses (para gráfico)
            $ultimosMeses = FormularioStatsService::getStatsUltimosMeses(6);

            // Calcular porcentaje de anulados vs activos
            $porcentajeAnuladosSemana = ($statsSemana['totalGeneral'] + $statsSemana['totalAnulados']) > 0 ?
                round(($statsSemana['totalAnulados'] / ($statsSemana['totalGeneral'] + $statsSemana['totalAnulados'])) * 100, 1) : 0;

            $porcentajeAnuladosAnual = ($statsAnual['totalGeneral'] + $statsAnual['totalAnulados']) > 0 ?
                round(($statsAnual['totalAnulados'] / ($statsAnual['totalGeneral'] + $statsAnual['totalAnulados'])) * 100, 1) : 0;

            return view('dashboard.index', [
                // Datos semanales
                'comercioInternoSemana' => $statsSemana['comercioInterno'],
                'comercioExternoSemana' => $statsSemana['comercioExterno'],
                'totalGeneralSemana' => $statsSemana['totalGeneral'],
                'totalAnuladosSemana' => $statsSemana['totalAnulados'],
                'porcentajeAnuladosSemana' => $porcentajeAnuladosSemana,
                'periodoSemana' => $statsSemana['periodo'],

                // Datos anuales
                'comercioInternoAnual' => $statsAnual['comercioInterno'],
                'comercioExternoAnual' => $statsAnual['comercioExterno'],
                'totalGeneralAnual' => $statsAnual['totalGeneral'],
                'totalAnuladosAnual' => $statsAnual['totalAnulados'],
                'porcentajeAnuladosAnual' => $porcentajeAnuladosAnual,
                'periodoAnual' => $statsAnual['periodo'],
                'promedioMensualGeneral' => $statsAnual['promedioMensualGeneral'],

                // Datos adicionales
                'ultimosMeses' => $ultimosMeses,

                // Mapeos
                'estadosNombres' => FormularioStatsService::getEstadosNombres(),
                'tiposNombres' => FormularioStatsService::getTiposNombres(),
            ]);

        } catch (\Exception $e) {
            // Si hay error, mostrar datos vacíos
            return view('dashboard.index', [
                'comercioInternoSemana' => ['total' => 0, 'emitidos' => 0, 'vencidos' => 0, 'anulados' => 0, 'hoy' => 0],
                'comercioExternoSemana' => ['total' => 0, 'emitidos' => 0, 'vencidos' => 0, 'anulados' => 0, 'hoy' => 0],
                'totalGeneralSemana' => 0,
                'totalAnuladosSemana' => 0,
                'porcentajeAnuladosSemana' => 0,
                'periodoSemana' => ['inicio' => date('d/m/Y', strtotime('-7 days')), 'fin' => date('d/m/Y', strtotime('-1 day')), 'hoy' => date('d/m/Y')],

                'comercioInternoAnual' => ['total' => 0, 'emitidos' => 0, 'vencidos' => 0, 'anulados' => 0, 'promedio_mensual' => 0],
                'comercioExternoAnual' => ['total' => 0, 'emitidos' => 0, 'vencidos' => 0, 'anulados' => 0, 'promedio_mensual' => 0],
                'totalGeneralAnual' => 0,
                'totalAnuladosAnual' => 0,
                'porcentajeAnuladosAnual' => 0,
                'periodoAnual' => ['inicio' => '01/01/' . date('Y'), 'fin' => date('d/m/Y', strtotime('-1 day')), 'ano_actual' => date('Y'), 'meses_transcurridos' => date('n')],

                'ultimosMeses' => [],

                'estadosNombres' => FormularioStatsService::getEstadosNombres(),
                'tiposNombres' => FormularioStatsService::getTiposNombres(),
            ]);
        }
    }
}
