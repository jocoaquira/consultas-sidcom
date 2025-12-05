@extends('layouts.app')

@section('title', 'Operadores Mineros')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <h5 class="mb-0">
                <i class="fas fa-box me-2"></i>Operadores Mineros
            </h5>
            <div>
                <span class="badge bg-primary fs-6">{{ $productos->total() }} registros</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($productos->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>No hay operadores registrados</h5>
                </div>
            @else
                {{-- Tabla con scroll --}}
                <div class="table-container" style="max-height: calc(100vh - 200px); overflow: auto;">
                    <table class="table table-bordered table-striped table-hover mb-0">
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
                        <tbody>
                            @foreach($productos as $producto)
                            <tr>
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

                                {{-- OBSERVACIONES (con salto de línea) --}}
                                <td class="p-2" style="min-width: 200px; max-width: 300px;">
                                    @if($producto->observaciones)
                                        <div class="observaciones-cell"
                                             style="word-wrap: break-word; white-space: pre-line; max-height: 100px; overflow-y: auto;">
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

                                {{-- NOTIFICACIÓN (botón mejorado) --}}
                                <td class="text-center p-2">
                                    <form action="{{ route('operadores.notificar', $producto->id_operador_minero) }}"
                                          method="POST"
                                          class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-warning btn-sm d-flex align-items-center"
                                                onclick="return confirm('¿Enviar notificación a {{ $producto->razon_social }}?')"
                                                title="Enviar notificación">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            <span class="d-none d-md-inline">Notificar</span>
                                        </button>
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
    color: #000;
    font-weight: 500;
}

/* Paginación */
.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.pagination .page-link {
    color: #0d6efd;
}
.pagination .page-link:hover {
    color: #0a58ca;
    background-color: #e9ecef;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirmación de notificación
    document.querySelectorAll('form').forEach(function(form) {
        if (form.action.includes('notificar')) {
            form.addEventListener('submit', function(e) {
                if (!confirm('¿Está seguro de enviar notificación?')) {
                    e.preventDefault();
                }
            });
        }
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
