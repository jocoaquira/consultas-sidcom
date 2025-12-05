@extends('layouts.app')

@section('title', 'Editar Actualización')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-2"
                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Editar Actualización
                    </h5>
                    <a href="{{ route('actualizacion-operadors.index') }}"
                       class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('actualizacion-operadors.update', $actualizacion->id) }}" id="formActualizacion">
                        @csrf
                        @method('PUT')

                        <div class="row g-2">
                            <!-- Operador Minero -->
                            <div class="col-12">
                                <label for="operador_minero_id" class="form-label fw-medium mb-1" style="color: #8B0000; font-size: 0.9rem;">
                                    <i class="bi bi-person-badge me-1"></i>Operador Minero *
                                </label>

                                <!-- Input de búsqueda -->
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text" style="background-color: #f8f9fa; padding: 0.25rem 0.5rem;">
                                        <i class="bi bi-search" style="color: #8B0000;"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           id="searchOperador"
                                           placeholder="Escriba para filtrar..."
                                           autocomplete="off"
                                           style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="clearSearch()"
                                            style="padding: 0.25rem 0.5rem; font-size: 0.85rem;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>

                                <!-- Select con búsqueda -->
                                <select class="form-select form-select-sm @error('operador_minero_id') is-invalid @enderror"
                                        name="operador_minero_id"
                                        id="operador_minero_id"
                                        size="6"
                                        style="height: auto; max-height: 150px; overflow-y: auto; font-size: 0.85rem; padding: 0.25rem 0.5rem;"
                                        required>
                                    <option value="">-- Seleccione un operador --</option>
                                    @foreach($operadores as $operador)
                                    <option value="{{ $operador->id_operador_minero }}"
                                            data-search="{{ strtolower($operador->razon_social . ' ' . $operador->nit) }}"
                                            {{ $actualizacion->operador_minero_id == $operador->id_operador_minero ? 'selected' : '' }}>
                                        {{ $operador->razon_social }}
                                        @if($operador->nit)
                                         - NIT: {{ $operador->nit }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Mostrar operador seleccionado -->
                                <div id="selectedOperador" class="mt-2 {{ $actualizacion->operador_minero_id ? '' : 'd-none' }}">
                                    <div class="alert alert-success py-1 px-2 mb-0 d-flex align-items-center"
                                         style="font-size: 0.8rem; min-height: 28px;">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <span class="text-truncate">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                @if($actualizacion->operador_minero_id)
                                                    @php
                                                        $operadorSeleccionado = $operadores->firstWhere('id_operador_minero', $actualizacion->operador_minero_id);
                                                    @endphp
                                                    <strong id="selectedOperadorText" style="font-size: 0.85rem;">
                                                        {{ $operadorSeleccionado->razon_social ?? '' }}
                                                        @if($operadorSeleccionado->nit ?? false)
                                                         - NIT: {{ $operadorSeleccionado->nit }}
                                                        @endif
                                                    </strong>
                                                @endif
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-danger p-0 ms-2"
                                                    style="width: 18px; height: 18px; font-size: 0.7rem; line-height: 1; flex-shrink: 0;"
                                                    onclick="clearOperadorSelection()">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @error('operador_minero_id')
                                    <div class="invalid-feedback d-block" style="font-size: 0.8rem;">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-info-circle me-1"></i> Escriba arriba para filtrar y seleccione en la lista
                                </small>
                            </div>

                            <!-- Fecha y Contador -->
                            <div class="col-12">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-6">
                                        <label for="fecha" class="form-label fw-medium mb-1" style="color: #8B0000; font-size: 0.9rem;">
                                            <i class="bi bi-calendar-date me-1"></i>Fecha *
                                        </label>
                                        <input type="date"
                                               name="fecha"
                                               id="fecha"
                                               class="form-control form-control-sm @error('fecha') is-invalid @enderror"
                                               value="{{ old('fecha', $actualizacion->fecha) }}"
                                               style="padding: 0.25rem 0.5rem; font-size: 0.85rem;"
                                               required>
                                        @error('fecha')
                                            <div class="invalid-feedback" style="font-size: 0.8rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium mb-1" style="color: #8B0000; font-size: 0.9rem;">
                                            <i class="bi bi-tags me-1"></i>Tipos Seleccionados
                                        </label>
                                        <div class="d-flex align-items-center bg-light rounded p-1">
                                            <h6 class="mb-0 me-2" id="contadorTipos" style="color: #8B0000; font-size: 1.1rem;">
                                                {{ count($tiposSeleccionados) }}
                                            </h6>
                                            <div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem; line-height: 1;">
                                                    de {{ count($tipos) }}
                                                </small>
                                                <small class="text-muted" style="font-size: 0.7rem; line-height: 1;">
                                                    seleccionados
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tipos de Actualización -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-medium mb-0" style="color: #8B0000; font-size: 0.9rem;">
                                        <i class="bi bi-list-check me-1"></i>Tipos de Actualización *
                                    </label>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        Seleccione al menos uno
                                    </small>
                                </div>

                                <!-- Grid de 4 columnas -->
                                <div class="row g-1" id="tiposContainer">
                                    @foreach($tipos as $key => $value)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-0">
                                        <div class="form-check border rounded p-1 tipo-card h-100"
                                             style="background-color: #f8f9fa;
                                                    cursor: pointer;
                                                    font-size: 0.75rem;
                                                    min-height: 38px;
                                                    display: flex;
                                                    align-items: center;
                                                    margin: 0;
                                                    transition: all 0.2s;">
                                            <input class="form-check-input tipo-checkbox m-0 me-1"
                                                   type="checkbox"
                                                   name="tipos[]"
                                                   value="{{ $key }}"
                                                   id="tipo_{{ $key }}"
                                                   style="width: 0.85rem; height: 0.85rem; flex-shrink: 0;"
                                                   {{ in_array($key, $tiposSeleccionados) ? 'checked' : '' }}
                                                   onchange="actualizarContador()">
                                            <label class="form-check-label mb-0 w-100" for="tipo_{{ $key }}"
                                                   style="cursor: pointer; font-size: 0.75rem; line-height: 1.1;">
                                                {{ $value }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @error('tipos')
                                    <div class="invalid-feedback d-block mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Observaciones -->
                            <div class="col-12">
                                <label for="observaciones" class="form-label fw-medium mb-1" style="color: #8B0000; font-size: 0.9rem;">
                                    <i class="bi bi-chat-left-text me-1"></i>Observaciones
                                </label>
                                <textarea name="observaciones"
                                          id="observaciones"
                                          class="form-control form-control-sm @error('observaciones') is-invalid @enderror"
                                          rows="2"
                                          style="padding: 0.25rem 0.5rem; font-size: 0.85rem; resize: vertical;"
                                          placeholder="Observaciones (opcional)...">{{ old('observaciones', $actualizacion->observaciones) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback" style="font-size: 0.8rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Botones -->
                            <div class="col-12 mt-3 pt-2 border-top">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('actualizacion-operadors.index') }}"
                                       class="btn btn-sm btn-outline-secondary py-1 px-3" style="font-size: 0.85rem;">
                                        <i class="bi bi-x-circle me-1"></i> Cancelar
                                    </a>
                                    <div>
                                        <button type="submit"
                                                class="btn btn-sm d-flex align-items-center py-1 px-3 me-2"
                                                style="background: linear-gradient(to right, #FFD700, #FFA500);
                                                       color: #8B0000;
                                                       border: none;
                                                       font-weight: 600;
                                                       font-size: 0.85rem;
                                                       border-radius: 4px;
                                                       transition: all 0.2s ease;
                                                       box-shadow: 0 2px 4px rgba(139, 0, 0, 0.1);">
                                            <i class="bi bi-save-fill me-1"></i>
                                            <span>Actualizar</span>
                                        </button>
                                    </div>
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
    /* Estilos iguales al create */
    #operador_minero_id {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        background-color: white;
        transition: all 0.2s;
    }

    #operador_minero_id:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    #operador_minero_id option {
        padding: 0.3rem 0.5rem;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.85rem;
    }

    #operador_minero_id option:hover {
        background-color: rgba(139, 0, 0, 0.1);
    }

    #operador_minero_id option:checked {
        background-color: rgba(139, 0, 0, 0.2);
        font-weight: bold;
    }

    /* Scroll personalizado */
    #operador_minero_id::-webkit-scrollbar {
        width: 6px;
    }

    #operador_minero_id::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    #operador_minero_id::-webkit-scrollbar-thumb {
        background: #8B0000;
        border-radius: 4px;
        opacity: 0.5;
    }

    /* Tipos */
    .tipo-card {
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }

    .tipo-card:hover {
        background-color: rgba(139, 0, 0, 0.05) !important;
        border-color: #8B0000 !important;
        transform: translateY(-1px);
    }

    .tipo-card.selected {
        background-color: rgba(139, 0, 0, 0.1) !important;
        border-color: #8B0000 !important;
        box-shadow: 0 0 0 1px #8B0000;
    }

    .tipo-checkbox:checked {
        background-color: #8B0000;
        border-color: #8B0000;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tipo-card {
            min-height: 36px;
        }

        .tipo-card label {
            font-size: 0.7rem !important;
        }
    }

    @media (max-width: 576px) {
        #tiposContainer .col-6 {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .tipo-card {
            padding: 0.5rem !important;
            min-height: 34px;
        }

        #operador_minero_id {
            font-size: 0.8rem !important;
        }
    }

    /* Botón actualizar */
    button[type="submit"]:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(255, 215, 0, 0.3) !important;
        background: linear-gradient(to right, #FFE44D, #FFB347) !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Filtrar opciones del select mientras se escribe
document.getElementById('searchOperador').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const select = document.getElementById('operador_minero_id');
    const options = select.querySelectorAll('option');
    let visibleCount = 0;

    // Mostrar todas las opciones primero (excepto la primera vacía)
    options.forEach((option, index) => {
        if (index > 0) {
            option.style.display = 'block';
        }
    });

    // Filtrar si hay texto de búsqueda
    if (searchTerm !== '') {
        options.forEach((option, index) => {
            if (index > 0) {
                const searchText = option.getAttribute('data-search') || option.textContent.toLowerCase();
                if (searchText.includes(searchTerm)) {
                    option.style.display = 'block';
                    visibleCount++;
                } else {
                    option.style.display = 'none';
                }
            }
        });
    } else {
        visibleCount = options.length - 1;
    }
});

