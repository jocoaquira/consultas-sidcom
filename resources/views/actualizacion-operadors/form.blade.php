<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $title }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $action }}">
            @csrf
            @if(isset($actualizacionOperador))
                @method('PUT')
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="operador_minero_id" class="form-label">Operador Minero *</label>
                    <select name="operador_minero_id" id="operador_minero_id" class="form-select" required>
                        <option value="">Seleccione un operador</option>
                        @foreach($operadores as $operador)
                            <option value="{{ $operador->id_operador_minero }}"
                                {{ (isset($actualizacionOperador) && $actualizacionOperador->operador_minero_id == $operador->id_operador_minero) ||
                                   old('operador_minero_id') == $operador->id_operador_minero ? 'selected' : '' }}>
                                {{ $operador->razon_social }} - NIT: {{ $operador->nit }}
                            </option>
                        @endforeach
                    </select>
                    @error('operador_minero_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="fecha" class="form-label">Fecha *</label>
                    <input type="date" name="fecha" id="fecha" class="form-control"
                           value="{{ isset($actualizacionOperador) ? $actualizacionOperador->fecha->format('Y-m-d') : old('fecha', date('Y-m-d')) }}"
                           required>
                    @error('fecha')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Tipos de Actualización *</label>
                    <div class="border rounded p-3">
                        <div class="row">
                            @foreach($tipos as $key => $value)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="tipos[]"
                                           value="{{ $key }}"
                                           id="tipo_{{ $key }}"
                                           {{ (isset($tiposSeleccionados) && in_array($key, $tiposSeleccionados)) ||
                                              (is_array(old('tipos')) && in_array($key, old('tipos'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipo_{{ $key }}">
                                        {{ $value }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('tipos')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @error('tipos.*')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control"
                              rows="3">{{ isset($actualizacionOperador) ? $actualizacionOperador->observaciones : old('observaciones') }}</textarea>
                    @error('observaciones')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ isset($actualizacionOperador) ? 'Actualizar' : 'Guardar' }}
                </button>
                <a href="{{ route('actualizacion-operadors.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validar que al menos un checkbox esté seleccionado
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('input[name="tipos[]"]:checked');
        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Por favor, seleccione al menos un tipo de actualización.');
            return false;
        }
    });

    // Manejar checkbox "OTROS"
    const otrosCheckbox = document.getElementById('tipo_OTROS');
    if (otrosCheckbox) {
        otrosCheckbox.addEventListener('change', function() {
            const observaciones = document.getElementById('observaciones');
            if (this.checked) {
                // Si ya hay observaciones, agregar "OTROS: " si no está
                if (!observaciones.value.includes('OTROS:')) {
                    observaciones.value = 'OTROS: ' + observaciones.value;
                }
            }
        });
    }
});
</script>
@endpush
