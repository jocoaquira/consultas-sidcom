@extends('layouts.app')

@section('title', 'Operadores Mineros')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
            <h5 class="mb-0 text-white">
                <i class="fas fa-box me-2"></i>Operadores Mineros
            </h5>
            <div>
                <span class="badge bg-light text-dark fs-6">{{ $productos->total() }} registros</span>
            </div>
        </div>

        <!-- Filtros como en actualizaciones -->
        <div class="card-body border-bottom p-3 bg-light">
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0"
                               id="globalSearch"
                               placeholder="Buscar en todo...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="filterTipoOperador">
                        <option value="">Todos los tipos</option>
                        @php
                            $tipos = [
                                1 => 'COOPERATIVA',
                                2 => 'ESTATAL',
                                3 => 'PRIVADA'
                            ];
                        @endphp
                        @foreach($tipos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="filterEstado">
                        <option value="">Todos los estados</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" id="filterObservaciones"
                           placeholder="Buscar en observaciones...">
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-primary flex-grow-1" id="btnAplicarFiltros">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="btnLimpiarFiltros" title="Limpiar todo">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($productos->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted mb-2">No hay operadores registrados</h5>
                </div>
            @else
                {{-- Tabla con scroll --}}
                <div class="table-container" style="max-height: calc(100vh - 280px); overflow: auto;">
                    <table class="table table-bordered table-striped table-hover mb-0" id="operadoresTable">
                        <thead class="table-dark sticky-top" style="top: 0;">
                            <tr>
                                <th class="text-nowrap p-2">ID</th>
                                <th class="text-nowrap p-2">RAZÓN SOCIAL</th>
                                <th class="text-nowrap p-2">REPRESENTANTE</th>
                                <th class="text-nowrap p-2">TIPO</th>
                                <th class="text-nowrap p-2">EMAIL</th>
                                <th class="text-nowrap p-2">FECHA EXP NIM</th>
                                <th class="text-nowrap p-2">FECHA EXP SEPREC</th>
                                <th class="text-nowrap p-2">FECHA EXP IDOM</th>
                                <th class="text-nowrap p-2">OBSERVACIONES</th>
                                <th class="text-nowrap p-2">USUARIOS</th>
                                <th class="text-nowrap p-2">ESTADO</th>
                                <th class="text-nowrap p-2">NOTIFICAR</th>
                                <th class="text-nowrap p-2">MENSAJES</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @foreach($productos as $producto)
                            <tr class="fila-operador"
                                data-tipo="{{ $producto->actor_minero }}"
                                data-razon-social="{{ strtolower($producto->razon_social) }}"
                                data-representante="{{ strtolower($producto->nombre_rep_legal) }}"
                                data-observaciones="{{ strtolower($producto->observaciones ?? '') }}"
                                data-estado="{{ $producto->usuario()->where('estado_usuario', '=', '1')->count() > 0 ? 'activo' : 'inactivo' }}">
                                {{-- ID --}}
                                <td class="text-center fw-bold p-2">{{ $producto->id_operador_minero }}</td>

                                {{-- RAZÓN SOCIAL --}}
                                <td class="text-nowrap p-2">
                                    <strong>{{ $producto->razon_social }}</strong>
                                    @if($producto->nit)
                                        <br>
                                        <small class="text-muted">NIT: {{ $producto->nit }}</small>
                                    @endif
                                </td>

                                {{-- REPRESENTANTE --}}
                                <td class="text-nowrap p-2">
                                    {{ $producto->nombre_rep_legal }}
                                    @if($producto->ci_rep_legal)
                                        <br>
                                        <small class="text-muted">CI: {{ $producto->ci_rep_legal }}</small>
                                    @endif
                                </td>

                                {{-- TIPO (COOPERATIVA=1, ESTATAL=2, PRIVADA=3) --}}
                                <td class="text-center p-2">
                                    @php
                                        $tipos = [
                                            1 => ['text' => 'COOPERATIVA', 'class' => 'bg-info'],
                                            2 => ['text' => 'ESTATAL', 'class' => 'bg-warning'],
                                            3 => ['text' => 'PRIVADA', 'class' => 'bg-success']
                                        ];
                                        $tipo = $tipos[$producto->actor_minero] ?? ['text' => 'DESCONOCIDO', 'class' => 'bg-secondary'];
                                    @endphp
                                    <span class="badge {{ $tipo['class'] }}">{{ $tipo['text'] }}</span>
                                </td>

                                {{-- EMAIL --}}
                                <td class="text-nowrap p-2">
                                    @if($producto->email_op_min)
                                        <a href="mailto:{{ $producto->email_op_min }}"
                                           class="text-decoration-none"
                                           title="{{ $producto->email_op_min }}">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ $producto->email_op_min }}
                                        </a>
                                    @else
                                        <span class="text-muted">Sin email</span>
                                    @endif
                                </td>

                                {{-- FECHA EXP NIM --}}
                                @php
                                    $diasNim = $producto->fecha_exp_nim ?
                                        round((strtotime($producto->fecha_exp_nim) - strtotime(now())) / 86400) :
                                        null;
                                @endphp
                                <td class="text-center p-2 {{
                                    ($producto->fecha_exp_nim && $diasNim < 0) ? 'estado1' :
                                    (($producto->fecha_exp_nim && $diasNim < 5) ? 'estado2' : '')
                                }}">
                                    @if($producto->fecha_exp_nim)
                                        {{ \Carbon\Carbon::parse($producto->fecha_exp_nim)->format('d/m/Y') }}
                                        <br>
                                        <small class="{{ ($diasNim < 0) ? 'text-white' : 'text-muted' }}">
                                            @if($diasNim > 0)
                                                {{ $diasNim }} días
                                            @elseif($diasNim == 0)
                                                Hoy vence
                                            @else
                                                Vencido {{ abs($diasNim) }} días
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                {{-- FECHA EXP SEPREC --}}
                                @php
                                    $diasSeprec = $producto->fecha_exp_funda ?
                                        round((strtotime($producto->fecha_exp_funda) - strtotime(now())) / 86400) :
                                        null;
                                @endphp
                                <td class="text-center p-2 {{
                                    ($producto->fecha_exp_funda && $producto->actor_minero == 3 && $diasSeprec < 0) ? 'estado1' :
                                    (($producto->fecha_exp_funda && $producto->actor_minero == 3 && $diasSeprec < 5) ? 'estado2' : '')
                                }}">
                                    @if($producto->fecha_exp_funda && $producto->actor_minero == 3)
                                        {{ \Carbon\Carbon::parse($producto->fecha_exp_funda)->format('d/m/Y') }}
                                        <br>
                                        <small class="{{ ($diasSeprec < 0) ? 'text-white' : 'text-muted' }}">
                                            @if($diasSeprec > 0)
                                                {{ $diasSeprec }} días
                                            @elseif($diasSeprec == 0)
                                                Hoy vence
                                            @else
                                                Vencido {{ abs($diasSeprec) }} días
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                {{-- FECHA EXP IDOM --}}
                                @php
                                    $diasIdom = $producto->fecha_expiracion ?
                                        round((strtotime($producto->fecha_expiracion) - strtotime(now())) / 86400) :
                                        null;
                                @endphp
                                <td class="text-center p-2 {{
                                    ($producto->fecha_expiracion && $diasIdom < 0) ? 'estado1' :
                                    (($producto->fecha_expiracion && $diasIdom < 5) ? 'estado2' : '')
                                }}">
                                    @if($producto->fecha_expiracion)
                                        {{ \Carbon\Carbon::parse($producto->fecha_expiracion)->format('d/m/Y') }}
                                        <br>
                                        <small class="{{ ($diasIdom < 0) ? 'text-white' : 'text-muted' }}">
                                            @if($diasIdom > 0)
                                                {{ $diasIdom }} días
                                            @elseif($diasIdom == 0)
                                                Hoy vence
                                            @else
                                                Vencido {{ abs($diasIdom) }} días
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                {{-- OBSERVACIONES --}}
                                <td class="p-2" style="min-width: 200px; max-width: 300px;">
                                    @if($producto->observaciones)
                                        <div class="observaciones-cell"
                                             style="word-wrap: break-word; white-space: pre-line; max-height: 100px; overflow-y: auto;"
                                             title="Haz scroll para ver todo el contenido">
                                            {{ $producto->observaciones }}
                                        </div>
                                    @else
                                        <span class="text-muted">Sin observaciones</span>
                                    @endif
                                </td>

                                {{-- USUARIOS COUNT --}}
                                <td class="text-center p-2">
                                    <span class="badge bg-secondary fs-6">{{ $producto->usuario()->count() }}</span>
                                </td>

                                {{-- ESTADO USUARIOS --}}
                                <td class="text-center p-2">
                                    @php
                                        $usuariosActivos = $producto->usuario()->where('estado_usuario', '=', '1')->count();
                                    @endphp
                                    @if($usuariosActivos == 0)
                                        <span class="badge bg-danger">Deshabilitado</span>
                                    @else
                                        <span class="badge bg-success">Activo</span>
                                    @endif
                                </td>

                                {{-- NOTIFICACIÓN --}}
                                <td class="text-center p-2">
                                    <button type="button"
                                            class="btn btn-warning btn-sm d-flex align-items-center btn-notificar"
                                            data-operador="{{ $producto->razon_social }}"
                                            data-id="{{ $producto->id_operador_minero }}"
                                            title="Enviar notificación">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        <span class="d-none d-md-inline">Notificar</span>
                                    </button>

                                    <!-- Formulario hidden para notificación -->
                                    <form action="{{ route('operadores.notificar', $producto->id_operador_minero) }}"
                                          method="POST" class="d-inline form-notificar"
                                          id="formNotificar{{ $producto->id_operador_minero }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>

                                {{-- EMAIL COUNT --}}
                                <td class="text-center p-2">
                                    <span class="badge bg-info fs-6">{{ $producto->email()->count() }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN MEJORADA --}}
                @if($productos->hasPages())
                <div class="pagination-container p-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Mostrando {{ $productos->firstItem() }} - {{ $productos->lastItem() }} de {{ $productos->total() }} registros
                        </div>
                        <div>
                            <nav aria-label="Paginación">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Botón Anterior --}}
                                    @if($productos->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">« Anterior</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $productos->previousPageUrl() }}" rel="prev">
                                                « Anterior
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Números de página --}}
                                    @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                                        @if($page == $productos->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Botón Siguiente --}}
                                    @if($productos->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $productos->nextPageUrl() }}" rel="next">
                                                Siguiente »
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Siguiente »</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal para confirmación de notificación -->
<div class="modal fade" id="modalConfirmarNotificacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-envelope-check me-2"></i>Confirmar Notificación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="bi bi-envelope-exclamation-fill text-warning fs-1"></i>
                    </div>
                    <h5 class="text-warning mb-3" id="modalTituloNotificacion"></h5>

                    <div class="alert alert-info border-info border-2 bg-info bg-opacity-10">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-4 me-2 text-info"></i>
                            <div class="text-start">
                                <small class="fw-medium">Se enviará una notificación al operador</small>
                                <p class="mb-0 small">Esto notificará sobre documentos próximos a vencer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="confirmarNotificacionBtn">
                    <i class="bi bi-send-fill me-1"></i> Sí, enviar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Colores de alerta */
