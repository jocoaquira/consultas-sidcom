@extends('layouts.app')

@section('title', 'Editar Bloqueo/Desbloqueo de Operador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil me-2"></i>Editar Registro de Bloqueo/Desbloqueo
                    </h4>
                    <a href="{{ route('bloqueo-operadors.index') }}"
                       class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('bloqueo-operadors.update', $bloqueo->id) }}" method="POST" id="formBloqueo">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Operador Minero - CON SELECT Y BÚSQUEDA -->
                            <div class="col-md-12">
                                <label for="operador_minero_id" class="form-label fw-medium" style="color: #8B0000;">
                                    <i class="bi bi-person-badge me-1"></i>Operador Minero *
                                </label>

                                <!-- Input de búsqueda -->
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           id="searchOperador"
                                           placeholder="Escriba para filtrar operadores..."
                                           autocomplete="off">
                                </div>

                                <!-- Select normal pero con clase para filtrar -->
                                <select class="form-select @error('operador_minero_id') is-invalid @enderror"
                                        name="operador_minero_id"
                                        id="operador_minero_id"
                                        size="8"
                                        style="height: auto;"
                                        required>
                                    <option value="">-- Seleccione un operador --</option>
                                    @foreach($operadores as $operador)
                                    <option value="{{ $operador->id_operador_minero }}"
                                            data-search="{{ strtolower($operador->razon_social) }}"
                                            {{ $bloqueo->operador_minero_id == $operador->id_operador_minero ? 'selected' : '' }}>
                                        {{ $operador->razon_social }}
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Mostrar operador seleccionado -->
                                <div id="selectedOperador" class="mt-2 {{ $bloqueo->operador_minero_id ? '' : 'd-none' }}">
                                    <div class="alert alert-success py-2 mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="bi bi-check-circle-fill me-2"></i>
                                                <strong id="selectedOperadorText">
                                                    @php
                                                        $currentOperador = $operadores->firstWhere('id_operador_minero', $bloqueo->operador_minero_id);
                                                    @endphp
                                                    {{ $currentOperador->razon_social ?? '' }}
                                                </strong>
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="clearOperadorSelection()">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @error('operador_minero_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Escriba arriba para filtrar y seleccione en la lista
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label for="estado" class="form-label fw-medium" style="color: #8B0000;">
                                    <i class="bi bi-toggles me-1"></i>Acción *
                                </label>
                                <select class="form-select @error('estado') is-invalid @enderror"
                                        name="estado" id="estado" required>
                                    <option value="">Seleccionar acción...</option>
                                    <option value="activo" {{ $bloqueo->estado == 'activo' ? 'selected' : '' }}>
                                        <i class="bi bi-unlock-fill me-1 text-success"></i> Activar / Desbloquear
                                    </option>
                                    <option value="bloqueado" {{ $bloqueo->estado == 'bloqueado' ? 'selected' : '' }}>
                                        <i class="bi bi-lock-fill me-1 text-danger"></i> Bloquear
                                    </option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-6">
                                <label for="fecha" class="form-label fw-medium" style="color: #8B0000;">
                                    <i class="bi bi-calendar-date me-1"></i>Fecha *
                                </label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror"
                                       name="fecha" id="fecha"
                                       value="{{ old('fecha', $bloqueo->fecha) }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Motivo -->
                            <div class="col-md-12">
                                <label for="motivo" class="form-label fw-medium" style="color: #8B0000;">
                                    <i class="bi bi-chat-left-text me-1"></i>Motivo *
                                </label>
                                <textarea class="form-control @error('motivo') is-invalid @enderror"
                                          name="motivo" id="motivo" rows="4"
                                          placeholder="Describa el motivo del bloqueo/desbloqueo..."
                                          required>{{ old('motivo', $bloqueo->motivo) }}</textarea>
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Mínimo 10 caracteres
                                </div>
                                @error('motivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('bloqueo-operadors.index') }}"
                                       class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Cancelar
                                    </a>
                                    <button type="submit"
                                            class="btn d-flex align-items-center"
                                            style="background: linear-gradient(to right, #FFD700, #FFA500);
                                                   color: #8B0000;
                                                   border: none;
                                                   font-weight: 600;
                                                   padding: 0.5rem 1.5rem;
                                                   border-radius: 8px;
                                                   transition: all 0.3s ease;
                                                   box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);">
                                        <i class="bi bi-save-fill me-2"></i>
                                        <span>Actualizar Registro</span>
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

@push('styles')
<style>
    .form-select:focus, .form-control:focus, textarea:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        background: linear-gradient(to right, #FFED4E, #FFB347) !important;
        color: #8B0000 !important;
    }

    .card-header .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border-color: white;
    }

    /* Estilo para el select */
    #operador_minero_id {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem;
        background-color: white;
    }

    #operador_minero_id option {
        padding: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }

    #operador_minero_id option:last-child {
        border-bottom: none;
    }

    #operador_minero_id option:hover {
        background-color: rgba(139, 0, 0, 0.1);
    }

    #operador_minero_id option:checked {
        background-color: rgba(139, 0, 0, 0.2);
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
// Filtrar opciones del select mientras se escribe
document.getElementById('searchOperador').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const select = document.getElementById('operador_minero_id');
    const options = select.querySelectorAll('option');

    // Mostrar todas las opciones primero
    options.forEach(option => {
        option.style.display = 'block';
    });

    // Ocultar las que no coinciden (excepto la primera opción vacía)
    if (searchTerm.trim() !== '') {
        options.forEach(option => {
            if (option.value !== '') {
                const searchText = option.getAttribute('data-search') || option.textContent.toLowerCase();
                if (!searchText.includes(searchTerm)) {
                    option.style.display = 'none';
                }
            }
        });
    }
});

