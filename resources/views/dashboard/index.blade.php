@extends('layouts.app')

@section('title', 'Dashboard - SIDCOM')

@section('content')
<div class="container-fluid">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>FORMULARIOS 101
                        <span class="float-end">
                            <small>Año {{ $periodoAnual['ano_actual'] }} | Hoy: {{ $periodoSemana['hoy'] }}</small>
                        </span>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicadores clave -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-primary shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Activos - Semana</h6>
                    <h2 class="text-primary">{{ $totalGeneralSemana }}</h2>
                    <small class="text-muted">{{ $periodoSemana['inicio'] }} - {{ $periodoSemana['fin'] }}</small>
                    @if($totalAnuladosSemana > 0)
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            {{ $totalAnuladosSemana }} anulados ({{ $porcentajeAnuladosSemana }}%)
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-success shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Activos - Anual</h6>
                    <h2 class="text-success">{{ $totalGeneralAnual }}</h2>
                    <small class="text-muted">{{ $periodoAnual['inicio'] }} - {{ $periodoAnual['fin'] }}</small>
                    @if($totalAnuladosAnual > 0)
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            {{ $totalAnuladosAnual }} anulados ({{ $porcentajeAnuladosAnual }}%)
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-info shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Promedio Mensual</h6>
                    <h2 class="text-info">{{ $promedioMensualGeneral }}</h2>
                    <small class="text-muted">{{ $periodoAnual['meses_transcurridos'] }} meses transcurridos</small>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-calculator me-1"></i>
                            {{ round($totalGeneralAnual / max($periodoAnual['meses_transcurridos'], 1)) }} por mes
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-warning shadow">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Tasa de Anulación</h6>
                    <h4 class="text-warning">{{ $porcentajeAnuladosAnual }}%</h4>
                    <small class="text-muted">Del total histórico</small>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-arrow-down-up me-1"></i>
                            Semana: {{ $porcentajeAnuladosSemana }}%
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Estadísticas Semanales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-week me-2"></i>Estadísticas de la Última Semana
                        <small class="float-end">{{ $periodoSemana['inicio'] }} - {{ $periodoSemana['fin'] }}</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- COMERCIO INTERNO SEMANAL -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card border-primary shadow-sm">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="bi bi-house-door me-2"></i>Comercio Interno (I) - Semana
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-primary fs-6">{{ $comercioInternoSemana['total'] }}</span>
                                        @if($comercioInternoSemana['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioInternoSemana['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-success">
                                                <div class="card-body py-3">
                                                    <h6 class="text-success mb-1">
                                                        <i class="bi bi-check-circle me-1"></i>Emitidos
                                                    </h6>
                                                    <h4 class="text-success mb-0">{{ $comercioInternoSemana['emitidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['1'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body py-3">
                                                    <h6 class="text-warning mb-1">
                                                        <i class="bi bi-clock-history me-1"></i>Vencidos
                                                    </h6>
                                                    <h4 class="text-warning mb-0">{{ $comercioInternoSemana['vencidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['2'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-danger">
                                                <div class="card-body py-3">
                                                    <h6 class="text-danger mb-1">
                                                        <i class="bi bi-x-circle me-1"></i>Anulados
                                                    </h6>
                                                    <h4 class="text-danger mb-0">{{ $comercioInternoSemana['anulados'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['0'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-primary">
                                                <div class="card-body py-3">
                                                    <h6 class="text-primary mb-1">
                                                        <i class="bi bi-calendar-check me-1"></i>Hoy (Activos)
                                                    </h6>
                                                    <h4 class="text-primary mb-0">{{ $comercioInternoSemana['hoy'] }}</h4>
                                                    <small class="text-muted">{{ $periodoSemana['hoy'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($comercioInternoSemana['total'] > 0 || $comercioInternoSemana['anulados'] > 0)
                                    <div class="mt-2">
                                        <div class="progress" style="height: 15px;">
                                            @php
                                                $totalConAnulados = $comercioInternoSemana['total'] + $comercioInternoSemana['anulados'];
                                                $porcentajeEmitidos = $totalConAnulados > 0 ? round(($comercioInternoSemana['emitidos'] / $totalConAnulados) * 100) : 0;
                                                $porcentajeVencidos = $totalConAnulados > 0 ? round(($comercioInternoSemana['vencidos'] / $totalConAnulados) * 100) : 0;
                                                $porcentajeAnulados = $totalConAnulados > 0 ? round(($comercioInternoSemana['anulados'] / $totalConAnulados) * 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $porcentajeEmitidos }}%">
                                                {{ $comercioInternoSemana['emitidos'] }}
                                            </div>
                                            <div class="progress-bar bg-warning" style="width: {{ $porcentajeVencidos }}%">
                                                {{ $comercioInternoSemana['vencidos'] }}
                                            </div>
                                            <div class="progress-bar bg-danger" style="width: {{ $porcentajeAnulados }}%">
                                                {{ $comercioInternoSemana['anulados'] }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-success">Emitidos: {{ $porcentajeEmitidos }}%</small>
                                            <small class="text-warning">Vencidos: {{ $porcentajeVencidos }}%</small>
                                            <small class="text-danger">Anulados: {{ $porcentajeAnulados }}%</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- COMERCIO EXTERNO SEMANAL -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card border-info shadow-sm">
                                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="bi bi-globe me-2"></i>Comercio Externo (E) - Semana
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-info fs-6">{{ $comercioExternoSemana['total'] }}</span>
                                        @if($comercioExternoSemana['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioExternoSemana['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-success">
                                                <div class="card-body py-3">
                                                    <h6 class="text-success mb-1">
                                                        <i class="bi bi-check-circle me-1"></i>Emitidos
                                                    </h6>
                                                    <h4 class="text-success mb-0">{{ $comercioExternoSemana['emitidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['1'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body py-3">
                                                    <h6 class="text-warning mb-1">
                                                        <i class="bi bi-clock-history me-1"></i>Vencidos
                                                    </h6>
                                                    <h4 class="text-warning mb-0">{{ $comercioExternoSemana['vencidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['2'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-danger">
                                                <div class="card-body py-3">
                                                    <h6 class="text-danger mb-1">
                                                        <i class="bi bi-x-circle me-1"></i>Anulados
                                                    </h6>
                                                    <h4 class="text-danger mb-0">{{ $comercioExternoSemana['anulados'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['0'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-info">
                                                <div class="card-body py-3">
                                                    <h6 class="text-info mb-1">
                                                        <i class="bi bi-calendar-check me-1"></i>Hoy (Activos)
                                                    </h6>
                                                    <h4 class="text-info mb-0">{{ $comercioExternoSemana['hoy'] }}</h4>
                                                    <small class="text-muted">{{ $periodoSemana['hoy'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($comercioExternoSemana['total'] > 0 || $comercioExternoSemana['anulados'] > 0)
                                    <div class="mt-2">
                                        <div class="progress" style="height: 15px;">
                                            @php
                                                $totalConAnuladosExt = $comercioExternoSemana['total'] + $comercioExternoSemana['anulados'];
                                                $porcentajeEmitidosExt = $totalConAnuladosExt > 0 ? round(($comercioExternoSemana['emitidos'] / $totalConAnuladosExt) * 100) : 0;
                                                $porcentajeVencidosExt = $totalConAnuladosExt > 0 ? round(($comercioExternoSemana['vencidos'] / $totalConAnuladosExt) * 100) : 0;
                                                $porcentajeAnuladosExt = $totalConAnuladosExt > 0 ? round(($comercioExternoSemana['anulados'] / $totalConAnuladosExt) * 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $porcentajeEmitidosExt }}%">
                                                {{ $comercioExternoSemana['emitidos'] }}
                                            </div>
                                            <div class="progress-bar bg-warning" style="width: {{ $porcentajeVencidosExt }}%">
                                                {{ $comercioExternoSemana['vencidos'] }}
                                            </div>
                                            <div class="progress-bar bg-danger" style="width: {{ $porcentajeAnuladosExt }}%">
                                                {{ $comercioExternoSemana['anulados'] }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-success">Emitidos: {{ $porcentajeEmitidosExt }}%</small>
                                            <small class="text-warning">Vencidos: {{ $porcentajeVencidosExt }}%</small>
                                            <small class="text-danger">Anulados: {{ $porcentajeAnuladosExt }}%</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Estadísticas Anuales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-range me-2"></i>Estadísticas del Año {{ $periodoAnual['ano_actual'] }}
                        <small class="float-end">{{ $periodoAnual['inicio'] }} - {{ $periodoAnual['fin'] }}</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- COMERCIO INTERNO ANUAL -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card border-primary shadow-sm">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="bi bi-house-door me-2"></i>Comercio Interno (I) - Anual
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-primary fs-6">{{ $comercioInternoAnual['total'] }}</span>
                                        @if($comercioInternoAnual['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioInternoAnual['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-success">
                                                <div class="card-body py-3">
                                                    <h6 class="text-success mb-1">
                                                        <i class="bi bi-check-circle me-1"></i>Emitidos
                                                    </h6>
                                                    <h4 class="text-success mb-0">{{ $comercioInternoAnual['emitidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['1'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body py-3">
                                                    <h6 class="text-warning mb-1">
                                                        <i class="bi bi-clock-history me-1"></i>Vencidos
                                                    </h6>
                                                    <h4 class="text-warning mb-0">{{ $comercioInternoAnual['vencidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['2'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-danger">
                                                <div class="card-body py-3">
                                                    <h6 class="text-danger mb-1">
                                                        <i class="bi bi-x-circle me-1"></i>Anulados
                                                    </h6>
                                                    <h4 class="text-danger mb-0">{{ $comercioInternoAnual['anulados'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['0'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-info">
                                                <div class="card-body py-3">
                                                    <h6 class="text-info mb-1">
                                                        <i class="bi bi-graph-up me-1"></i>Prom. Mensual
                                                    </h6>
                                                    <h4 class="text-info mb-0">{{ $comercioInternoAnual['promedio_mensual'] }}</h4>
                                                    <small class="text-muted">Activos por mes</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($comercioInternoAnual['total'] > 0 || $comercioInternoAnual['anulados'] > 0)
                                    <div class="mt-2">
                                        <div class="progress" style="height: 15px;">
                                            @php
                                                $totalConAnuladosAnual = $comercioInternoAnual['total'] + $comercioInternoAnual['anulados'];
                                                $porcentajeAnualEmitidos = $totalConAnuladosAnual > 0 ? round(($comercioInternoAnual['emitidos'] / $totalConAnuladosAnual) * 100) : 0;
                                                $porcentajeAnualVencidos = $totalConAnuladosAnual > 0 ? round(($comercioInternoAnual['vencidos'] / $totalConAnuladosAnual) * 100) : 0;
                                                $porcentajeAnualAnulados = $totalConAnuladosAnual > 0 ? round(($comercioInternoAnual['anulados'] / $totalConAnuladosAnual) * 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $porcentajeAnualEmitidos }}%">
                                                {{ $comercioInternoAnual['emitidos'] }}
                                            </div>
                                            <div class="progress-bar bg-warning" style="width: {{ $porcentajeAnualVencidos }}%">
                                                {{ $comercioInternoAnual['vencidos'] }}
                                            </div>
                                            <div class="progress-bar bg-danger" style="width: {{ $porcentajeAnualAnulados }}%">
                                                {{ $comercioInternoAnual['anulados'] }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-success">Emitidos: {{ $porcentajeAnualEmitidos }}%</small>
                                            <small class="text-warning">Vencidos: {{ $porcentajeAnualVencidos }}%</small>
                                            <small class="text-danger">Anulados: {{ $porcentajeAnualAnulados }}%</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- COMERCIO EXTERNO ANUAL -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card border-info shadow-sm">
                                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="bi bi-globe me-2"></i>Comercio Externo (E) - Anual
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-info fs-6">{{ $comercioExternoAnual['total'] }}</span>
                                        @if($comercioExternoAnual['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioExternoAnual['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-success">
                                                <div class="card-body py-3">
                                                    <h6 class="text-success mb-1">
                                                        <i class="bi bi-check-circle me-1"></i>Emitidos
                                                    </h6>
                                                    <h4 class="text-success mb-0">{{ $comercioExternoAnual['emitidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['1'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body py-3">
                                                    <h6 class="text-warning mb-1">
                                                        <i class="bi bi-clock-history me-1"></i>Vencidos
                                                    </h6>
                                                    <h4 class="text-warning mb-0">{{ $comercioExternoAnual['vencidos'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['2'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-danger">
                                                <div class="card-body py-3">
                                                    <h6 class="text-danger mb-1">
                                                        <i class="bi bi-x-circle me-1"></i>Anulados
                                                    </h6>
                                                    <h4 class="text-danger mb-0">{{ $comercioExternoAnual['anulados'] }}</h4>
                                                    <small class="text-muted">{{ $estadosNombres['0'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="card border-info">
                                                <div class="card-body py-3">
                                                    <h6 class="text-info mb-1">
                                                        <i class="bi bi-graph-up me-1"></i>Prom. Mensual
                                                    </h6>
                                                    <h4 class="text-info mb-0">{{ $comercioExternoAnual['promedio_mensual'] }}</h4>
                                                    <small class="text-muted">Activos por mes</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($comercioExternoAnual['total'] > 0 || $comercioExternoAnual['anulados'] > 0)
                                    <div class="mt-2">
                                        <div class="progress" style="height: 15px;">
                                            @php
                                                $totalConAnuladosAnualExt = $comercioExternoAnual['total'] + $comercioExternoAnual['anulados'];
                                                $porcentajeAnualEmitidosExt = $totalConAnuladosAnualExt > 0 ? round(($comercioExternoAnual['emitidos'] / $totalConAnuladosAnualExt) * 100) : 0;
                                                $porcentajeAnualVencidosExt = $totalConAnuladosAnualExt > 0 ? round(($comercioExternoAnual['vencidos'] / $totalConAnuladosAnualExt) * 100) : 0;
                                                $porcentajeAnualAnuladosExt = $totalConAnuladosAnualExt > 0 ? round(($comercioExternoAnual['anulados'] / $totalConAnuladosAnualExt) * 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $porcentajeAnualEmitidosExt }}%">
                                                {{ $comercioExternoAnual['emitidos'] }}
                                            </div>
                                            <div class="progress-bar bg-warning" style="width: {{ $porcentajeAnualVencidosExt }}%">
                                                {{ $comercioExternoAnual['vencidos'] }}
                                            </div>
                                            <div class="progress-bar bg-danger" style="width: {{ $porcentajeAnualAnuladosExt }}%">
                                                {{ $comercioExternoAnual['anulados'] }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-success">Emitidos: {{ $porcentajeAnualEmitidosExt }}%</small>
                                            <small class="text-warning">Vencidos: {{ $porcentajeAnualVencidosExt }}%</small>
                                            <small class="text-danger">Anulados: {{ $porcentajeAnualAnuladosExt }}%</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Anulados -->
    @if($totalAnuladosSemana > 0 || $totalAnuladosAnual > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>Resumen de Formularios Anulados
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h6 class="text-danger mb-2">Semana</h6>
                                    <h2 class="text-danger">{{ $totalAnuladosSemana }}</h2>
                                    <small class="text-muted">Anulados en la última semana</small>
                                    @if($totalGeneralSemana > 0)
                                    <div class="mt-2">
                                        <small>Tasa: {{ $porcentajeAnuladosSemana }}%</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h6 class="text-danger mb-2">Año {{ $periodoAnual['ano_actual'] }}</h6>
                                    <h2 class="text-danger">{{ $totalAnuladosAnual }}</h2>
                                    <small class="text-muted">Anulados acumulados</small>
                                    @if($totalGeneralAnual > 0)
                                    <div class="mt-2">
                                        <small>Tasa: {{ $porcentajeAnuladosAnual }}%</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="text-warning mb-2">Distribución</h6>
                                    <div class="d-flex justify-content-around">
                                        <div>
                                            <h5 class="text-primary">{{ $comercioInternoAnual['anulados'] + $comercioInternoSemana['anulados'] }}</h5>
                                            <small>Interno (I)</small>
                                        </div>
                                        <div>
                                            <h5 class="text-info">{{ $comercioExternoAnual['anulados'] + $comercioExternoSemana['anulados'] }}</h5>
                                            <small>Externo (E)</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">Total combinado</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tendencias (últimos 6 meses) -->
    @if(!empty($ultimosMeses))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Tendencias - Últimos 6 Meses
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Mes</th>
                                    <th>Interno (I)</th>
                                    <th>Externo (E)</th>
                                    <th>Total Activos</th>
                                    <th>Anulados</th>
                                    <th>Tasa Anulación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosMeses as $mes)
                                @php
                                    $totalMes = $mes['total_activos'] + $mes['total_anulados'];
                                    $tasaAnulacion = $totalMes > 0 ? round(($mes['total_anulados'] / $totalMes) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $mes['label'] }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $mes['interno_activos'] }}</span>
                                        @if($mes['interno_anulados'] > 0)
                                        <small class="text-danger ms-1">
                                            <i class="bi bi-x-circle"></i> {{ $mes['interno_anulados'] }}
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $mes['externo_activos'] }}</span>
                                        @if($mes['externo_anulados'] > 0)
                                        <small class="text-danger ms-1">
                                            <i class="bi bi-x-circle"></i> {{ $mes['externo_anulados'] }}
                                        </small>
                                        @endif
                                    </td>
                                    <td><strong>{{ $mes['total_activos'] }}</strong></td>
                                    <td>
                                        @if($mes['total_anulados'] > 0)
                                        <span class="badge bg-danger">{{ $mes['total_anulados'] }}</span>
                                        @else
                                        <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tasaAnulacion > 0)
                                        <span class="badge bg-{{ $tasaAnulacion > 10 ? 'danger' : ($tasaAnulacion > 5 ? 'warning' : 'success') }}">
                                            {{ $tasaAnulacion }}%
                                        </span>
                                        @else
                                        <span class="badge bg-success">0%</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif




    <!-- Sección de Gráficas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Visualización Gráfica de Datos
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Gráfica 1: Distribución Semanal -->
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card chart-card">
                            <div class="chart-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-pie-chart-fill me-2"></i>Distribución Semanal
                                    <small class="float-end">{{ $periodoSemana['inicio'] }} - {{ $periodoSemana['fin'] }}</small>
                                </h6>
                            </div>
                            <div class="chart-body">
                                <canvas id="graficaSemanal" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfica 2: Distribución Anual -->
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card chart-card">
                            <div class="chart-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-bar-chart me-2"></i>Distribución Anual
                                    <small class="float-end">Año {{ $periodoAnual['ano_actual'] }}</small>
                                </h6>
                            </div>
                            <div class="chart-body">
                                <canvas id="graficaAnual" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfica 3: Tendencia Mensual -->
                    <div class="col-lg-12 col-md-12 mb-4">
                        <div class="card chart-card">
                            <div class="chart-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-graph-up me-2"></i>Tendencia - Últimos 6 Meses
                                </h6>
                            </div>
                            <div class="chart-body">
                                <canvas id="graficaMensual" height="150"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfica 4: Comparativa Semana vs Año -->
                    <div class="col-lg-12 col-md-12 mb-4">
                        <div class="card chart-card">
                            <div class="chart-header bg-warning text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-clipboard-data me-2"></i>Comparativa Semana vs Año
                                </h6>
                            </div>
                            <div class="chart-body">
                                <canvas id="graficaComparativa" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





    <!-- Acciones rápidas -->
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('estadisticas.index') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="bi bi-bar-chart fs-1 text-primary mb-3"></i>
                    <h6>Estadísticas Detalladas</h6>
                    <p class="text-muted mb-0 small">Consultas personalizadas</p>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('operadores.index') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1 text-success mb-3"></i>
                    <h6>Operadores Mineros</h6>
                    <p class="text-muted mb-0 small">Gestión de operadores</p>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('actualizacion-operadors.index') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="bi bi-list-check fs-1 text-warning mb-3"></i>
                    <h6>Actualizaciones</h6>
                    <p class="text-muted mb-0 small">Registro y seguimiento</p>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('toma-muestra.index') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="bi bi-droplet fs-1 text-info mb-3"></i>
                    <h6>Toma de Muestra</h6>
                    <p class="text-muted mb-0 small">Gestión de muestras</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Información del sistema -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-light">
                <div class="card-body text-center text-muted">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        Sistema SIDCOM | Dashboard actualizado el {{ now()->format('d/m/Y H:i:s') }}
                        | Estadísticas activas (emitidos + vencidos) | Anulados mostrados separadamente
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .progress {
        border-radius: 5px;
    }
    .badge {
        font-size: 0.85em;
    }
    .text-danger {
        color: #dc3545 !important;
    }


    .chart-card {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }

    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .chart-header {
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding: 15px 20px;
    }

    .chart-body {
        padding: 20px;
    }

    /* Colores personalizados para gráficas */
    :root {
        --color-emitido: #28a745;
        --color-vencido: #ffc107;
        --color-anulado: #dc3545;
        --color-interno: #007bff;
        --color-externo: #17a2b8;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. GRÁFICA SEMANAL - Doughnut
    const ctxSemanal = document.getElementById('graficaSemanal').getContext('2d');
    new Chart(ctxSemanal, {
        type: 'doughnut',
        data: {
            labels: ['Emitidos', 'Vencidos', 'Anulados'],
            datasets: [{
                data: [
                    {{ $comercioInternoSemana['emitidos'] + $comercioExternoSemana['emitidos'] }},
                    {{ $comercioInternoSemana['vencidos'] + $comercioExternoSemana['vencidos'] }},
                    {{ $comercioInternoSemana['anulados'] + $comercioExternoSemana['anulados'] }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Total Semanal: {{ $totalGeneralSemana + $totalAnuladosSemana }} formularios',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },
            cutout: '60%'
        }
    });

    // 2. GRÁFICA ANUAL - Barras agrupadas
    const ctxAnual = document.getElementById('graficaAnual').getContext('2d');
    new Chart(ctxAnual, {
        type: 'bar',
        data: {
            labels: ['Interno (I)', 'Externo (E)'],
            datasets: [
                {
                    label: 'Emitidos',
                    data: [
                        {{ $comercioInternoAnual['emitidos'] }},
                        {{ $comercioExternoAnual['emitidos'] }}
                    ],
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Vencidos',
                    data: [
                        {{ $comercioInternoAnual['vencidos'] }},
                        {{ $comercioExternoAnual['vencidos'] }}
                    ],
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Anulados',
                    data: [
                        {{ $comercioInternoAnual['anulados'] }},
                        {{ $comercioExternoAnual['anulados'] }}
                    ],
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Total Anual Activos: {{ $totalGeneralAnual }} | Anulados: {{ $totalAnuladosAnual }}',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 50
                    },
                    title: {
                        display: true,
                        text: 'Cantidad de Formularios'
                    }
                }
            }
        }
    });

    // 3. GRÁFICA MENSUAL - Líneas
    @if(!empty($ultimosMeses))
    const mesesLabels = {!! json_encode(array_column($ultimosMeses, 'label')) !!};
    const datosInternoActivos = {!! json_encode(array_column($ultimosMeses, 'interno_activos')) !!};
    const datosExternoActivos = {!! json_encode(array_column($ultimosMeses, 'externo_activos')) !!};
    const datosAnulados = {!! json_encode(array_column($ultimosMeses, 'total_anulados')) !!};

    const ctxMensual = document.getElementById('graficaMensual').getContext('2d');
    new Chart(ctxMensual, {
        type: 'line',
        data: {
            labels: mesesLabels,
            datasets: [
                {
                    label: 'Interno Activos',
                    data: datosInternoActivos,
                    borderColor: 'rgba(0, 123, 255, 0.8)',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Externo Activos',
                    data: datosExternoActivos,
                    borderColor: 'rgba(23, 162, 184, 0.8)',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Anulados',
                    data: datosAnulados,
                    borderColor: 'rgba(220, 53, 69, 0.8)',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false,
                    borderDash: [5, 5]
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 50
                    },
                    title: {
                        display: true,
                        text: 'Cantidad de Formularios'
                    }
                }
            }
        }
    });
    @endif

    // 4. GRÁFICA COMPARATIVA - Radar
    const ctxComparativa = document.getElementById('graficaComparativa').getContext('2d');
    new Chart(ctxComparativa, {
        type: 'radar',
        data: {
            labels: ['Interno Emitidos', 'Interno Vencidos', 'Interno Anulados', 'Externo Emitidos', 'Externo Vencidos', 'Externo Anulados'],
            datasets: [
                {
                    label: 'Semana',
                    data: [
                        {{ $comercioInternoSemana['emitidos'] }},
                        {{ $comercioInternoSemana['vencidos'] }},
                        {{ $comercioInternoSemana['anulados'] }},
                        {{ $comercioExternoSemana['emitidos'] }},
                        {{ $comercioExternoSemana['vencidos'] }},
                        {{ $comercioExternoSemana['anulados'] }}
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                },
                {
                    label: 'Año',
                    data: [
                        {{ $comercioInternoAnual['emitidos'] }},
                        {{ $comercioInternoAnual['vencidos'] }},
                        {{ $comercioInternoAnual['anulados'] }},
                        {{ $comercioExternoAnual['emitidos'] }},
                        {{ $comercioExternoAnual['vencidos'] }},
                        {{ $comercioExternoAnual['anulados'] }}
                    ],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(255, 99, 132, 1)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                r: {
                    angleLines: {
                        display: true
                    },
                    suggestedMin: 0,
                    ticks: {
                        stepSize: 50
                    }
                }
            }
        }
    });

    // 5. Gráfica adicional: Pie Chart comparativo Interno vs Externo
    // Puedes agregar este div donde quieras mostrar esta gráfica adicional
    const graficaAdicionalHTML = `
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card chart-card">
            <div class="chart-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Distribución por Tipo
                </h6>
            </div>
            <div class="chart-body">
                <canvas id="graficaTipoComercio" height="200"></canvas>
            </div>
        </div>
    </div>`;

    // Inserta la gráfica adicional (opcional)
    // document.querySelector('.row:has(#graficaSemanal)').insertAdjacentHTML('beforeend', graficaAdicionalHTML);

    // Crea la gráfica de distribución por tipo
    // const ctxTipo = document.getElementById('graficaTipoComercio')?.getContext('2d');
    // if (ctxTipo) {
    //     new Chart(ctxTipo, {
    //         type: 'pie',
    //         data: {
    //             labels: ['Interno (I)', 'Externo (E)'],
    //             datasets: [{
    //                 data: [
    //                     {{ $comercioInternoAnual['total'] }},
    //                     {{ $comercioExternoAnual['total'] }}
    //                 ],
    //                 backgroundColor: [
    //                     'rgba(0, 123, 255, 0.8)',
    //                     'rgba(23, 162, 184, 0.8)'
    //                 ],
    //                 borderColor: [
    //                     'rgba(0, 123, 255, 1)',
    //                     'rgba(23, 162, 184, 1)'
    //                 ],
    //                 borderWidth: 2
    //             }]
    //         },
    //         options: {
    //             responsive: true,
    //             plugins: {
    //                 legend: {
    //                     position: 'bottom'
    //                 }
    //             }
    //         }
    //     });
    // }

    // Actualizar automáticamente cada 5 minutos
    setTimeout(function() {
        window.location.reload();
    }, 300000); // 5 minutos
});
</script>
@endpush
