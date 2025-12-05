@extends('layouts.app')

@section('title', 'Detalles del Bloqueo')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-eye me-2"></i>Detalles del Registro
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información principal -->
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Operador Minero</h6>
                            <p class="h5">
                                <strong>{{ $bloqueoOperador->operador->nombre_razon_social ?? 'N/A' }}</strong>
                            </p>
                            <p class="text-muted mb-0">
                                {{ $bloqueoOperador->operador->nro_doc ?? '' }}
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Estado</h6>
                            <p>
                                <span class="badge bg-{{ $bloqueoOperador->estado_color }} fs-6">
                                    <i class="bi {{ $bloqueoOperador->estado_icono }} me-1"></i>
                                    {{ $bloqueoOperador->estado_formateado }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Fecha</h6>
                            <p class="h6">{{ $bloqueoOperador->fecha_formateada }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Registrado por</h6>
                            <p class="h6">{{ $bloqueoOperador->usuario_registro }}</p>
                        </div>

                        <!-- Motivo -->
                        <div class="col-12 mb-4">
                            <h6 class="text-muted">Motivo</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $bloqueoOperador->motivo }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        @if($bloqueoOperador->observaciones)
                        <div class="col-12 mb-4">
                            <h6 class="text-muted">Observaciones</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $bloqueoOperador->observaciones }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Información del sistema -->
                        <div class="col-12">
                            <hr>
                            <div class="row text-muted small">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <i class="bi bi-calendar-plus me-1"></i>
                                        Creado: {{ $bloqueoOperador->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Actualizado: {{ $bloqueoOperador->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bloqueo-operadors.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('bloqueo-operadors.edit', $bloqueoOperador) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i> Editar
                            </a>
                            <a href="{{ route('bloqueo-operadors.historial', $bloqueoOperador->operador_minero_id) }}"
                               class="btn btn-info">
                                <i class="bi bi-clock-history me-1"></i> Ver Historial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
