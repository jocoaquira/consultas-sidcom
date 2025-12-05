<?php
// app/Http/Controllers/EstadisticasController.php

namespace App\Http\Controllers;

use App\Services\FormularioStatsService;
use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    /**
     * Muestra la página de estadísticas con formulario de consulta
     */
    public function index()
    {
        return view('estadisticas.index', [
            'estadosNombres' => FormularioStatsService::getEstadosNombres(),
            'tiposNombres' => FormularioStatsService::getTiposNombres(),
        ]);
    }

    /**
     * Procesa la consulta personalizada
     */
    public function consulta(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_form_comercio' => 'required|string|in:I,E,T',
            'incluir_estado3' => 'boolean',
        ]);

        $resultados = FormularioStatsService::consultaPersonalizada(
            $request->fecha_inicio,
            $request->fecha_fin,
            $request->tipo_form_comercio,
            $request->has('incluir_estado3')
        );

        return view('estadisticas.resultado', array_merge($resultados, [
            'estadosNombres' => FormularioStatsService::getEstadosNombres(),
            'tiposNombres' => FormularioStatsService::getTiposNombres(),
        ]));
    }

    /**
     * Consulta rápida de la semana anterior (AJAX)
     */
    public function consultaSemana()
    {
        $stats = FormularioStatsService::getStatsSemana();

        return response()->json([
            'success' => true,
            'total' => $stats['totalGeneral'],
            'internos' => $stats['comercioInterno']['total'],
            'externos' => $stats['comercioExterno']['total'],
            'periodo' => $stats['periodo']['inicio'] . ' - ' . $stats['periodo']['fin'],
            'mensaje' => "Total de formularios activos de la semana pasada: {$stats['totalGeneral']}"
        ]);
    }
}