// Mostrar operador seleccionado
document.getElementById('operador_minero_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];

    if (selectedOption.value !== '') {
        document.getElementById('selectedOperadorText').textContent = selectedOption.textContent.trim();
        document.getElementById('selectedOperador').classList.remove('d-none');
        document.getElementById('searchOperador').value = '';

        // Mostrar todas las opciones
        const options = this.querySelectorAll('option');
        options.forEach(option => {
            option.style.display = 'block';
        });
    } else {
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

    const options = select.querySelectorAll('option');
    options.forEach(option => {
        option.style.display = 'block';
    });
}

// Limpiar búsqueda
function clearSearch() {
    document.getElementById('searchOperador').value = '';
    document.getElementById('searchOperador').focus();

    const select = document.getElementById('operador_minero_id');
    const options = select.querySelectorAll('option');
    options.forEach(option => {
        option.style.display = 'block';
    });
}

// Contador de tipos
function actualizarContador() {
    const checkboxes = document.querySelectorAll('.tipo-checkbox');
    const contador = document.getElementById('contadorTipos');
    const seleccionados = document.querySelectorAll('.tipo-checkbox:checked').length;

    contador.textContent = seleccionados;

    checkboxes.forEach(checkbox => {
        const card = checkbox.closest('.tipo-card');
        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
}

// Clic en tarjeta de tipo
document.querySelectorAll('.tipo-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (e.target.type !== 'checkbox') {
            const checkbox = this.querySelector('.tipo-checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        }
    });
});

// Inicializar contador al cargar
document.addEventListener('DOMContentLoaded', function() {
    actualizarContador();

    // Fecha máxima hoy
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').setAttribute('max', today);

    // Auto-focus en búsqueda
    document.getElementById('searchOperador').focus();
});

// Validación del formulario
document.getElementById('formActualizacion').addEventListener('submit', function(e) {
    let isValid = true;
    let errorMessage = '';

    const operadorSelect = document.getElementById('operador_minero_id');
    if (!operadorSelect.value) {
        errorMessage = 'Por favor, seleccione un operador minero';
        document.getElementById('searchOperador').focus();
        isValid = false;
    }

    const tiposSeleccionados = document.querySelectorAll('.tipo-checkbox:checked').length;
    if (tiposSeleccionados === 0) {
        errorMessage = 'Por favor, seleccione al menos un tipo de actualización';
        isValid = false;
    }

    const fecha = document.getElementById('fecha').value;
    const hoy = new Date().toISOString().split('T')[0];
    if (fecha > hoy) {
        errorMessage = 'La fecha no puede ser futura';
        document.getElementById('fecha').focus();
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        alert(errorMessage);
        return false;
    }

    return true;
});
</script>
@endpush
