@extends('layouts.app')

@section('title', 'Ver Actualización')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Actualización #{{ $actualizacionOperador->id }}</h5>
            <div>
                <a href="{{ route('actualizacion-operadors.edit', $actualizacionOperador) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="{{ route('actualizacion-operadors.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Información de la Actualización</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">ID:</th>
                            <td>{{ $actualizacionOperador->id }}</td>
                        </tr>
                        <tr>
                            <th>Tipos:</th>
                            <td>{{ $actualizacionOperador->tipo_actualizacion }}</td>
                        </tr>
                        <tr>
                            <th>Fecha:</th>
                            <td>{{ $actualizacionOperador->fecha->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td>{{ $actualizacionOperador->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td>{{ $actualizacionOperador->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    <h6 class="mt-4">Observaciones</h6>
                    <div class="border p-3 bg-light rounded">
                        {{ $actualizacionOperador->observaciones ?? 'Sin observaciones' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <h6>Información del Operador</h6>
                    @if($actualizacionOperador->operadorMinero)
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Operador:</th>
                            <td>{{ $actualizacionOperador->operadorMinero->razon_social }}</td>
                        </tr>
                        <tr>
                            <th>NIT:</th>
                            <td>{{ $actualizacionOperador->operadorMinero->nit }}</td>
                        </tr>
                        <tr>
                            <th>NIM:</th>
                            <td>{{ $actualizacionOperador->operadorMinero->nim }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @if($actualizacionOperador->operadorMinero->estado_operador_minero)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha Creación:</th>
                            <td>
                                @if($actualizacionOperador->operadorMinero->fecha_creacion)
                                    {{ \Carbon\Carbon::parse($actualizacionOperador->operadorMinero->fecha_creacion)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Operador no encontrado o eliminado
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
