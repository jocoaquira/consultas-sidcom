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

        <!-- FILTROS -->
        <div class="card-body border-bottom p-3 bg-light">
            <form method="GET" action="{{ route('operadores.index') }}" id="formFiltros">
                <input type="hidden" name="tab" value="{{ request('tab', 'todos') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" name="search"
                                   value="{{ request('search') }}" placeholder="Buscar: Razón social, representante, NIT...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm" name="tipo">
                            <option value="">Todos los tipos</option>
                            <option value="1" {{ request('tipo') == '1' ? 'selected' : '' }}>COOPERATIVA</option>
                            <option value="2" {{ request('tipo') == '2' ? 'selected' : '' }}>ESTATAL</option>
                            <option value="3" {{ request('tipo') == '3' ? 'selected' : '' }}>PRIVADA</option>
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
                               class="btn btn-sm btn-outline-secondary" title="Limpiar filtros">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- TABLA -->
        <div class="card-body p-0">
            @if($productos->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted mb-2">No hay operadores en esta categoría</h5>
                    <p class="text-muted">Intenta cambiar los filtros o seleccionar otra pestaña</p>
                </div>
            @else
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
                                <th class="text-nowrap p-1 small text-center" style="width: 110px;">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody">
                            @foreach($productos as $producto)
                            @php
                                $tieneUsuariosActivos = $producto->usuario()->where('estado_usuario', '=', '1')->count() > 0;

                                // Calcular días usando Carbon
                                $diasNim = null;
                                if ($producto->fecha_exp_nim) {
                                    $diasNim = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($producto->fecha_exp_nim)->startOfDay(), false);
                                }

                                $diasSeprec = null;
                                if ($producto->fecha_exp_funda) {
                                    $diasSeprec = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($producto->fecha_exp_funda)->startOfDay(), false);
                                }

                                $diasIdom = null;
                                if ($producto->fecha_expiracion) {
                                    $diasIdom = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($producto->fecha_expiracion)->startOfDay(), false);
                                }

                                // Calcular nivel de prioridad
                                $prioridad = 0;
                                if ($tieneUsuariosActivos) $prioridad += 3;
                                if ($diasIdom !== null && $diasIdom < 0) $prioridad += 5;
                                if ($diasNim !== null && $diasNim < 0) $prioridad += 3;
                                if ($diasSeprec !== null && $diasSeprec < 0 && $producto->actor_minero == 3) $prioridad += 2;

                                // Obtener celular como string
                                $celular = (string)($producto->cel_op_min ?: $producto->cel_rep_legal);
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

                                {{-- RAZÓN SOCIAL - MULTILÍNEA --}}
                                <td class="p-1 small"
                                style="min-width: 180px; max-width: 180px; white-space: normal; word-wrap: break-word;"
                                title="{{ $producto->razon_social }}{{ $producto->nit ? ' - NIT: ' . $producto->nit : '' }}{{ $producto->email_op_min ? ' - ' . $producto->email_op_min : '' }}">
                                <div style="max-height: 80px; overflow: hidden; line-height: 1.3;">
                                    <strong>{{ $producto->razon_social }}</strong>
                                </div>
                                @if($producto->nit)
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $producto->nit }}</small>
                                @endif
                                </td>
                                {{-- REPRESENTANTE - COMPACTO --}}
                                <td class="p-1 small" style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                    title="{{ $producto->nombre_rep_legal }}{{ $producto->ci_rep_legal ? ' - CI: ' . $producto->ci_rep_legal : '' }}">
                                    {{ Str::limit($producto->nombre_rep_legal, 20) }}
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
                                    <div class="d-flex flex-nowrap gap-1 justify-content-center">
                                        <!-- Botón Email (AMARILLO con texto) -->
                                        <button type="button"
                                                class="btn btn-warning btn-sm px-2 py-1 btn-notificar-email"
                                                data-id="{{ $producto->id_operador_minero }}"
                                                data-operador="{{ $producto->razon_social }}"
                                                data-email="{{ $producto->email_op_min }}"
                                                title="Enviar notificación por Email"
                                                style="min-width: 75px; font-size: 0.8rem;">
                                            <i class="fas fa-envelope me-1"></i>
                                            <span class="d-none d-md-inline">Email</span>
                                        </button>

                                        <!-- Botón WhatsApp (VERDE con texto) -->
                                        <button type="button"
                                                class="btn btn-success btn-sm px-2 py-1 btn-notificar-whatsapp"
                                                data-id="{{ $producto->id_operador_minero }}"
                                                data-operador="{{ $producto->razon_social }}"
                                                data-celular="{{ $celular }}"
                                                title="Enviar notificación por WhatsApp"
                                                style="min-width: 95px; font-size: 0.8rem;">
                                            <i class="fab fa-whatsapp me-1"></i>
                                            <span class="d-none d-md-inline">WhatsApp</span>
                                        </button>
                                    </div>
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