.estado1 {
    background-color: #961007 !important;
    color: #ffffff !important;
    font-weight: bold;
}
.estado2 {
    background-color: #f4f48a !important;
    font-weight: bold;
}
.estado1 small {
    color: #ffffff !important;
    opacity: 0.9;
}

/* Tabla responsiva */
.table-container {
    overflow-x: auto;
    overflow-y: auto;
}
.table thead th {
    position: sticky;
    top: 0;
    background-color: #343a40;
    z-index: 10;
}
.table tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}

/* Observaciones con scroll */
.observaciones-cell {
    max-height: 100px;
    overflow-y: auto;
    word-wrap: break-word;
    white-space: pre-line;
    padding: 5px;
    border-radius: 4px;
    background-color: #f8f9fa;
    font-size: 0.9em;
}

/* Badges personalizados */
.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

/* Botón notificar */
.btn-warning {
    background: linear-gradient(to right, #FFD700, #FFA500);
    color: #8B0000;
    border: none;
    font-weight: 600;
}

.btn-warning:hover {
    background: linear-gradient(to right, #FFED4E, #FFB347);
    color: #8B0000;
}

/* Paginación */
.pagination .page-item.active .page-link {
    background-color: #8B0000;
    border-color: #8B0000;
}
.pagination .page-link {
    color: #8B0000;
}
.pagination .page-link:hover {
    color: #6A0C0C;
    background-color: #f8f9fa;
}

/* Header del card */
.card-header .badge {
    font-size: 0.9em;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filtros en tiempo real que funcionan en todas las páginas
    function aplicarFiltros() {
        const busquedaGlobal = $('#globalSearch').val().toLowerCase();
        const tipoOperador = $('#filterTipoOperador').val();
        const estado = $('#filterEstado').val();
        const observaciones = $('#filterObservaciones').val().toLowerCase();

        let resultadosVisibles = 0;

        $('#tablaBody tr').each(function() {
            const row = $(this);
            const razonSocial = row.data('razon-social');
            const representante = row.data('representante');
            const tipo = row.data('tipo');
            const obs = row.data('observaciones');
            const rowEstado = row.data('estado');

            let mostrar = true;

            // Filtro global (busca en razón social y representante)
            if (busquedaGlobal) {
                if (!razonSocial.includes(busquedaGlobal) && !representante.includes(busquedaGlobal)) {
                    mostrar = false;
                }
            }

            // Filtro por tipo de operador
            if (tipoOperador && tipo.toString() !== tipoOperador) {
                mostrar = false;
            }

            // Filtro por estado
            if (estado && rowEstado !== estado) {
                mostrar = false;
            }

            // Filtro por observaciones
            if (observaciones && !obs.includes(observaciones)) {
                mostrar = false;
            }

            if (mostrar) {
                row.show();
                resultadosVisibles++;
            } else {
                row.hide();
            }
        });

        // Actualizar contador de resultados visibles
        $('#contadorResultados').text(resultadosVisibles + ' de ' + {{ $productos->total() }});
    }

    // Eventos para filtros
    $('#globalSearch, #filterObservaciones').on('keyup', function() {
        aplicarFiltros();
    });

    $('#filterTipoOperador, #filterEstado').on('change', function() {
        aplicarFiltros();
    });

    // Aplicar filtros al cargar (si hay filtros guardados)
    aplicarFiltros();

    // Limpiar filtros
    $('#btnLimpiarFiltros').click(function() {
        $('#globalSearch').val('');
        $('#filterTipoOperador').val('');
        $('#filterEstado').val('');
        $('#filterObservaciones').val('');
        aplicarFiltros();
    });

    // Aplicar filtros con botón
    $('#btnAplicarFiltros').click(function() {
        aplicarFiltros();
    });

    // Notificación con modal de confirmación
    $(document).on('click', '.btn-notificar', function(e) {
        e.preventDefault();

        const operador = $(this).data('operador');
        const id = $(this).data('id');

        // Configurar modal
        $('#modalTituloNotificacion').text(`¿Enviar notificación a ${operador}?`);

        // Configurar botón de confirmación
        $('#confirmarNotificacionBtn').off('click').on('click', function() {
            const btn = $(this);
            btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Enviando...').prop('disabled', true);

            // Enviar formulario
            $(`#formNotificar${id}`).submit();
        });

        // Mostrar modal
        $('#modalConfirmarNotificacion').modal('show');
    });

    // Scroll suave para observaciones largas
    document.querySelectorAll('.observaciones-cell').forEach(function(cell) {
        if (cell.scrollHeight > 100) {
            cell.title = "Haz scroll para ver todo el contenido";
        }
    });
});
</script>
@endpush
@endsection