// Mostrar operador seleccionado en el cuadro verde
document.getElementById('operador_minero_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];

    if (selectedOption.value !== '') {
        // Mostrar el cuadro con el operador seleccionado
        document.getElementById('selectedOperadorText').textContent = selectedOption.textContent;
        document.getElementById('selectedOperador').classList.remove('d-none');

        // Limpiar el campo de búsqueda
        document.getElementById('searchOperador').value = '';

        // Mostrar todas las opciones nuevamente
        const options = this.querySelectorAll('option');
        options.forEach(option => {
            option.style.display = 'block';
        });
    } else {
        // Si se selecciona la opción vacía, ocultar el cuadro
        document.getElementById('selectedOperador').classList.add('d-none');
    }
});

// Función para limpiar la selección
function clearOperadorSelection() {
    const select = document.getElementById('operador_minero_id');
    select.value = '';
    document.getElementById('selectedOperador').classList.add('d-none');
    document.getElementById('searchOperador').value = '';
    document.getElementById('searchOperador').focus();

    // Mostrar todas las opciones
    const options = select.querySelectorAll('option');
    options.forEach(option => {
        option.style.display = 'block';
    });
}

// Validar formulario
document.getElementById('formBloqueo').addEventListener('submit', function(e) {
    // Validar operador seleccionado
    const operadorSelect = document.getElementById('operador_minero_id');
    if (!operadorSelect.value) {
        e.preventDefault();
        alert('Por favor, seleccione un operador minero');
        document.getElementById('searchOperador').focus();
        return false;
    }

    // Validar motivo mínimo 10 caracteres
    const motivo = document.getElementById('motivo').value.trim();
    if (motivo.length < 10) {
        e.preventDefault();
        alert('El motivo debe tener al menos 10 caracteres');
        document.getElementById('motivo').focus();
        return false;
    }

    // Validar fecha no futura
    const fecha = document.getElementById('fecha').value;
    const hoy = new Date().toISOString().split('T')[0];
    if (fecha > hoy) {
        e.preventDefault();
        alert('La fecha no puede ser futura');
        document.getElementById('fecha').focus();
        return false;
    }
});

// Setear fecha máxima como hoy
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').setAttribute('max', today);
});
</script>
@endpush
