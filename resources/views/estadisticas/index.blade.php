@extends('layouts.app')

@section('title', 'Estadísticas de Formularios')

@section('content')
<div class="container-fluid">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line me-2"></i>Estadisticas de Formularios 101
                        <span class="float-end">
                            <small>Período: {{ $fechaInicio }} - {{ $fechaFin }}</small>
                        </span>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas principales de la última semana -->
    <div class="row mb-4">
        <!-- COMERCIO INTERNO -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card border-primary shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-house-door me-2"></i>Comercio Interno (I)
                    </h5>
                    <span class="badge bg-light text-primary fs-6">{{ $comercioInterno['total'] }}</span>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body py-3">
                                    <h6 class="text-success mb-2">
                                        <i class="bi bi-check-circle me-1"></i>Emitidos
                                    </h6>
                                    <h3 class="text-success mb-0">{{ $comercioInterno['emitidos'] }}</h3>
                                    <small class="text-muted">Estado 1</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body py-3">
                                    <h6 class="text-warning mb-2">
                                        <i class="bi bi-clock-history me-1"></i>Vencidos
                                    </h6>
                                    <h3 class="text-warning mb-0">{{ $comercioInterno['vencidos'] }}</h3>
                                    <small class="text-muted">Estado 2</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body py-3">
                                    <h6 class="text-primary mb-2">
                                        <i class="bi bi-calendar-check me-1"></i>Hoy
                                    </h6>
                                    <h3 class="text-primary mb-0">{{ $hoyInterno }}</h3>
                                    <small class="text-muted">{{ $hoy }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 20px;">
                            @php
                                $totalInterno = $comercioInterno['total'];
                                $porcentajeEmitidos = $totalInterno > 0 ? round(($comercioInterno['emitidos'] / $totalInterno) * 100) : 0;
                                $porcentajeVencidos = $totalInterno > 0 ? round(($comercioInterno['vencidos'] / $totalInterno) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success"
                                 style="width: {{ $porcentajeEmitidos }}%"
                                 title="Emitidos: {{ $porcentajeEmitidos }}%">
                                {{ $comercioInterno['emitidos'] }}
                            </div>
                            <div class="progress-bar bg-warning"
                                 style="width: {{ $porcentajeVencidos }}%"
                                 title="Vencidos: {{ $porcentajeVencidos }}%">
                                {{ $comercioInterno['vencidos'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COMERCIO EXTERNO -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card border-info shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-globe me-2"></i>Comercio Externo (E)
                    </h5>
                    <span class="badge bg-light text-info fs-6">{{ $comercioExterno['total'] }}</span>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body py-3">
                                    <h6 class="text-success mb-2">
                                        <i class="bi bi-check-circle me-1"></i>Emitidos
                                    </h6>
                                    <h3 class="text-success mb-0">{{ $comercioExterno['emitidos'] }}</h3>
                                    <small class="text-muted">Estado 1</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body py-3">
                                    <h6 class="text-warning mb-2">
                                        <i class="bi bi-clock-history me-1"></i>Vencidos
                                    </h6>
                                    <h3 class="text-warning mb-0">{{ $comercioExterno['vencidos'] }}</h3>
                                    <small class="text-muted">Estado 2</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-info">
                                <div class="card-body py-3">
                                    <h6 class="text-info mb-2">
                                        <i class="bi bi-calendar-check me-1"></i>Hoy
                                    </h6>
                                    <h3 class="text-info mb-0">{{ $hoyExterno }}</h3>
                                    <small class="text-muted">{{ $hoy }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 20px;">
                            @php
                                $totalExterno = $comercioExterno['total'];
                                $porcentajeEmitidosExt = $totalExterno > 0 ? round(($comercioExterno['emitidos'] / $totalExterno) * 100) : 0;
                                $porcentajeVencidosExt = $totalExterno > 0 ? round(($comercioExterno['vencidos'] / $totalExterno) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success"
                                 style="width: {{ $porcentajeEmitidosExt }}%"
                                 title="Emitidos: {{ $porcentajeEmitidosExt }}%">
                                {{ $comercioExterno['emitidos'] }}
                            </div>
                            <div class="progress-bar bg-warning"
                                 style="width: {{ $porcentajeVencidosExt }}%"
                                 title="Vencidos: {{ $porcentajeVencidosExt }}%">
                                {{ $comercioExterno['vencidos'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total General -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-dark">
                <div class="card-body text-center py-4">
                    <h3 class="text-muted mb-3">Resumen General de la Semana</h3>
                    <div class="display-1 text-dark mb-2">{{ $totalGeneral }}</div>
                    <p class="lead mb-0">
                        Formularios activos (Estados 1 y 2)
                        <br>
                        <small class="text-muted">{{ $fechaInicio }} - {{ $fechaFin }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de consulta personalizada -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-filter-circle me-2"></i>Consulta Personalizada
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('estadisticas.consulta') }}" method="POST" id="formConsulta">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date"
                                       class="form-control"
                                       id="fecha_inicio"
                                       name="fecha_inicio"
                                       value="{{ now()->subWeek()->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date"
                                       class="form-control"
                                       id="fecha_fin"
                                       name="fecha_fin"
                                       value="{{ now()->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label for="tipo_form_comercio" class="form-label">Tipo de Comercio</label>
                                <select class="form-select" name="tipo_form_comercio" id="tipo_form_comercio" required>
                                    <option value="I">Interno (I)</option>
                                    <option value="E">Externo (E)</option>
                                    <option value="T">Todos los Tipos</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Opciones Adicionales</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="incluir_estado3" value="1" id="incluir_estado3">
                                    <label class="form-check-label" for="incluir_estado3">
                                        Incluir Estado 3 (adicionalmente)
                                    </label>
                                    <div class="form-text text-muted">Por defecto solo se incluyen Estados 1 y 2</div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-info me-2" id="btnSemana">
                                        <i class="bi bi-calendar-week me-1"></i>Última Semana
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i>Consultar Personalizado
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados (se llenará dinámicamente) -->
    <div id="resultadosContainer" class="mt-4"></div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Consulta rápida de la semana
    $('#btnSemana').click(function() {
        $.ajax({
            url: '{{ route("estadisticas.semana") }}',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#btnSemana').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Consultando...');
            },
            success: function(response) {
                if(response.success) {
                    mostrarResultadoSemanal(response);
                }
            },
            complete: function() {
                $('#btnSemana').prop('disabled', false).html('<i class="bi bi-calendar-week me-1"></i>Última Semana');
            }
        });
    });

    // Envío del formulario con AJAX
    $('#formConsulta').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function() {
                $('#resultadosContainer').html('<div class="text-center"><div class="spinner-border text-primary"></div></div>');
            },
            success: function(data) {
                $('#resultadosContainer').html(data);
            },
            error: function() {
                $('#resultadosContainer').html('<div class="alert alert-danger">Error al procesar la consulta</div>');
            }
        });
    });

    function mostrarResultadoSemanal(data) {
        const html = `
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle me-2"></i>Resultado Consulta Semanal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Comercio Interno</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="display-3 text-primary">${data.internos}</h2>
                                    <p class="mb-0">Formularios activos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Comercio Externo</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="display-3 text-info">${data.externos}</h2>
                                    <p class="mb-0">Formularios activos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-success">
                        <h5><i class="bi bi-calendar-check me-2"></i>${data.periodo}</h5>
                        <p class="mb-0">Total general: <strong>${data.total}</strong> formularios activos</p>
                    </div>
                </div>
            </div>
        `;

        $('#resultadosContainer').html(html);
    }
});
</script>
@endpush
