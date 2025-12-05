@extends('layouts.app')

@section('title', 'Estadísticas - Consultas Personalizadas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line me-2"></i>Estadísticas - Consultas Personalizadas
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
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
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio *</label>
                                <input type="date"
                                       class="form-control"
                                       id="fecha_inicio"
                                       name="fecha_inicio"
                                       value="{{ now()->subWeek()->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha Fin *</label>
                                <input type="date"
                                       class="form-control"
                                       id="fecha_fin"
                                       name="fecha_fin"
                                       value="{{ now()->format('Y-m-d') }}"
                                       required>
                                <div class="form-text">Incluye todo el día seleccionado</div>
                            </div>

                            <div class="col-md-6">
                                <label for="tipo_form_comercio" class="form-label">Tipo de Comercio *</label>
                                <select class="form-select" name="tipo_form_comercio" id="tipo_form_comercio" required>
                                    @foreach($tiposNombres as $valor => $nombre)
                                        <option value="{{ $valor }}">{{ $nombre }} ({{ $valor }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Estados a Incluir *</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estados[]" value="1" id="estado1" checked>
                                    <label class="form-check-label" for="estado1">
                                        {{ $estadosNombres['1'] }} (Estado 1)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estados[]" value="2" id="estado2" checked>
                                    <label class="form-check-label" for="estado2">
                                        {{ $estadosNombres['2'] }} (Estado 2)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estados[]" value="3" id="estado3">
                                    <label class="form-check-label" for="estado3">
                                        {{ $estadosNombres['3'] }} (Estado 3) - Opcional
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estados[]" value="0" id="estado0">
                                    <label class="form-check-label" for="estado0">
                                        {{ $estadosNombres['0'] }} (Estado 0) - Opcional
                                    </label>
                                </div>
                                <div class="form-text text-muted">Los estados 1 y 2 están seleccionados por defecto</div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-info me-2" id="btnSemana">
                                        <i class="bi bi-calendar-week me-1"></i>Última Semana
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i>Ejecutar Consulta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Información de Estados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($estadosNombres as $codigo => $nombre)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-secondary me-2">{{ $codigo }}</span>
                                {{ $nombre }}
                            </div>
                            @if(in_array($codigo, ['1', '2']))
                            <span class="badge bg-success">Recomendado</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="bi bi-lightbulb me-1"></i>
                            <strong>Sugerencia:</strong> Para reportes estándar, incluya solo los estados 1 y 2.
                            El estado 0 (Anulado) generalmente se excluye de las estadísticas activas.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        // Validar que al menos un estado esté seleccionado
        if($('input[name="estados[]"]:checked').length === 0) {
            alert('Debe seleccionar al menos un estado.');
            return false;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function() {
                $('#resultadosContainer').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3">Procesando consulta...</p>
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
                    <div class="alert alert-danger">
                        <h5><i class="bi bi-exclamation-triangle me-2"></i>Error</h5>
                        <p class="mb-0">${errorMsg}</p>
                    </div>
                `);
            }
        });
    });

    function mostrarResultadoSemanal(data) {
        const html = `
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle me-2"></i>Consulta Rápida - Última Semana
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bi bi-house-door me-2"></i>Comercio Interno</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="display-3 text-primary">${data.internos}</h2>
                                    <p class="mb-0">Formularios activos</p>
                                    <small class="text-muted">Estados 1 y 2</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="bi bi-globe me-2"></i>Comercio Externo</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="display-3 text-info">${data.externos}</h2>
                                    <p class="mb-0">Formularios activos</p>
                                    <small class="text-muted">Estados 1 y 2</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-success">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-check fs-3 me-3"></i>
                            <div>
                                <h5 class="mb-1">Período: ${data.periodo}</h5>
                                <p class="mb-0">Total general: <strong>${data.total}</strong> formularios activos (Estados 1 y 2)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#resultadosContainer').html(html);
    }
});
</script>
@endpush
