@extends('layouts.app')

@section('title', 'Gestión de Bloqueos de Operadores')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>Gestión de Bloqueos de Operadores
                    </h4>
                    <a href="{{ route('bloqueo-operadors.create') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo Bloqueo/Desbloqueo
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select class="form-select" id="filterEstado">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activos</option>
                                <option value="bloqueado">Bloqueados</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="filterFecha" placeholder="Filtrar por fecha">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterOperador">
                                <option value="">Todos los operadores</option>
                                @foreach($operadores as $operador)
                                <option value="{{ $operador->id_operador_minero }}">
                                    {{ $operador->razon_social }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="filterMotivo" placeholder="Buscar por motivo...">
                        </div>
                    </div>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="bloqueosTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Operador Minero</th>
                                    <th>Estado</th>
                                    <th>Motivo</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bloqueos as $bloqueo)
                                <tr>
                                    <td>{{ $bloqueo->id }}</td>
                                    <td>
                                        <strong>{{ $bloqueo->razon_social ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $bloqueo->nro_doc ?? '' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $estadoColor = $bloqueo->estado === 'bloqueado' ? 'danger' : 'success';
                                            $estadoIcono = $bloqueo->estado === 'bloqueado' ? 'bi-lock-fill' : 'bi-unlock-fill';
                                            $estadoTexto = $bloqueo->estado === 'bloqueado' ? 'BLOQUEADO' : 'ACTIVO';
                                        @endphp
                                        <span class="badge bg-{{ $estadoColor }}">
                                            <i class="bi {{ $estadoIcono }} me-1"></i>
                                            {{ $estadoTexto }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;"
                                             title="{{ $bloqueo->motivo }}">
                                            {{ $bloqueo->motivo }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($bloqueo->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('bloqueo-operadors.show', $bloqueo->id) }}"
                                               class="btn btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('bloqueo-operadors.edit', $bloqueo->id) }}"
                                               class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('bloqueo-operadors.destroy', $bloqueo->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        title="Eliminar"
                                                        onclick="return confirm('¿Está seguro de eliminar este registro?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $bloqueos->links() }}
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        Total registros: {{ $bloqueos->total() }} |
                        Mostrando: {{ $bloqueos->firstItem() ?? 0 }} - {{ $bloqueos->lastItem() ?? 0 }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filtros en tiempo real
    $('#filterEstado, #filterFecha, #filterOperador, #filterMotivo').on('change keyup', function() {
        let estado = $('#filterEstado').val();
        let fecha = $('#filterFecha').val();
        let operadorId = $('#filterOperador').val();
        let motivo = $('#filterMotivo').val().toLowerCase();

        $('#bloqueosTable tbody tr').each(function() {
            let row = $(this);
            let rowEstado = row.find('td:nth-child(3) .badge').text().toLowerCase().includes('bloqueado') ? 'bloqueado' : 'activo';
            let rowFecha = row.find('td:nth-child(5)').text().trim();
            let rowMotivo = row.find('td:nth-child(4)').text().toLowerCase();

            let show = true;

            if (estado && rowEstado !== estado) show = false;
            if (fecha) {
                let fechaFiltro = new Date(fecha).toLocaleDateString('es-ES');
                if (rowFecha !== fechaFiltro) show = false;
            }
            if (operadorId) {
                // Buscar en el texto del operador
                let rowOperadorText = row.find('td:nth-child(2)').text().toLowerCase();
                let selectedOption = $('#filterOperador option:selected').text().toLowerCase();
                if (!rowOperadorText.includes(selectedOption)) show = false;
            }
            if (motivo && !rowMotivo.includes(motivo)) show = false;

            show ? row.show() : row.hide();
        });
    });
});
</script>
@endpush
