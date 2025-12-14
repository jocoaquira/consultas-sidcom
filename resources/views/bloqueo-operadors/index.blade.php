@extends('layouts.app')

@section('title', 'Gestión de Bloqueos de Operadores')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-shield-lock me-2"></i>Gestión de Bloqueos de Operadores
                    </h4>
                    <a href="{{ route('bloqueo-operadors.create') }}"
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
                        <span>Nuevo Bloqueo/Desbloqueo</span>
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtros mejorados -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Estado</label>
                            <select class="form-select form-select-sm" id="filterEstado">
                                <option value="">Todos</option>
                                <option value="activo">Activos</option>
                                <option value="bloqueado">Bloqueados</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Fecha</label>
                            <input type="date" class="form-control form-control-sm" id="filterFecha">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Operador</label>
                            <input type="text" class="form-control form-control-sm" id="filterOperadorTexto"
                                   placeholder="Buscar operador...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Motivo</label>
                            <input type="text" class="form-control form-control-sm" id="filterMotivo"
                                   placeholder="Buscar en motivo...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                    id="btnLimpiarFiltros">
                                <i class="bi bi-x-circle me-1"></i> Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="bloqueosTable">
                            <thead>
                                <tr style="background-color: rgba(139, 0, 0, 0.05);">
                                    <th style="color: #8B0000; width: 60px;">#</th>
                                    <th style="color: #8B0000; min-width: 200px;">Operador Minero</th>
                                    <th style="color: #8B0000; width: 120px;">Estado</th>
                                    <th style="color: #8B0000; min-width: 300px;">Motivo</th>
                                    <th style="color: #8B0000; width: 100px;">Fecha</th>
                                    <th style="color: #8B0000; width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bloqueos as $bloqueo)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            {{ $bloqueo->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $bloqueo->razon_social ?? 'N/A' }}</strong>
                                        @if($bloqueo->operador_minero_id)
                                        <br>
                                        <small class="text-muted">SDMMRE-{{ $bloqueo->operador_minero_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $estadoColor = $bloqueo->estado === 'bloqueado' ? 'danger' : 'success';
                                            $estadoIcono = $bloqueo->estado === 'bloqueado' ? 'bi-lock-fill' : 'bi-unlock-fill';
                                            $estadoTexto = $bloqueo->estado === 'bloqueado' ? 'BLOQUEADO' : 'ACTIVO';
                                        @endphp
                                        <span class="badge bg-{{ $estadoColor }}-subtle text-{{ $estadoColor }} border border-{{ $estadoColor }}-subtle">
                                            <i class="bi {{ $estadoIcono }} me-1"></i>
                                            {{ $estadoTexto }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="motivo-texto"
                                             style="max-height: 60px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                            {{ $bloqueo->motivo }}
                                        </div>
                                        @if(strlen($bloqueo->motivo) > 150)
                                        <button type="button"
                                                class="btn btn-link btn-sm p-0 mt-1 ver-mas-btn"
                                                data-texto="{{ $bloqueo->motivo }}">
                                            Ver más...
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($bloqueo->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('bloqueo-operadors.show', $bloqueo->id) }}"
                                               class="btn btn-outline-primary"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('bloqueo-operadors.edit', $bloqueo->id) }}"
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
                                                    data-id="{{ $bloqueo->id }}"
                                                    data-operador="{{ $bloqueo->razon_social ?? 'N/A' }}"
                                                    data-estado="{{ $bloqueo->estado }}"
                                                    data-fecha="{{ \Carbon\Carbon::parse($bloqueo->fecha)->format('d/m/Y') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            <!-- Formulario hidden para eliminar -->
                                            <form action="{{ route('bloqueo-operadors.destroy', $bloqueo->id) }}"
                                                  method="POST" class="d-inline form-eliminar"
                                                  id="formEliminar{{ $bloqueo->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="py-4">
                                            <i class="bi bi-inbox display-4 text-muted"></i>
                                            <p class="mt-3">No hay registros de bloqueos</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINACIÓN CORREGIDA -->
                    @if($bloqueos->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            <small>
                                <i class="bi bi-info-circle me-1"></i>
                                Mostrando {{ $bloqueos->firstItem() ?? 0 }}-{{ $bloqueos->lastItem() ?? 0 }}
                                de {{ $bloqueos->total() }} registros
                            </small>
                        </div>

                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Enlace anterior --}}
                                <li class="page-item {{ $bloqueos->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link"
                                       href="{{ $bloqueos->previousPageUrl() }}"
                                       aria-label="Anterior"
                                       style="{{ !$bloqueos->onFirstPage() ? 'color: #8B0000;' : '' }}">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                {{-- Páginas numeradas --}}
                                @php
                                    $current = $bloqueos->currentPage();
                                    $last = $bloqueos->lastPage();
                                    $start = max(1, $current - 2);
                                    $end = min($last, $current + 2);
                                @endphp

                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bloqueos->url(1) }}" style="color: #8B0000;">
                                            1
                                        </a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                @for ($page = $start; $page <= $end; $page++)
                                    @if($page == $current)
                                        <li class="page-item active">
                                            <span class="page-link" style="background-color: #8B0000; border-color: #8B0000;">
                                                {{ $page }}
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $bloqueos->url($page) }}" style="color: #8B0000;">
                                                {{ $page }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor

                                @if($end < $last)
                                    @if($end < $last - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bloqueos->url($last) }}" style="color: #8B0000;">
                                            {{ $last }}
                                        </a>
                                    </li>
                                @endif

                                {{-- Enlace siguiente --}}
                                <li class="page-item {{ !$bloqueos->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link"
                                       href="{{ $bloqueos->nextPageUrl() }}"
                                       aria-label="Siguiente"
                                       style="{{ $bloqueos->hasMorePages() ? 'color: #8B0000;' : '' }}">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <div class="d-flex align-items-center">
                            <small class="text-muted me-2">Ir a:</small>
                            <div class="input-group input-group-sm" style="width: 80px;">
                                <input type="number"
                                       id="paginaInput"
                                       class="form-control form-control-sm"
                                       min="1"
                                       max="{{ $bloqueos->lastPage() }}"
                                       value="{{ $bloqueos->currentPage() }}"
                                       style="border-color: #8B0000;">
                                <button class="btn btn-outline-secondary btn-sm" type="button" id="btnIrPagina">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Total: {{ $bloqueos->total() }} registros
                        </small>
                    </div>
                    @endif
                </div>
                <div class="card-footer text-muted" style="background-color: rgba(139, 0, 0, 0.03);">
                    <small>
                        <i class="bi bi-clock-history me-1"></i>
                        Última actualización: {{ now()->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver motivo completo -->
<div class="modal fade" id="modalMotivoCompleto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-chat-left-text me-2"></i>Motivo Completo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium" style="color: #8B0000;">Operador:</label>
                    <p id="modalOperador" class="mb-2"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium" style="color: #8B0000;">Estado:</label>
                    <span id="modalEstado" class="badge"></span>
                </div>
                <div>
                    <label class="form-label fw-medium" style="color: #8B0000;">Motivo:</label>
                    <div class="border rounded p-3 bg-light" id="modalMotivoTexto"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cerrar
                </button>
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

    /* Estilo para el motivo en la tabla */
    .motivo-texto {
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

    /* Estilos para el modal de confirmación */
    #modalConfirmarEliminar .modal-content {
        border-radius: 12px;
    }

    #modalConfirmarEliminar .modal-body {
        padding: 2rem;
    }

    #modalConfirmarEliminar .alert-warning {
        border-left: 4px solid #ffc107;
    }

    #modalConfirmarEliminar .table-sm td {
        padding: 0.25rem 0;
    }

    /* Estilos para paginación */
    .page-link {
        border: 1px solid #dee2e6;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background-color: rgba(139, 0, 0, 0.1);
        color: #8B0000;
        border-color: #8B0000;
    }

    .page-item.active .page-link {
        background-color: #8B0000;
        border-color: #8B0000;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #f8f9fa;
        border-color: #dee2e6;
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

    // Filtros en tiempo real mejorados
    function aplicarFiltros() {
        let estado = $('#filterEstado').val();
        let fecha = $('#filterFecha').val();
        let operadorTexto = $('#filterOperadorTexto').val().toLowerCase();
        let motivo = $('#filterMotivo').val().toLowerCase();

        $('#bloqueosTable tbody tr').each(function() {
            let row = $(this);
            let rowEstado = row.find('td:nth-child(3) .badge').text().toLowerCase().includes('bloqueado') ? 'bloqueado' : 'activo';
            let rowFecha = row.find('td:nth-child(5)').text().trim();
            let rowOperador = row.find('td:nth-child(2)').text().toLowerCase();
            let rowMotivo = row.find('td:nth-child(4) .motivo-texto').text().toLowerCase();

            let show = true;

            // Filtrar por estado
            if (estado && rowEstado !== estado) show = false;

            // Filtrar por fecha
            if (fecha) {
                let fechaFiltro = new Date(fecha).toLocaleDateString('es-ES');
                if (rowFecha !== fechaFiltro) show = false;
            }

            // Filtrar por operador (texto)
            if (operadorTexto && !rowOperador.includes(operadorTexto)) show = false;

            // Filtrar por motivo
            if (motivo && !rowMotivo.includes(motivo)) show = false;

            show ? row.show() : row.hide();
        });
    }

    // Eventos para filtros
    $('#filterEstado, #filterFecha, #filterOperadorTexto, #filterMotivo').on('change keyup', function() {
        aplicarFiltros();
    });

    // Limpiar filtros
    $('#btnLimpiarFiltros').click(function() {
        $('#filterEstado').val('');
        $('#filterFecha').val('');
        $('#filterOperadorTexto').val('');
        $('#filterMotivo').val('');
        aplicarFiltros();
    });

    // Modal para ver motivo completo
    $(document).on('click', '.ver-mas-btn', function() {
        const textoCompleto = $(this).data('texto');
        const fila = $(this).closest('tr');
        const operador = fila.find('td:nth-child(2) strong').text();
        const estadoBadge = fila.find('td:nth-child(3) .badge').clone();

        $('#modalOperador').text(operador);
        $('#modalEstado').html(estadoBadge);
        $('#modalMotivoTexto').text(textoCompleto);

        $('#modalMotivoCompleto').modal('show');
    });

    // Doble click en fecha para limpiar
    $('#filterFecha').on('dblclick', function() {
        $(this).val('');
        aplicarFiltros();
    });

    // Función para ir a página específica
    $('#btnIrPagina').click(function() {
        const pagina = $('#paginaInput').val();
        const totalPaginas = {{ $bloqueos->lastPage() }};

        if (pagina >= 1 && pagina <= totalPaginas) {
            const baseUrl = window.location.href.split('?')[0];
            const params = new URLSearchParams(window.location.search);
            params.set('page', pagina);
            window.location.href = baseUrl + '?' + params.toString();
        }
    });

    // Permitir Enter en el input de página
    $('#paginaInput').keypress(function(e) {
        if (e.which === 13) {
            $('#btnIrPagina').click();
        }
    });

    // Modal de confirmación para eliminar
    $(document).on('click', '.btn-eliminar', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const operador = $(this).data('operador');
        const estado = $(this).data('estado');
        const fecha = $(this).data('fecha');

        // Texto del estado
        const estadoTexto = estado === 'bloqueado' ? 'BLOQUEADO' : 'ACTIVO';
        const estadoColor = estado === 'bloqueado' ? 'danger' : 'success';
        const estadoIcono = estado === 'bloqueado' ? 'bi-lock-fill' : 'bi-unlock-fill';

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
                                <h5 class="text-danger mb-3">¿Está seguro de eliminar este registro?</h5>

                                <div class="alert alert-warning border-warning border-2 bg-warning bg-opacity-10">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill fs-4 me-2 text-warning"></i>
                                        <div class="text-start">
                                            <small class="fw-medium">Esta acción no se puede deshacer</small>
                                            <p class="mb-0 small">El registro será eliminado permanentemente del sistema</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-start mt-4">
                                    <h6 class="text-muted mb-2">Detalles del registro:</h6>
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
                                                    <td class="text-muted">Estado:</td>
                                                    <td>
                                                        <span class="badge bg-${estadoColor}-subtle text-${estadoColor} border border-${estadoColor}-subtle">
                                                            <i class="bi ${estadoIcono} me-1"></i>
                                                            ${estadoTexto}
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
                                <i class="bi bi-trash-fill me-1"></i> Sí, eliminar registro
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
