@extends('layouts.app')

@section('title', 'Nuevo Bloqueo/Desbloqueo de Operador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Registro de Bloqueo/Desbloqueo
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('bloqueo-operadors.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Operador Minero -->
                            <div class="col-md-12">
                                <label for="operador_minero_id" class="form-label">Operador Minero *</label>
                                <select class="form-select @error('operador_minero_id') is-invalid @enderror"
                                        name="operador_minero_id" id="operador_minero_id" required>
                                    <option value="">Seleccionar operador...</option>
                                    @foreach($operadores as $operador)
                                    <option value="{{ $operador->id_operador_minero }}"
                                            {{ old('operador_minero_id') == $operador->id_operador_minero ? 'selected' : '' }}>
                                        {{ $operador->razon_social }}
                                        ({{ $operador->nro_doc ?? 'Sin documento' }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('operador_minero_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label for="estado" class="form-label">Acción *</label>
                                <select class="form-select @error('estado') is-invalid @enderror"
                                        name="estado" id="estado" required>
                                    <option value="">Seleccionar acción...</option>
                                    <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>
                                        Activar / Desbloquear
                                    </option>
                                    <option value="bloqueado" {{ old('estado') == 'bloqueado' ? 'selected' : '' }}>
                                        Bloquear
                                    </option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-6">
                                <label for="fecha" class="form-label">Fecha *</label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror"
                                       name="fecha" id="fecha"
                                       value="{{ old('fecha', date('Y-m-d')) }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Motivo -->
                            <div class="col-md-12">
                                <label for="motivo" class="form-label">Motivo *</label>
                                <textarea class="form-control @error('motivo') is-invalid @enderror"
                                          name="motivo" id="motivo" rows="4"
                                          placeholder="Describa el motivo del bloqueo/desbloqueo..."
                                          required>{{ old('motivo') }}</textarea>
                                <div class="form-text">Mínimo 10 caracteres</div>
                                @error('motivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('bloqueo-operadors.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Guardar Registro
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Validar que se seleccionó un operador y un estado
    $('#operador_minero_id, #estado').change(function() {
        const operadorId = $('#operador_minero_id').val();
        const estado = $('#estado').val();

        if (operadorId && estado) {
            // Podrías agregar lógica adicional aquí si necesitas
            // Por ejemplo, verificar si el operador ya está bloqueado/activo
        }
    });

    // Setear fecha máxima como hoy
    const today = new Date().toISOString().split('T')[0];
    $('#fecha').attr('max', today);
});
</script>
@endpush
