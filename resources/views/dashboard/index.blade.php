@extends('layouts.app')

@section('title', 'Dashboard - SIDCOM')

@section('content')
<div class="container-fluid">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-speedometer2 me-2"></i>FORMULARIOS 101
                        <span class="float-end fs-6">
                            <small class="text-white-50">Año {{ $periodoAnual['ano_actual'] }} | Hoy: {{ $periodoSemana['hoy'] }}</small>
                        </span>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicadores clave -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-start border-4 border-start-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Activos - Semana</h6>
                            <h2 class="mb-0" style="color: #8B0000;">{{ $totalGeneralSemana }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                            <i class="bi bi-calendar-week fs-4" style="color: #8B0000;"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-1 small">
                        <i class="bi bi-calendar-date me-1"></i>{{ $periodoSemana['inicio'] }} - {{ $periodoSemana['fin'] }}
                    </p>
                    @if($totalAnuladosSemana > 0)
                    <div class="mt-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                            <i class="bi bi-x-circle me-1"></i>
                            {{ $totalAnuladosSemana }} anulados ({{ $porcentajeAnuladosSemana }}%)
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-start border-4 border-start-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Activos - Anual</h6>
                            <h2 class="mb-0" style="color: #8B0000;">{{ $totalGeneralAnual }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-2 rounded">
                            <i class="bi bi-calendar-range fs-4" style="color: #8B0000;"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-1 small">
                        <i class="bi bi-calendar4 me-1"></i>{{ $periodoAnual['inicio'] }} - {{ $periodoAnual['fin'] }}
                    </p>
                    @if($totalAnuladosAnual > 0)
                    <div class="mt-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                            <i class="bi bi-x-circle me-1"></i>
                            {{ $totalAnuladosAnual }} anulados ({{ $porcentajeAnuladosAnual }}%)
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-start border-4 border-start-info shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Promedio Mensual</h6>
                            <h2 class="mb-0" style="color: #8B0000;">{{ $promedioMensualGeneral }}</h2>
                        </div>
                        <div class="bg-info bg-opacity-10 p-2 rounded">
                            <i class="bi bi-calculator fs-4" style="color: #8B0000;"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-1 small">
                        <i class="bi bi-clock-history me-1"></i>{{ $periodoAnual['meses_transcurridos'] }} meses transcurridos
                    </p>
                    <div class="mt-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                            <i class="bi bi-graph-up me-1"></i>
                            {{ round($totalGeneralAnual / max($periodoAnual['meses_transcurridos'], 1)) }} por mes
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-start border-4 border-start-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Tasa de Anulación</h6>
                            <h2 class="mb-0" style="color: #8B0000;">{{ $porcentajeAnuladosAnual }}%</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-2 rounded">
                            <i class="bi bi-exclamation-triangle fs-4" style="color: #8B0000;"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-1 small">
                        <i class="bi bi-percent me-1"></i>Del total histórico
                    </p>
                    <div class="mt-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                            <i class="bi bi-arrow-down-up me-1"></i>
                            Semana: {{ $porcentajeAnuladosSemana }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Semanales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="border-bottom: 2px solid rgba(139, 0, 0, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #8B0000;">
                            <i class="bi bi-calendar-week me-2"></i>Estadísticas de la Última Semana
                        </h5>
                        <span class="badge" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                            {{ $periodoSemana['inicio'] }} - {{ $periodoSemana['fin'] }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Comercio Interno Semanal -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center"
                                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                                    <h6 class="mb-0 text-white">
                                        <i class="bi bi-house-door me-2"></i>Comercio Interno (I) - Semana
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-dark fs-6">{{ $comercioInternoSemana['total'] }}</span>
                                        @if($comercioInternoSemana['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioInternoSemana['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-success mb-1">
                                                    <i class="bi bi-check-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoSemana['emitidos'] }}</h5>
                                                <small class="text-muted">Emitidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-warning mb-1">
                                                    <i class="bi bi-clock-history fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoSemana['vencidos'] }}</h5>
                                                <small class="text-muted">Vencidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-danger mb-1">
                                                    <i class="bi bi-x-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoSemana['anulados'] }}</h5>
                                                <small class="text-muted">Anulados</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-primary mb-1">
                                                    <i class="bi bi-calendar-check fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoSemana['hoy'] }}</h5>
                                                <small class="text-muted">Hoy Activos</small>
                                            </div>
                                        </div>
                                    </div>
                                    @if($comercioInternoSemana['total'] > 0)
                                    <div class="mt-3">
                                        <small class="text-muted mb-2 d-block">Distribución:</small>
                                        @php
                                            $totalConAnulados = $comercioInternoSemana['total'] + $comercioInternoSemana['anulados'];
                                            $porcentajeEmitidos = $totalConAnulados > 0 ? round(($comercioInternoSemana['emitidos'] / $totalConAnulados) * 100) : 0;
                                            $porcentajeVencidos = $totalConAnulados > 0 ? round(($comercioInternoSemana['vencidos'] / $totalConAnulados) * 100) : 0;
                                            $porcentajeAnulados = $totalConAnulados > 0 ? round(($comercioInternoSemana['anulados'] / $totalConAnulados) * 100) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $porcentajeEmitidos }}%"></div>
                                                <div class="progress-bar bg-warning" style="width: {{ $porcentajeVencidos }}%"></div>
                                                <div class="progress-bar bg-danger" style="width: {{ $porcentajeAnulados }}%"></div>
                                            </div>
                                            <small class="ms-2 text-muted">{{ $totalConAnulados }} total</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Comercio Externo Semanal -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center"
                                     style="background: linear-gradient(135deg, #006494 0%, #003554 100%);">
                                    <h6 class="mb-0 text-white">
                                        <i class="bi bi-globe me-2"></i>Comercio Externo (E) - Semana
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-dark fs-6">{{ $comercioExternoSemana['total'] }}</span>
                                        @if($comercioExternoSemana['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioExternoSemana['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-success mb-1">
                                                    <i class="bi bi-check-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoSemana['emitidos'] }}</h5>
                                                <small class="text-muted">Emitidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-warning mb-1">
                                                    <i class="bi bi-clock-history fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoSemana['vencidos'] }}</h5>
                                                <small class="text-muted">Vencidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-danger mb-1">
                                                    <i class="bi bi-x-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoSemana['anulados'] }}</h5>
                                                <small class="text-muted">Anulados</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-info mb-1">
                                                    <i class="bi bi-calendar-check fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoSemana['hoy'] }}</h5>
                                                <small class="text-muted">Hoy Activos</small>
                                            </div>
                                        </div>
                                    </div>
                                    @if($comercioExternoSemana['total'] > 0)
                                    <div class="mt-3">
                                        <small class="text-muted mb-2 d-block">Distribución:</small>
                                        @php
                                            $totalConAnuladosExt = $comercioExternoSemana['total'] + $comercioExternoSemana['anulados'];
                                            $porcentajeEmitidosExt = $totalConAnuladosExt > 0 ? round(($comercioExternoSemana['emitidos'] / $totalConAnuladosExt) * 100) : 0;
                                            $porcentajeVencidosExt = $totalConAnuladosExt > 0 ? round(($comercioExternoSemana['vencidos'] / $totalConAnuladosExt) * 100) : 0;
                                            $porcentajeAnuladosExt = $totalConAnuladosExt > 0 ? round(($comercioExternoSemana['anulados'] / $totalConAnuladosExt) * 100) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $porcentajeEmitidosExt }}%"></div>
                                                <div class="progress-bar bg-warning" style="width: {{ $porcentajeVencidosExt }}%"></div>
                                                <div class="progress-bar bg-danger" style="width: {{ $porcentajeAnuladosExt }}%"></div>
                                            </div>
                                            <small class="ms-2 text-muted">{{ $totalConAnuladosExt }} total</small>
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

    <!-- Estadísticas Anuales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="border-bottom: 2px solid rgba(139, 0, 0, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #8B0000;">
                            <i class="bi bi-calendar-range me-2"></i>Estadísticas del Año {{ $periodoAnual['ano_actual'] }}
                        </h5>
                        <span class="badge" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                            {{ $periodoAnual['inicio'] }} - {{ $periodoAnual['fin'] }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Comercio Interno Anual -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center"
                                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                                    <h6 class="mb-0 text-white">
                                        <i class="bi bi-house-door me-2"></i>Comercio Interno (I) - Anual
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-dark fs-6">{{ $comercioInternoAnual['total'] }}</span>
                                        @if($comercioInternoAnual['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioInternoAnual['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-success mb-1">
                                                    <i class="bi bi-check-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoAnual['emitidos'] }}</h5>
                                                <small class="text-muted">Emitidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-warning mb-1">
                                                    <i class="bi bi-clock-history fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoAnual['vencidos'] }}</h5>
                                                <small class="text-muted">Vencidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-danger mb-1">
                                                    <i class="bi bi-x-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoAnual['anulados'] }}</h5>
                                                <small class="text-muted">Anulados</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-info mb-1">
                                                    <i class="bi bi-graph-up fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioInternoAnual['promedio_mensual'] }}</h5>
                                                <small class="text-muted">Prom. Mensual</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comercio Externo Anual -->
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center"
                                     style="background: linear-gradient(135deg, #006494 0%, #003554 100%);">
                                    <h6 class="mb-0 text-white">
                                        <i class="bi bi-globe me-2"></i>Comercio Externo (E) - Anual
                                    </h6>
                                    <div>
                                        <span class="badge bg-light text-dark fs-6">{{ $comercioExternoAnual['total'] }}</span>
                                        @if($comercioExternoAnual['anulados'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $comercioExternoAnual['anulados'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-success mb-1">
                                                    <i class="bi bi-check-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoAnual['emitidos'] }}</h5>
                                                <small class="text-muted">Emitidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-warning mb-1">
                                                    <i class="bi bi-clock-history fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoAnual['vencidos'] }}</h5>
                                                <small class="text-muted">Vencidos</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-danger mb-1">
                                                    <i class="bi bi-x-circle fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoAnual['anulados'] }}</h5>
                                                <small class="text-muted">Anulados</small>
                                            </div>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <div class="text-center p-2 border rounded">
                                                <div class="text-info mb-1">
                                                    <i class="bi bi-graph-up fs-4"></i>
                                                </div>
                                                <h5 class="mb-0">{{ $comercioExternoAnual['promedio_mensual'] }}</h5>
                                                <small class="text-muted">Prom. Mensual</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    @if(!empty($ultimosMeses))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="border-bottom: 2px solid rgba(139, 0, 0, 0.1);">
                    <h5 class="mb-0" style="color: #8B0000;">
                        <i class="bi bi-graph-up me-2"></i>Tendencias - Últimos 6 Meses
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr style="background-color: rgba(139, 0, 0, 0.05);">
                                    <th class="border-0">Mes</th>
                                    <th class="border-0">Interno (I)</th>
                                    <th class="border-0">Externo (E)</th>
                                    <th class="border-0">Total Activos</th>
                                    <th class="border-0">Anulados</th>
                                    <th class="border-0">Tasa Anulación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosMeses as $mes)
                                @php
                                    $totalMes = $mes['total_activos'] + $mes['total_anulados'];
                                    $tasaAnulacion = $totalMes > 0 ? round(($mes['total_anulados'] / $totalMes) * 100, 1) : 0;
                                @endphp
                                <tr class="border-top">
                                    <td><strong>{{ $mes['label'] }}</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: rgba(139, 0, 0, 0.1); color: #8B0000;">
                                            {{ $mes['interno_activos'] }}
                                        </span>
                                        @if($mes['interno_anulados'] > 0)
                                        <small class="text-danger ms-1">
                                            (+{{ $mes['interno_anulados'] }})
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: rgba(0, 100, 148, 0.1); color: #006494;">
                                            {{ $mes['externo_activos'] }}
                                        </span>
                                        @if($mes['externo_anulados'] > 0)
                                        <small class="text-danger ms-1">
                                            (+{{ $mes['externo_anulados'] }})
                                        </small>
                                        @endif
                                    </td>
                                    <td><strong style="color: #8B0000;">{{ $mes['total_activos'] }}</strong></td>
                                    <td>
                                        @if($mes['total_anulados'] > 0)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                            {{ $mes['total_anulados'] }}
                                        </span>
                                        @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $tasaAnulacion > 10 ? 'danger' : ($tasaAnulacion > 5 ? 'warning' : 'success') }}-subtle
                                                  text-{{ $tasaAnulacion > 10 ? 'danger' : ($tasaAnulacion > 5 ? 'warning' : 'success') }}
                                                  border border-{{ $tasaAnulacion > 10 ? 'danger' : ($tasaAnulacion > 5 ? 'warning' : 'success') }}-subtle">
                                            {{ $tasaAnulacion }}%
                                        </span>
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

</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(139, 0, 0, 0.1) !important;
    }

    .progress {
        border-radius: 4px;
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }

    .border-start {
        border-left-width: 4px !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-info {
        color: #17a2b8 !important;
    }

    .text-primary {
        color: #8B0000 !important;
    }

    .table thead th {
        font-weight: 600;
        color: #8B0000;
        background-color: rgba(139, 0, 0, 0.05);
        border-bottom: 2px solid rgba(139, 0, 0, 0.1);
    }

    .table tbody tr {
        transition: background-color 0.2s;
    }

    .table tbody tr:hover {
        background-color: rgba(139, 0, 0, 0.02);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Efecto de hover en las tarjetas de acciones rápidas
    const actionCards = document.querySelectorAll('.card.text-decoration-none');
    actionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
            this.style.boxShadow = '0 15px 30px rgba(139, 0, 0, 0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '';
        });
    });

    // Actualizar automáticamente cada 5 minutos
    setTimeout(function() {
        location.reload();
    }, 300000);
});
</script>
@endpush
