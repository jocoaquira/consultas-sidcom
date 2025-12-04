@extends('layouts.app')

@section('title', 'Actualizaciones de Operadores')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Actualizaciones de Operadores Mineros</h5>
            <a href="{{ route('actualizacion-operadors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Actualización
            </a>
        </div>

        <div class="card-body">
            @if($actualizaciones->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No hay actualizaciones registradas</h5>
                    <p class="text-muted">Comienza creando tu primera actualización</p>
                    <a href="{{ route('actualizacion-operadors.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Crear Actualización
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Operador</th>
                                <th>Tipos</th>
                                <th>Fecha</th>
                                <th>Observaciones</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($actualizaciones as $actualizacion)
                            <tr>
                                <td>{{ $actualizacion->id }}</td>
                                <td>
                                    @if($actualizacion->razon_social) {{-- CAMBIADO --}}
                                        <strong>{{ $actualizacion->razon_social }}</strong>
                                        <br>
                                        <small class="text-muted">NIT: {{ $actualizacion->nit }}</small>
                                    @else
                                        <span class="text-danger">Operador no encontrado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($actualizacion->tipo_actualizacion)
                                        {{ $actualizacion->tipo_actualizacion }}
                                    @else
                                        <span class="text-muted">Sin tipo</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- CAMBIADO: Usar Carbon directamente --}}
                                    @if($actualizacion->fecha)
                                        {{ \Carbon\Carbon::parse($actualizacion->fecha)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Sin fecha</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($actualizacion->observaciones, 50) }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        {{-- CAMBIADO: Pasar ID en lugar de objeto --}}
                                        <a href="{{ route('actualizacion-operadors.show', $actualizacion->id) }}"
                                           class="btn btn-outline-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('actualizacion-operadors.edit', $actualizacion->id) }}"
                                           class="btn btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('actualizacion-operadors.destroy', $actualizacion->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"
                                                    onclick="return confirm('¿Eliminar esta actualización?')" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $actualizaciones->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