<!-- Modal para WhatsApp -->
<div class="modal fade" id="modalWhatsApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);">
                <h5 class="modal-title text-white">
                    <i class="fab fa-whatsapp me-2"></i>Enviar por WhatsApp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fab fa-whatsapp text-success fs-1"></i>
                        </div>
                        <div>
                            <h6 class="mb-1" id="whatsappOperador"></h6>
                            <p class="mb-0 text-muted small" id="whatsappNumero"></p>
                        </div>
                    </div>

                    <div class="alert alert-info border-info bg-info bg-opacity-10">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <small>Se abrirá WhatsApp Web o la aplicación instalada. El mensaje está listo para enviar.</small>
                    </div>

                    <label class="form-label fw-bold">Mensaje:</label>
                    <textarea class="form-control font-monospace"
                              id="whatsappMensaje"
                              rows="12"
                              readonly
                              style="font-size: 0.9rem; background-color: #f8f9fa;"></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-grow-1" onclick="copiarMensajeWhatsApp()">
                        <i class="bi bi-clipboard me-1"></i> Copiar mensaje
                    </button>
                    <button type="button" class="btn btn-success flex-grow-1" id="btnAbrirWhatsApp">
                        <i class="fab fa-whatsapp me-1"></i> Abrir WhatsApp
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>

                </div>

                <div class="alert alert-success mt-3 d-none" id="alertCopiado">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    ¡Mensaje copiado! Ahora puedes pegarlo en WhatsApp.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Email -->
<div class="modal fade" id="modalEmail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-envelope me-2"></i>Enviar por Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-envelope text-warning fs-1"></i>
                    </div>
                    <h5 class="text-warning mb-3" id="emailOperador">¿Enviar notificación?</h5>

                    <div class="alert alert-info border-info border-2 bg-info bg-opacity-10">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-4 me-2 text-info"></i>
                            <div class="text-start">
                                <small class="fw-medium">Se enviará una notificación al operador</small>
                                <p class="mb-0 small" id="emailDestino"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="btnConfirmarEmail">
                    <i class="fas fa-paper-plane me-1"></i> Enviar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Tabs personalizados */
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

