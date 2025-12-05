@extends('layouts.app')

@section('title', 'Actualizaciones de Operadores')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-list-check me-2"></i>Actualizaciones de Operadores Mineros
                    </h4>
                    <a href="{{ route('actualizacion-operadors.create') }}"
                       class="btn btn-light btn-sm d-flex align-items-center"
                       style="background: linear-gradient(to right, #FFD700, #FFA500);
                              color: #8B0000;
                              border: none;
                              font-weight: 600;
                              padding: 0.5rem 1.25rem;
                              border-radius: 8px;
                              transition: all 0.3s ease;
                              box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        <span>Nueva Actualización</span>
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Operador</label>
                            <input type="text" class="form-control form-control-sm" id="filterOperador"
                                   placeholder="Buscar operador...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Tipo</label>
                            <select class="form-select form-select-sm" id="filterTipo">
                                <option value="">Todos</option>
                                @php
                                    $tiposUnicos = collect();
                                    foreach($actualizaciones as $act) {
                                        if($act->tipo_actualizacion) {
                                            $tiposUnicos->push($act->tipo_actualizacion);
                                        }
                                    }
                                    $tiposUnicos = $tiposUnicos->unique()->sort();
                                @endphp
                                @foreach($tiposUnicos as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Fecha Desde</label>
                            <input type="date" class="form-control form-control-sm" id="filterFechaDesde">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Fecha Hasta</label>
                            <input type="date" class="form-control form-control-sm" id="filterFechaHasta">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Observaciones</label>
                            <input type="text" class="form-control form-control-sm" id="filterObservaciones"
                                   placeholder="Buscar en obs...">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                    id="btnLimpiarFiltros">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>

                    @if($actualizaciones->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-2">No hay actualizaciones registradas</h5>
                        <p class="text-muted small mb-4">Comienza creando tu primera actualización</p>
                        <a href="{{ route('actualizacion-operadors.create') }}"
                           class="btn btn-sm d-flex align-items-center mx-auto"
                           style="background: linear-gradient(to right, #FFD700, #FFA500);
                                  color: #8B0000;
                                  border: none;
                                  font-weight: 600;
                                  padding: 0.5rem 1.25rem;
                                  border-radius: 8px;
                                  transition: all 0.3s ease;
                                  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3); width: fit-content;">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            <span>Crear Actualización</span>
                        </a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover" id="actualizacionesTable">
                            <thead>
                                <tr style="background-color: rgba(139, 0, 0, 0.05);">
                                    <th style="color: #8B0000; width: 60px;">#</th>
                                    <th style="color: #8B0000; min-width: 250px;">Operador</th>
                                    <th style="color: #8B0000; width: 150px;">Tipo</th>
                                    <th style="color: #8B0000; width: 100px;">Fecha</th>
                                    <th style="color: #8B0000; min-width: 300px;">Observaciones</th>
                                    <th style="color: #8B0000; width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($actualizaciones as $actualizacion)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            {{ $actualizacion->id }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($actualizacion->razon_social)
                                        <strong>{{ $actualizacion->razon_social }}</strong>
                                        @if($actualizacion->nit)
                                        <br>
                                        <small class="text-muted">SDMMRE- {{ $actualizacion->operador_minero_id }}</small>
                                        @endif
                                        @else
                                        <span class="text-danger">Operador no encontrado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($actualizacion->tipo_actualizacion)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">
                                            {{ $actualizacion->tipo_actualizacion }}
                                        </span>
                                        @else
                                        <span class="text-muted">Sin tipo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($actualizacion->fecha)
                                        {{ \Carbon\Carbon::parse($actualizacion->fecha)->format('d/m/Y') }}
                                        @else
                                        <span class="text-muted">Sin fecha</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="observaciones-texto"
                                             style="max-height: 60px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                            {{ $actualizacion->observaciones ?? 'Sin observaciones' }}
                                        </div>
                                        @if($actualizacion->observaciones && strlen($actualizacion->observaciones) > 150)
                                        <button type="button"
                                                class="btn btn-link btn-sm p-0 mt-1 ver-mas-btn"
                                                data-texto="{{ $actualizacion->observaciones }}">
                                            Ver más...
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('actualizacion-operadors.show', $actualizacion->id) }}"
                                               class="btn btn-outline-primary"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('actualizacion-operadors.edit', $actualizacion->id) }}"
                                               class="btn btn-outline-warning"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- Botón eliminar -->
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-eliminar"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Eliminar"
                                                    data-id="{{ $actualizacion->id }}"
                                                    data-operador="{{ $actualizacion->razon_social ?? 'N/A' }}"
                                                    data-tipo="{{ $actualizacion->tipo_actualizacion ?? 'Sin tipo' }}"
                                                    data-fecha="{{ $actualizacion->fecha ? \Carbon\Carbon::parse($actualizacion->fecha)->format('d/m/Y') : 'Sin fecha' }}">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            <!-- Formulario hidden para eliminar -->
                                            <form action="{{ route('actualizacion-operadors.destroy', $actualizacion->id) }}"
                                                  method="POST" class="d-inline form-eliminar"
                                                  id="formEliminar{{ $actualizacion->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $actualizaciones->links() }}
                    </div>
                    @endif
                </div>
                @if(!$actualizaciones->isEmpty())
                <div class="card-footer text-muted" style="background-color: rgba(139, 0, 0, 0.03);">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        Total registros: {{ $actualizaciones->total() }} |
                        Mostrando: {{ $actualizaciones->firstItem() ?? 0 }} - {{ $actualizaciones->lastItem() ?? 0 }}
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver observaciones completas -->
<div class="modal fade" id="modalObservacionesCompletas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-chat-left-text me-2"></i>Observaciones Completas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium" style="color: #8B0000;">Operador:</label>
                    <p id="modalOperador" class="mb-2"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium" style="color: #8B0000;">Tipo:</label>
                    <span id="modalTipo" class="badge bg-info-subtle text-info"></span>
                </div>
                <div>
                    <label class="form-label fw-medium" style="color: #8B0000;">Observaciones:</label>
                    <div class="border rounded p-3 bg-light" id="modalObservacionesTexto"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Efecto hover para el botón nuevo */
    .card-header a.btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        background: linear-gradient(to right, #FFED4E, #FFB347) !important;
        color: #8B0000 !important;
    }

    /* Estilos para el botón al enfocar */
    .card-header a.btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    }

    /* Estilo para las observaciones en la tabla */
    .observaciones-texto {
        line-height: 1.4;
        font-size: 0.9rem;
    }

    /* Botón ver más */
    .ver-mas-btn {
        font-size: 0.8rem;
        color: #8B0000;
        text-decoration: none;
    }

    .ver-mas-btn:hover {
        text-decoration: underline;
        color: #6A0C0C;
    }

    /* Tooltip */
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    /* Badge para tipo */
    .badge.bg-info-subtle {
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filtros en tiempo real
    function aplicarFiltros() {
        let operador = $('#filterOperador').val().toLowerCase();
        let tipo = $('#filterTipo').val().toLowerCase();
        let fechaDesde = $('#filterFechaDesde').val();
        let fechaHasta = $('#filterFechaHasta').val();
        let observaciones = $('#filterObservaciones').val().toLowerCase();

        $('#actualizacionesTable tbody tr').each(function() {
            let row = $(this);
            let rowOperador = row.find('td:nth-child(2)').text().toLowerCase();
            let rowTipo = row.find('td:nth-child(3) .badge').text().toLowerCase();
            let rowFecha = row.find('td:nth-child(4)').text().trim();
            let rowObservaciones = row.find('td:nth-child(5) .observaciones-texto').text().toLowerCase();

            let show = true;

            // Filtrar por operador
            if (operador && !rowOperador.includes(operador)) show = false;

            // Filtrar por tipo
            if (tipo && !rowTipo.includes(tipo)) show = false;

            // Filtrar por fecha
            if (fechaDesde || fechaHasta) {
                let fechaText = rowFecha;
                if (fechaText !== 'Sin fecha') {
                    // Convertir fecha de tabla (dd/mm/yyyy) a objeto Date
                    let partes = fechaText.split('/');
                    let fechaRow = new Date(partes[2], partes[1] - 1, partes[0]);

                    if (fechaDesde) {
                        let fechaDesdeObj = new Date(fechaDesde);
                        if (fechaRow < fechaDesdeObj) show = false;
                    }

                    if (fechaHasta) {
                        let fechaHastaObj = new Date(fechaHasta);
                        if (fechaRow > fechaHastaObj) show = false;
                    }
                }
            }

            // Filtrar por observaciones
            if (observaciones && !rowObservaciones.includes(observaciones)) show = false;

            show ? row.show() : row.hide();
        });
    }

    // Eventos para filtros
    $('#filterOperador, #filterTipo, #filterFechaDesde, #filterFechaHasta, #filterObservaciones').on('change keyup', function() {
        aplicarFiltros();
    });

    // Limpiar filtros
    $('#btnLimpiarFiltros').click(function() {
        $('#filterOperador').val('');
        $('#filterTipo').val('');
        $('#filterFechaDesde').val('');
        $('#filterFechaHasta').val('');
        $('#filterObservaciones').val('');
        aplicarFiltros();
    });

    // Modal para ver observaciones completas
    $(document).on('click', '.ver-mas-btn', function() {
        const textoCompleto = $(this).data('texto');
        const fila = $(this).closest('tr');
        const operador = fila.find('td:nth-child(2) strong').text();
        const tipo = fila.find('td:nth-child(3) .badge').clone();

        $('#modalOperador').text(operador);
        $('#modalTipo').html(tipo);
        $('#modalObservacionesTexto').text(textoCompleto);

        $('#modalObservacionesCompletas').modal('show');
    });

    // Validar que fecha hasta no sea menor que fecha desde
    $('#filterFechaDesde, #filterFechaHasta').on('change', function() {
        const fechaDesde = $('#filterFechaDesde').val();
        const fechaHasta = $('#filterFechaHasta').val();

        if (fechaDesde && fechaHasta && new Date(fechaDesde) > new Date(fechaHasta)) {
            alert('La fecha "Hasta" no puede ser anterior a la fecha "Desde"');
            $(this).val('');
        }
    });

    // Modal de confirmación para eliminar
    $(document).on('click', '.btn-eliminar', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const operador = $(this).data('operador');
        const tipo = $(this).data('tipo');
        const fecha = $(this).data('fecha');

        // Crear modal dinámico
        const modalHTML = `
            <div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header border-0" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                            <h5 class="modal-title text-white">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <div class="bg-danger bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="bi bi-trash-fill text-danger fs-1"></i>
                                </div>
                                <h5 class="text-danger mb-3">¿Está seguro de eliminar esta actualización?</h5>

                                <div class="alert alert-warning border-warning border-2 bg-warning bg-opacity-10">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill fs-4 me-2 text-warning"></i>
                                        <div class="text-start">
                                            <small class="fw-medium">Esta acción no se puede deshacer</small>
                                            <p class="mb-0 small">La actualización será eliminada permanentemente</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-4">
                                    <h6 class="text-muted mb-2">Detalles de la actualización:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="text-muted" style="width: 120px;">ID:</td>
                                                    <td class="fw-medium">${id}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Operador:</td>
                                                    <td class="fw-medium">${operador}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Tipo:</td>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-info">
                                                            ${tipo}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Fecha:</td>
                                                    <td class="fw-medium">${fecha}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </button>
                            <button type="button" class="btn btn-danger" id="confirmarEliminarBtn" data-id="${id}">
                                <i class="bi bi-trash-fill me-1"></i> Sí, eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remover modal anterior si existe
        $('#modalConfirmarEliminar').remove();

        // Agregar nuevo modal al body
        $('body').append(modalHTML);

        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
        modal.show();

        // Evento para confirmar eliminación
        $('#modalConfirmarEliminar').on('click', '#confirmarEliminarBtn', function() {
            const btn = $(this);
            const id = btn.data('id');

            // Mostrar spinner y deshabilitar
            btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Eliminando...').prop('disabled', true);

            // Enviar formulario de eliminación
            $(`#formEliminar${id}`).submit();
        });

        // Limpiar modal al cerrar
        $('#modalConfirmarEliminar').on('hidden.bs.modal', function () {
            $(this).remove();
        });
    });
});
</script>
@endpush
