@extends('layouts.app')

@section('title', 'Detalles del Bloqueo')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-eye-fill me-2"></i>Detalles del Registro
                    </h4>
                    <a href="{{ route('bloqueo-operadors.index') }}"
                       class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información principal -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0" style="background-color: rgba(139, 0, 0, 0.03);">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="bi bi-person-badge me-2"></i>Operador Minero
                                    </h6>
                                    <h5 class="mb-2" style="color: #8B0000;">
                                        {{ $bloqueo->razon_social ?? 'N/A' }}
                                    </h5>
                                    @if($bloqueo->operador_minero_id)
                                    <div class="d-flex align-items-center mt-2">
                                        <i class="bi bi-tag text-muted me-2"></i>
                                        <span class="text-muted">ID: SDMMRE-{{ $bloqueo->operador_minero_id }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0" style="background-color: rgba(139, 0, 0, 0.03);">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="bi bi-toggles me-2"></i>Estado
                                    </h6>
                                    @php
                                        $estadoColor = $bloqueo->estado === 'bloqueado' ? 'danger' : 'success';
                                        $estadoIcono = $bloqueo->estado === 'bloqueado' ? 'bi-lock-fill' : 'bi-unlock-fill';
                                        $estadoTexto = $bloqueo->estado === 'bloqueado' ? 'BLOQUEADO' : 'ACTIVO';
                                    @endphp
                                    <span class="badge bg-{{ $estadoColor }}-subtle
                                                  text-{{ $estadoColor }}
                                                  border border-{{ $estadoColor }}-subtle
                                                  px-4 py-3 fs-6">
                                        <i class="bi {{ $estadoIcono }} me-2"></i>
                                        {{ $estadoTexto }}
                                    </span>
                                    <p class="text-muted small mt-2 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        {{ $bloqueo->estado === 'bloqueado' ? 'Operador bloqueado en el sistema' : 'Operador activo en el sistema' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0" style="background-color: rgba(139, 0, 0, 0.03);">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="bi bi-calendar-date me-2"></i>Fecha
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                            <i class="bi bi-calendar3 text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0" style="color: #8B0000;">
                                                {{ \Carbon\Carbon::parse($bloqueo->fecha)->format('d/m/Y') }}
                                            </h5>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($bloqueo->fecha)->isoFormat('dddd') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0" style="background-color: rgba(139, 0, 0, 0.03);">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="bi bi-person-circle me-2"></i>Registro
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                            <i class="bi bi-person-check text-info fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1" style="color: #333;">{{ $bloqueo->usuario_registro ?? 'Sistema' }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($bloqueo->created_at)->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Motivo -->
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header" style="background-color: rgba(139, 0, 0, 0.05);">
                                    <h6 class="mb-0" style="color: #8B0000;">
                                        <i class="bi bi-chat-left-text me-2"></i>Motivo del {{ $bloqueo->estado === 'bloqueado' ? 'Bloqueo' : 'Desbloqueo' }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="bg-light p-4 rounded">
                                        <p class="mb-0" style="line-height: 1.8; font-size: 1.05rem;">
                                            {{ $bloqueo->motivo }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del sistema -->
                        <div class="col-12">
                            <div class="card border-0" style="background-color: rgba(139, 0, 0, 0.03);">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="bi bi-info-circle me-2"></i>Información del Sistema
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                                    <i class="bi bi-calendar-plus text-success"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Creado</small>
                                                    <span style="color: #8B0000;">
                                                        {{ \Carbon\Carbon::parse($bloqueo->created_at)->format('d/m/Y H:i:s') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                                    <i class="bi bi-calendar-check text-warning"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Actualizado</small>
                                                    <span style="color: #8B0000;">
                                                        {{ \Carbon\Carbon::parse($bloqueo->updated_at)->format('d/m/Y H:i:s') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0" style="background-color: rgba(139, 0, 0, 0.03);">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bloqueo-operadors.edit', $bloqueo->id) }}"
                           class="btn d-flex align-items-center"
                           style="background: linear-gradient(to right, #FFD700, #FFA500);
                                  color: #8B0000;
                                  border: none;
                                  font-weight: 600;
                                  padding: 0.5rem 1.5rem;
                                  border-radius: 8px;
                                  transition: all 0.3s ease;
                                  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);">
                            <i class="bi bi-pencil-fill me-2"></i>
                            <span>Editar Registro</span>
                        </a>

                        <div class="btn-group">
                            <a href="{{ route('bloqueo-operadors.index') }}"
                               class="btn btn-outline-secondary">
                                <i class="bi bi-list-ul me-1"></i> Ver Todos
                            </a>
                            <!-- Si tienes ruta de historial -->
                            <!--
                            <a href="{{ route('bloqueo-operadors.historial', $bloqueo->operador_minero_id) }}"
                               class="btn btn-outline-info">
                                <i class="bi bi-clock-history me-1"></i> Historial
                            </a>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(139, 0, 0, 0.1);
    }

    .badge {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Efecto hover para el botón editar */
    .card-footer .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        background: linear-gradient(to right, #FFED4E, #FFB347) !important;
        color: #8B0000 !important;
    }

    /* Estilos para el texto del motivo */
    .bg-light p {
        white-space: pre-line;
    }
</style>
@endpush