/* Botones de acción mejorados */
.btn-warning {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    color: #6c2300 !important;
    border: none;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #FFED4E 0%, #FFB347 100%);
    color: #6c2300 !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.btn-success {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    color: white !important;
    border: none;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.btn-success:hover {
    background: linear-gradient(135deg, #2FE579 0%, #16A085 100%);
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Responsive para botones */
@media (max-width: 768px) {
    .btn-warning span, .btn-success span {
        display: none !important;
    }
    .btn-warning, .btn-success {
        min-width: 40px !important;
        padding: 6px 8px !important;
    }
    .btn-warning i, .btn-success i {
        margin-right: 0 !important;
    }
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

/* Animación para alertas */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Alertas flotantes */
.alert-flotante {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    animation: slideInRight 0.3s ease-out;
}

/* Espaciado entre botones */
.d-flex.gap-1 {
    gap: 4px !important;
}
</style>

@push('scripts')
<script>
    window.APP = {
        baseUrl: "{{ url('') }}"
    };
$(document).ready(function() {
    console.log('Documento cargado, inicializando eventos...');

    // Email - Evento para botones de email
    $(document).on('click', '.btn-notificar-email', function() {
        const id = $(this).data('id');
        const operador = $(this).data('operador');
        const email = $(this).data('email');

        console.log('Email clickeado:', { id, operador, email });

        $('#emailOperador').text(`¿Enviar notificación a ${operador}?`);
        $('#emailDestino').html(`<strong>Destino:</strong> ${email || '<span class="text-danger">No tiene email</span>'}`);

        // Configurar el botón de confirmación
        $('#btnConfirmarEmail').off('click').on('click', function() {
            enviarNotificacionEmail(id);
        });

        $('#modalEmail').modal('show');
    });

    // WhatsApp - Evento para botones de WhatsApp
    $(document).on('click', '.btn-notificar-whatsapp', function() {
        const id = $(this).data('id');
        const operador = $(this).data('operador');
        const celular = $(this).data('celular');

        console.log('WhatsApp clickeado:', { id, operador, celular });

        // Mostrar loading
        $('#whatsappOperador').html('<span class="spinner-border spinner-border-sm me-2"></span>Cargando mensaje...');
        $('#whatsappNumero').text(`Número: ${celular || 'No disponible'}`);
        $('#whatsappMensaje').val('');
        $('#modalWhatsApp').modal('show');

        // Obtener mensaje del servidor
        $.ajax({

            //url: `/operadores/${id}/whatsapp-mensaje`,
            url: `${window.APP.baseUrl}/operadores/${id}/whatsapp-mensaje`,


            method: 'GET',
            success: function(response) {
                console.log('Respuesta WhatsApp:', response);

                if (response.success) {
                    $('#whatsappOperador').text(`Operador: ${response.operador}`);
                    $('#whatsappNumero').text(`Número: ${response.celular_original || celular || 'No disponible'}`);
                    $('#whatsappMensaje').val(response.mensaje);

                    // Configurar botón para abrir WhatsApp
                    $('#btnAbrirWhatsApp').off('click').on('click', function() {
                        abrirWhatsApp(response.numero, response.mensaje, id);
                    });
                } else {
                    $('#modalWhatsApp').modal('hide');
                    mostrarAlerta('❌ ' + response.message, 'danger');
                }
            },
            error: function(error) {
                console.error('Error en WhatsApp:', error);
                $('#modalWhatsApp').modal('hide');

                let mensaje = 'Error al cargar mensaje de WhatsApp';
                if (error.responseJSON && error.responseJSON.message) {
                    mensaje = error.responseJSON.message;
                }

                mostrarAlerta('❌ ' + mensaje, 'danger');
            }
        });
    });

    // Auto-submit filtros
    $('select[name="sort"]').on('change', function() {
        $('#formFiltros').submit();
    });
});

// Función para enviar notificación por email
function enviarNotificacionEmail(id) {
    console.log('Enviando email para ID:', id);

    const btn = $('#btnConfirmarEmail');
    btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Enviando...');
    btn.prop('disabled', true);

    $.ajax({
        url: `${window.APP.baseUrl}/operadores/${id}/notificar-email`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Email enviado:', response);

            if (response.success) {
                mostrarAlerta('✅ ' + response.message, 'success');
            } else {
                mostrarAlerta('❌ ' + response.message, 'danger');
            }

            $('#modalEmail').modal('hide');
            btn.html('<i class="fas fa-paper-plane me-1"></i> Enviar');
            btn.prop('disabled', false);
        },
        error: function(error) {
            console.error('Error en email:', error);

            let mensaje = 'Error al enviar email';
            if (error.responseJSON && error.responseJSON.message) {
                mensaje = error.responseJSON.message;
            }

            mostrarAlerta('❌ ' + mensaje, 'danger');
            btn.html('<i class="fas fa-paper-plane me-1"></i> Enviar');
            btn.prop('disabled', false);
        }
    });
}

function abrirWhatsApp(numero, mensaje, id) {
    if (!numero) {
        mostrarAlerta('❌ No hay número válido', 'danger');
        return;
    }

    const numeroLimpio = String(numero).replace(/\D/g, '');
    if (numeroLimpio.length < 8) {
        mostrarAlerta('❌ Número inválido', 'danger');
        return;
    }

    const mensajeCodificado = encodeURIComponent(mensaje);
    const url = `https://wa.me/${numeroLimpio}?text=${mensajeCodificado}`;

    // Primero registrar, luego abrir WhatsApp
    registrarWhatsApp(id, numeroLimpio, 'abrir');

    window.open(url, '_blank', 'noopener,noreferrer');

    $('#modalWhatsApp').modal('hide');
}


// Función para copiar mensaje de WhatsApp
function copiarMensajeWhatsApp() {
    const mensaje = $('#whatsappMensaje').val();
    const id = $('.btn-notificar-whatsapp').data('id');
    const numero = $('#whatsappNumero').text().replace(/\D/g, '');

    if (!mensaje) {
        mostrarAlerta('❌ No hay mensaje para copiar', 'danger');
        return;
    }

    navigator.clipboard.writeText(mensaje).then(() => {
        mostrarAlerta(
            '📌 Mensaje copiado. La notificación será registrada en el sistema.',
            'info'
        );

        // 👉 REGISTRAR ENVÍO
        registrarWhatsApp(id, numero, 'copiado');
    });
}


function registrarWhatsApp(id, numero, accion) {
    // Obtener el token CSRF desde el meta tag
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (!csrfToken) {
        console.error('No se encontró el token CSRF');
        mostrarAlerta('❌ Error de seguridad. Recargue la página.', 'danger');
        return;
    }

    $.ajax({
        url: `${window.APP.baseUrl}/operadores/${id}/registrar-whatsapp`,
        method: 'POST',
        data: {
            _token: csrfToken,
            numero: numero,
            accion: accion
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken  // También envía en los headers por si acaso
        },
        success: function (res) {
            if (res.success) {
                console.log('WhatsApp registrado:', res);
                // No mostrar alerta aquí para evitar spam, solo en caso de éxito
            } else {
                console.warn('WhatsApp no registrado:', res.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error registrando WhatsApp:', error);
            if (xhr.status === 419) {
                mostrarAlerta('❌ Error de sesión. Por favor, recargue la página.', 'danger');
            }
        }
    });
}


// Fallback para copiar mensaje
function copiarMensajeFallback(mensaje) {
    const textarea = document.createElement('textarea');
    textarea.value = mensaje;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();

    try {
        const copiado = document.execCommand('copy');
        if (copiado) {
            mostrarAlerta('✅ Mensaje copiado al portapapeles', 'success');
        } else {
            mostrarAlerta('❌ No se pudo copiar el mensaje', 'danger');
        }
    } catch (err) {
        console.error('Error al copiar (fallback):', err);
        mostrarAlerta('❌ Error al copiar el mensaje', 'danger');
    }

    document.body.removeChild(textarea);
}

// Función para mostrar alertas flotantes
function mostrarAlerta(mensaje, tipo) {
    const alertId = 'alert-' + Date.now();
    const icono = tipo === 'success' ? 'bi-check-circle' :
                 tipo === 'warning' ? 'bi-exclamation-triangle' :
                 tipo === 'info' ? 'bi-info-circle' : 'bi-x-circle';

    const alerta = `
        <div id="${alertId}" class="alert alert-${tipo} alert-dismissible fade show alert-flotante" role="alert">
            <i class="bi ${icono} me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Agregar alerta al body
    $('body').append(alerta);

    // Auto-remover después de 5 segundos
    setTimeout(() => {
        const alertaElemento = document.getElementById(alertId);
        if (alertaElemento) {
            $(alertaElemento).alert('close');
        }
    }, 5000);
}

// Función para mostrar alerta de copiado en modal de WhatsApp
function mostrarAlertaCopiado() {
    $('#alertCopiado').removeClass('d-none').fadeIn();
    setTimeout(() => {
        $('#alertCopiado').fadeOut();
    }, 3000);
}

// Sobrescribir la función de copiar para usar la nueva
function copiarMensajeWhatsApp() {
    const mensaje = $('#whatsappMensaje').val();
    const numero = $('#whatsappNumero').text().replace('Número: ', '').replace(/\D/g, '');

    // Obtener el ID del botón clickeado
    const id = $('.btn-notificar-whatsapp').data('id');

    if (!mensaje) {
        mostrarAlerta('❌ No hay mensaje para copiar', 'danger');
        return;
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(mensaje).then(() => {
            mostrarAlerta('✅ Mensaje copiado', 'success');

            // Registrar después de copiar
            if (id && numero) {
                registrarWhatsApp(id, numero, 'copiado');
            }
        }).catch(err => {
            console.error('Error al copiar:', err);
            copiarMensajeFallback(mensaje);
        });
    } else {
        copiarMensajeFallback(mensaje);
    }
}
</script>
@endpush
@endsection
