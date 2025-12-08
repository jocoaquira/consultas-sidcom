@extends('layouts.app')

@section('title', 'Operadores Mineros')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
            <h5 class="mb-0 text-white">
                <i class="fas fa-hard-hat me-2"></i>Operadores Mineros
            </h5>
            <div>
                <span class="badge bg-light text-dark fs-6">
                    Mostrando {{ $productos->count() }} de {{ $productos->total() }} registros
                </span>
            </div>
        </div>

        <!-- TABS DE NAVEGACIÓN -->
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-bottom-0" id="operadoresTab" role="tablist" style="background: #495057;">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab', 'todos') == 'todos' ? 'active' : '' }}"
                       href="?tab=todos&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-grid-3x3-gap me-1"></i>
                        <span class="d-none d-lg-inline">Todos</span>
                        <span class="badge ms-1">{{ $stats['todos'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') == 'por_vencer' ? 'active' : '' }}"
                       href="?tab=por_vencer&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-clock-fill me-1"></i>
                        <span class="d-none d-xl-inline">Por Vencer</span>
                        <span class="badge ms-1">{{ $stats['por_vencer'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') == 'activos_vencidos' ? 'active' : '' }}"
                       href="?tab=activos_vencidos&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <span class="d-none d-xl-inline">Activos</span>
                        <span class="badge ms-1">{{ $stats['activos_vencidos'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') == 'bloqueados_vencidos' ? 'active' : '' }}"
                       href="?tab=bloqueados_vencidos&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-lock-fill me-1"></i>
                        <span class="d-none d-xl-inline">Bloqueados</span>
                        <span class="badge ms-1">{{ $stats['bloqueados_vencidos'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') == 'nim_vencido' ? 'active' : '' }}"
                       href="?tab=nim_vencido&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-file-earmark-x me-1"></i>
                        <span class="d-none d-lg-inline">NIM</span>
                        <span class="badge ms-1">{{ $stats['nim_vencido'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') == 'seprec_vencido' ? 'active' : '' }}"
                       href="?tab=seprec_vencido&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-file-earmark-x me-1"></i>
                        <span class="d-none d-lg-inline">SEPREC</span>
                        <span class="badge ms-1">{{ $stats['seprec_vencido'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') == 'idom_vencido' ? 'active' : '' }}"
                       href="?tab=idom_vencido&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-file-earmark-x me-1"></i>
                        <span class="d-none d-lg-inline">IDOM</span>
                        <span class="badge ms-1">{{ $stats['idom_vencido'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ request('tab') == 'todo_vencido' ? 'active' : '' }}"
                       href="?tab=todo_vencido&{{ http_build_query(request()->except('tab', 'page')) }}">
                        <i class="bi bi-x-octagon-fill me-1"></i>
                        <span class="d-none d-xl-inline">Todo</span>
                        <span class="badge ms-1">{{ $stats['todo_vencido'] }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- FILTROS MEJORADOS CON BÚSQUEDA GLOBAL -->
        <div class="card-body border-bottom p-3 bg-light">
            <form method="GET" action="{{ route('operadores.index') }}" id="formFiltros">
                <input type="hidden" name="tab" value="{{ request('tab', 'todos') }}">

                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Buscar: Razón social, representante, NIT, CI, email, observaciones...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" name="tipo">
                            <option value="">Todos los tipos</option>
                            @php
                                $tipos = [
                                    1 => 'COOPERATIVA',
                                    2 => 'ESTATAL',
                                    3 => 'PRIVADA'
                                ];
                            @endphp
                            @foreach($tipos as $key => $value)
                            <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" name="estado">
                            <option value="">Todos los estados</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" name="sort">
                            <option value="prioridad" {{ request('sort', 'prioridad') == 'prioridad' ? 'selected' : '' }}>
                                Ordenar por Prioridad
                            </option>
                            <option value="razon_social" {{ request('sort') == 'razon_social' ? 'selected' : '' }}>
                                Razón Social
                            </option>
                            <option value="fecha_expiracion" {{ request('sort') == 'fecha_expiracion' ? 'selected' : '' }}>
                                Fecha Exp. IDOM
                            </option>
                            <option value="fecha_exp_nim" {{ request('sort') == 'fecha_exp_nim' ? 'selected' : '' }}>
                                Fecha Exp. NIM
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                                <i class="bi bi-funnel me-1"></i> Filtrar
                            </button>
                            <a href="{{ route('operadores.index') }}?tab={{ request('tab', 'todos') }}"
                               class="btn btn-sm btn-outline-secondary"
                               title="Limpiar filtros">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @if($productos->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted mb-2">No hay operadores en esta categoría</h5>
                    <p class="text-muted">Intenta cambiar los filtros o seleccionar otra pestaña</p>
                </div>
            @else
                {{-- Tabla con scroll --}}
                <div class="table-container" style="max-height: calc(100vh - 380px); overflow: auto;">
                    <table class="table table-bordered table-striped table-hover mb-0" id="operadoresTable">
                        <thead class="table-dark sticky-top" style="top: 0;">
                            <tr>
                                <th class="text-nowrap p-1 small" style="width: 40px;">ID</th>
                                <th class="text-nowrap p-1 small text-center" style="width: 45px;">
                                    <i class="bi bi-flag-fill" title="Prioridad"></i>
                                </th>
                                <th class="text-nowrap p-1 small" style="width: 180px; max-width: 180px;">RAZÓN SOCIAL</th>
                                <th class="text-nowrap p-1 small" style="width: 140px;">REPRESENT.</th>
                                <th class="text-nowrap p-1 small text-center" style="width: 60px;">TIPO</th>
                                <th class="text-nowrap p-1 small" style="width: 110px;">NIM</th>
                                <th class="text-nowrap p-1 small" style="width: 110px;">SEPREC</th>
                                <th class="text-nowrap p-1 small" style="width: 110px;">IDOM</th>
                                <th class="text-nowrap p-1 small text-center" style="width: 45px;">
                                    <i class="bi bi-people-fill" title="Usuarios"></i>
                                </th>
                                <th class="text-nowrap p-1 small text-center" style="width: 70px;">ESTADO</th>
                                <th class="text-nowrap p-1 small text-center" style="width: 80px;">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @foreach($productos as $producto)
                            @php
                                $tieneUsuariosActivos = $producto->usuario()->where('estado_usuario', '=', '1')->count() > 0;
                                $diasNim = $producto->fecha_exp_nim ? round((strtotime($producto->fecha_exp_nim) - strtotime(now())) / 86400) : null;
                                $diasSeprec = $producto->fecha_exp_funda ? round((strtotime($producto->fecha_exp_funda) - strtotime(now())) / 86400) : null;
                                $diasIdom = $producto->fecha_expiracion ? round((strtotime($producto->fecha_expiracion) - strtotime(now())) / 86400) : null;

                                // Calcular nivel de prioridad
                                $prioridad = 0;
                                if ($tieneUsuariosActivos) $prioridad += 3;
                                if ($diasIdom !== null && $diasIdom < 0) $prioridad += 5;
                                if ($diasNim !== null && $diasNim < 0) $prioridad += 3;
                                if ($diasSeprec !== null && $diasSeprec < 0 && $producto->actor_minero == 3) $prioridad += 2;
                            @endphp
                            <tr>
                                {{-- ID --}}
                                <td class="text-center fw-bold p-1 small">{{ $producto->id_operador_minero }}</td>

                                {{-- INDICADOR DE PRIORIDAD --}}
                                <td class="text-center p-1">
                                    @if($prioridad >= 8)
                                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5" title="Prioridad Crítica"></i>
                                    @elseif($prioridad >= 5)
                                        <i class="bi bi-exclamation-circle-fill text-warning fs-5" title="Prioridad Alta"></i>
                                    @elseif($prioridad >= 3)
                                        <i class="bi bi-info-circle-fill text-info fs-5" title="Prioridad Media"></i>
                                    @else
                                        <i class="bi bi-check-circle-fill text-success fs-5" title="Sin problemas"></i>
                                    @endif
                                </td>

                                {{-- RAZÓN SOCIAL - COMPACTA --}}
                                <td class="p-1 small" style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                    title="{{ $producto->razon_social }}{{ $producto->nit ? ' - NIT: ' . $producto->nit : '' }}{{ $producto->email_op_min ? ' - ' . $producto->email_op_min : '' }}">
                                    <strong>{{ Str::limit($producto->razon_social, 25) }}</strong>
                                    @if($producto->nit)
                                        <br>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $producto->nit }}</small>
                                    @endif
                                </td>

                                {{-- REPRESENTANTE - COMPACTO --}}
                                <td class="p-1 small" style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                    title="{{ $producto->nombre_rep_legal }}{{ $producto->ci_rep_legal ? ' - CI: ' . $producto->ci_rep_legal : '' }}">
                                    {{ Str::limit($producto->nombre_rep_legal, 20) }}
                                    @if($producto->cel_op_min)
                                        <br>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $producto->cel_op_min }}</small>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $producto->cel_rep_legal }}</small>
                                    @endif
                                </td>

                                {{-- TIPO --}}
                                <td class="text-center p-1">
                                    @php
                                        $tipos = [
                                            1 => ['text' => 'COOP', 'class' => 'bg-info'],
                                            2 => ['text' => 'EST', 'class' => 'bg-warning'],
                                            3 => ['text' => 'PRIV', 'class' => 'bg-success']
                                        ];
                                        $tipo = $tipos[$producto->actor_minero] ?? ['text' => 'N/A', 'class' => 'bg-secondary'];
                                    @endphp
                                    <span class="badge {{ $tipo['class'] }} p-1" style="font-size: 0.7rem;">{{ $tipo['text'] }}</span>
                                </td>

                                {{-- FECHA EXP NIM --}}
                                <td class="text-center p-1 small {{
                                    ($producto->fecha_exp_nim && $diasNim < 0) ? 'estado1' :
                                    (($producto->fecha_exp_nim && $diasNim < 11) ? 'estado2' : '')
                                }}">
                                    @if($producto->fecha_exp_nim)
                                        <div style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($producto->fecha_exp_nim)->format('d/m/Y') }}</div>
                                        <small style="font-size: 0.65rem;" class="{{ ($diasNim < 0) ? 'text-white' : 'text-muted' }}">
                                            @if($diasNim > 0)
                                                {{ $diasNim }}d
                                            @elseif($diasNim == 0)
                                                Hoy
                                            @else
                                                -{{ abs($diasNim) }}d
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>

                                {{-- FECHA EXP SEPREC --}}
                                <td class="text-center p-1 small {{
                                    ($producto->fecha_exp_funda && $producto->actor_minero == 3 && $diasSeprec < 0) ? 'estado1' :
                                    (($producto->fecha_exp_funda && $producto->actor_minero == 3 && $diasSeprec < 11) ? 'estado2' : '')
                                }}">
                                    @if($producto->fecha_exp_funda && $producto->actor_minero == 3)
                                        <div style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($producto->fecha_exp_funda)->format('d/m/Y') }}</div>
                                        <small style="font-size: 0.65rem;" class="{{ ($diasSeprec < 0) ? 'text-white' : 'text-muted' }}">
                                            @if($diasSeprec > 0)
                                                {{ $diasSeprec }}d
                                            @elseif($diasSeprec == 0)
                                                Hoy
                                            @else
                                                -{{ abs($diasSeprec) }}d
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>

                                {{-- FECHA EXP IDOM --}}
                                <td class="text-center p-1 small {{
                                    ($producto->fecha_expiracion && $diasIdom < 0) ? 'estado1' :
                                    (($producto->fecha_expiracion && $diasIdom < 11) ? 'estado2' : '')
                                }}">
                                    @if($producto->fecha_expiracion)
                                        <div style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($producto->fecha_expiracion)->format('d/m/Y') }}</div>
                                        <small style="font-size: 0.65rem;" class="{{ ($diasIdom < 0) ? 'text-white' : 'text-muted' }}">
                                            @if($diasIdom > 0)
                                                {{ $diasIdom }}d
                                            @elseif($diasIdom == 0)
                                                Hoy
                                            @else
                                                -{{ abs($diasIdom) }}d
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>

                                {{-- USUARIOS COUNT --}}
                                <td class="text-center p-1">
                                    <span class="badge bg-secondary p-1" style="font-size: 0.7rem;">{{ $producto->usuario()->count() }}</span>
                                </td>

                                {{-- ESTADO USUARIOS --}}
                                <td class="text-center p-1">
                                    @if($tieneUsuariosActivos)
                                        <span class="badge bg-success p-1" style="font-size: 0.65rem;">Activo</span>
                                    @else
                                        <span class="badge bg-danger p-1" style="font-size: 0.65rem;">Bloq.</span>
                                    @endif
                                </td>

                                {{-- NOTIFICACIÓN --}}
                                <td class="text-center p-1">
                                    <button type="button"
                                            class="btn btn-warning btn-sm btn-notificar"
                                            data-operador="{{ $producto->razon_social }}"
                                            data-id="{{ $producto->id_operador_minero }}"
                                            style="padding: 4px 8px !important; font-size: 0.75rem;"
                                            title="Enviar notificación">
                                        <i class="fas fa-paper-plane"></i> Notificar
                                    </button>

                                    <form action="{{ route('operadores.notificar', $producto->id_operador_minero) }}"
                                          method="POST" class="d-inline form-notificar"
                                          id="formNotificar{{ $producto->id_operador_minero }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                @if($productos->hasPages())
                <div class="pagination-container p-3 border-top">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted">
                            Mostrando {{ $productos->firstItem() }} - {{ $productos->lastItem() }} de {{ $productos->total() }} registros
                            <small class="text-info ms-2">(Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }})</small>
                        </div>
                        <div>
                            {{ $productos->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
                @else
                <div class="p-3 border-top text-muted text-center">
                    Total: {{ $productos->total() }} registro(s) - Página {{ $productos->currentPage() }}
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
/* Tabs personalizados - MEJORADOS CON FONDO OSCURO */
.nav-tabs {
    background-color: #495057 !important;
    padding: 5px 5px 0 5px;
}

.nav-tabs .nav-link {
    color: #f8f9fa !important;
    background-color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
    margin: 0 3px;
    border-radius: 6px 6px 0 0;
    font-weight: 500;
    padding: 8px 12px;
}

.nav-tabs .nav-link:hover {
    border-bottom: 3px solid #FFD700;
    background-color: #5a6268;
    color: #ffffff !important;
}

.nav-tabs .nav-link.active {
    background-color: #fff;
    border-bottom: 3px solid #8B0000;
    color: #8B0000 !important;
    font-weight: 600;
}

.nav-tabs .nav-link .badge {
    background-color: rgba(255, 255, 255, 0.3);
    color: #fff;
    font-weight: 600;
}

.nav-tabs .nav-link.active .badge {
    background-color: #8B0000;
    color: white;
}

.nav-tabs .nav-link i {
    font-size: 1rem;
}

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

/* Tabla compacta */
.table-container {
    overflow-x: auto;
    overflow-y: auto;
}

.table {
    font-size: 0.85rem;
}

.table thead th {
    position: sticky;
    top: 0;
    background-color: #343a40;
    z-index: 10;
    padding: 0.5rem 0.25rem !important;
}

.table tbody tr:hover {
    background-color: rgba(139, 0, 0, 0.05);
}

.table td {
    vertical-align: middle;
}

/* Íconos de prioridad */
.bi-exclamation-triangle-fill { color: #dc3545 !important; }
.bi-exclamation-circle-fill { color: #ffc107 !important; }
.bi-info-circle-fill { color: #0dcaf0 !important; }
.bi-check-circle-fill { color: #198754 !important; }

/* Badges compactos */
.badge {
    font-size: 0.75em;
    padding: 0.25em 0.5em;
}

/* Botón notificar mejorado */
.btn-warning {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    color: #6c2300 !important;
    border: none;
    font-weight: 600;
    white-space: nowrap;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #FFED4E 0%, #FFB347 100%);
    color: #6c2300 !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.btn-warning:active {
    transform: translateY(0);
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

/* Tooltip para textos truncados */
[title] {
    cursor: help;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Notificación con modal
    $(document).on('click', '.btn-notificar', function(e) {
        e.preventDefault();

        const operador = $(this).data('operador');
        const id = $(this).data('id');

        $('#modalTituloNotificacion').text(`¿Enviar notificación a ${operador}?`);

        $('#confirmarNotificacionBtn').off('click').on('click', function() {
            const btn = $(this);
            btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Enviando...').prop('disabled', true);
            $(`#formNotificar${id}`).submit();
        });

        $('#modalConfirmarNotificacion').modal('show');
    });

    // Auto-submit del formulario al cambiar ordenamiento
    $('select[name="sort"]').on('change', function() {
        $('#formFiltros').submit();
    });
});
</script>
@endpush
@endsection
