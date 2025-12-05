@extends('layouts.app')

@section('title', 'Estadísticas - Consultas Personalizadas')

@section('content')
<div class="container-fluid">
    <!-- Cabecera principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%)">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-bar-chart-line me-2"></i>Estadísticas - Consultas Personalizadas
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Formulario de Consulta -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-filter-circle me-2"></i>Formulario de Consulta
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('estadisticas.consulta') }}" method="POST" id="formConsulta">
                        @csrf

                        <div class="row g-3">
                            <!-- Fechas -->
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">
                                    <i class="bi bi-calendar-date me-1"></i>Fecha Inicio *
                                </label>
                                <input type="date"
                                       class="form-control"
                                       id="fecha_inicio"
                                       name="fecha_inicio"
                                       value="{{ now()->subWeek()->format('Y-m-d') }}"
                                       required>
                                <div class="form-text text-muted">Fecha inicial del período</div>
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">
                                    <i class="bi bi-calendar-check me-1"></i>Fecha Fin *
                                </label>
                                <input type="date"
                                       class="form-control"
                                       id="fecha_fin"
                                       name="fecha_fin"
                                       value="{{ now()->format('Y-m-d') }}"
                                       required>
                                <div class="form-text text-muted">Incluye todo el día seleccionado</div>
                            </div>

                            <!-- Tipo de Comercio -->
                            <div class="col-md-6">
                                <label for="tipo_form_comercio" class="form-label">
                                    <i class="bi bi-building me-1"></i>Tipo de Comercio *
                                </label>
                                <select class="form-select" name="tipo_form_comercio" id="tipo_form_comercio" required>
                                    @foreach($tiposNombres as $valor => $nombre)
                                        <option value="{{ $valor }}">{{ $nombre }} ({{ $valor }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Estados -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-list-check me-1"></i>Estados a Incluir *
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="estados[]" value="1" id="estado1" checked>
                                            <label class="form-check-label" for="estado1">
                                                {{ $estadosNombres['1'] }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="estados[]" value="2" id="estado2" checked>
                                            <label class="form-check-label" for="estado2">
                                                {{ $estadosNombres['2'] }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="estados[]" value="3" id="estado3">
                                            <label class="form-check-label" for="estado3">
                                                {{ $estadosNombres['3'] }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="estados[]" value="0" id="estado0">
                                            <label class="form-check-label" for="estado0">
                                                {{ $estadosNombres['0'] }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text text-muted mt-2">
                                    <i class="bi bi-info-circle me-1"></i>Estados 1 y 2 seleccionados por defecto
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-dark" id="btnSemana">
                                        <i class="bi bi-calendar-week me-1"></i>Última Semana
                                    </button>
                                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%); color: white; border: none;">
                                        <i class="bi bi-search me-1"></i>Ejecutar Consulta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Información -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Información de Estados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th class="text-end">Uso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estadosNombres as $codigo => $nombre)
                                <tr>
                                    <td>
                                        <span class="badge" style="background: {{ $codigo == '1' || $codigo == '2' ? '#8B0000' : '#6c757d' }};">
                                            {{ $codigo }}
                                        </span>
                                    </td>
                                    <td>{{ $nombre }}</td>
                                    <td class="text-end">
                                        @if(in_array($codigo, ['1', '2']))
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                            Recomendado
                                        </span>
                                        @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                            Opcional
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="alert border mt-3" style="border-color: rgba(139, 0, 0, 0.2); background-color: rgba(139, 0, 0, 0.05);">
                        <div class="d-flex">
                            <i class="bi bi-lightbulb me-2" style="color: #8B0000;"></i>
                            <div>
                                <small class="text-muted">
                                    <strong>Sugerencia:</strong> Para reportes estándar, incluya solo los estados 1 y 2.
                                    El estado 0 (Anulado) generalmente se excluye de las estadísticas activas.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor de Resultados -->
    <div id="resultadosContainer" class="mt-4"></div>
</div>
@endsection

@push('styles')
<style>
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    .form-check-input:checked {
        background-color: #8B0000;
        border-color: #8B0000;
    }

    .form-check-input:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    .btn-outline-dark {
        border-color: #8B0000;
        color: #8B0000;
        transition: all 0.3s ease;
    }

    .btn-outline-dark:hover {
        background-color: #8B0000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(139, 0, 0, 0.2);
    }

    .table th {
        font-weight: 600;
        color: #8B0000;
        background-color: rgba(139, 0, 0, 0.05);
        border-bottom: 2px solid rgba(139, 0, 0, 0.1);
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        padding: 0.4em 0.8em;
        font-weight: 500;
    }
</style>
@endpush

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
            },
            error: function() {
                $('#btnSemana').prop('disabled', false).html('<i class="bi bi-calendar-week me-1"></i>Última Semana');
            }
        });
    });

    // Envío del formulario con AJAX
    $('#formConsulta').submit(function(e) {
        e.preventDefault();

        // Validar que al menos un estado esté seleccionado
        if($('input[name="estados[]"]:checked').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Selección requerida',
                text: 'Debe seleccionar al menos un estado.',
                confirmButtonColor: '#8B0000',
            });
            return false;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function() {
                $('#resultadosContainer').html(`
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <div class="spinner-border" style="color: #8B0000; width: 3rem; height: 3rem;"></div>
                                <p class="mt-3 text-muted">Procesando consulta...</p>
                            </div>
                        </div>
                    </div>
                `);
            },
            success: function(data) {
                $('#resultadosContainer').html(data);
            },
            error: function(xhr) {
                let errorMsg = 'Error al procesar la consulta';
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                $('#resultadosContainer').html(`
                    <div class="alert alert-danger border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <h5 class="mb-1">Error en la consulta</h5>
                                <p class="mb-0">${errorMsg}</p>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    });

    function mostrarResultadoSemanal(data) {
        const html = `
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-check-circle me-2"></i>Consulta Rápida - Última Semana
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-start border-5 border-start-primary h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                            <i class="bi bi-house-door fs-3 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-primary">Comercio Interno</h6>
                                            <p class="text-muted mb-0">Estados 1 y 2</p>
                                        </div>
                                    </div>
                                    <h2 class="display-5 fw-bold text-center text-primary">${data.internos}</h2>
                                    <p class="text-center text-muted mb-0">Formularios activos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-start border-5 border-start-info h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                            <i class="bi bi-globe fs-3 text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-info">Comercio Externo</h6>
                                            <p class="text-muted mb-0">Estados 1 y 2</p>
                                        </div>
                                    </div>
                                    <h2 class="display-5 fw-bold text-center text-info">${data.externos}</h2>
                                    <p class="text-center text-muted mb-0">Formularios activos</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert border-0 shadow-sm mt-4" style="border-left: 4px solid #8B0000; background-color: rgba(139, 0, 0, 0.05);">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-check fs-3" style="color: #8B0000;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1" style="color: #8B0000;">Período de consulta</h6>
                                <p class="mb-0"><strong>${data.periodo}</strong></p>
                                <p class="mb-0 text-muted">Total general: <strong>${data.total}</strong> formularios activos (Estados 1 y 2)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#resultadosContainer').html(html);
    }

    // Establecer fecha máxima en fecha fin como hoy
    const today = new Date().toISOString().split('T')[0];
    $('#fecha_inicio').attr('max', today);
    $('#fecha_fin').attr('max', today);

    // Validar que fecha fin no sea menor a fecha inicio
    $('#fecha_inicio, #fecha_fin').change(function() {
        const inicio = $('#fecha_inicio').val();
        const fin = $('#fecha_fin').val();

        if (inicio && fin && new Date(inicio) > new Date(fin)) {
            Swal.fire({
                icon: 'error',
                title: 'Error en fechas',
                text: 'La fecha de inicio no puede ser mayor a la fecha de fin',
                confirmButtonColor: '#8B0000',
            });
            $('#fecha_fin').val(inicio);
        }
    });
});
</script>

@if(session('sweet_alert'))
<script>
    Swal.fire({
        icon: '{{ session("sweet_alert.type") }}',
        title: '{{ session("sweet_alert.title") }}',
        text: '{{ session("sweet_alert.text") }}',
        confirmButtonColor: '#8B0000',
    });
</script>
@endif
@endpush
