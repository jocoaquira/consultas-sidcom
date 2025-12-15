@extends('layouts.app')

@section('title', 'Nuevo Bloqueo/Desbloqueo de Operador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #8B0000 0%, #6A0C0C 100%);">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Registro de Bloqueo/Desbloqueo
                    </h4>
                    <a href="{{ route('bloqueo-operadors.index') }}"
                       class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <!-- Alertas normales -->
                    @if(request('success') != '1' && session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div class="flex-grow-1">{{ session('error') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('bloqueo-operadors.store') }}" method="POST" id="formBloqueo">
                        @csrf

                        <div class="row g-3">
                            <!-- Operador Minero -->
                            <div class="col-md-12">
                                <label for="operador_minero_id" class="form-label fw-medium" style="color: #8B0000;">
                                    <i class="bi bi-person-badge me-1"></i>Operador Minero *
                                </label>

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
                                            data-celular="{{ $operador->cel_op_min ?: $operador->cel_rep_legal }}"
                                            data-email="{{ $operador->email_op_min }}"
                                            data-nombre="{{ $operador->nombre_rep_legal }}"
                                            data-nit="{{ $operador->nit }}">
                                        {{ $operador->razon_social }}
                                        @if($operador->cel_op_min || $operador->cel_rep_legal)
                                            ({{ $operador->cel_op_min ?: $operador->cel_rep_legal }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>

                                <div id="selectedOperador" class="mt-2 d-none">
                                    <div class="alert alert-success py-2 mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-check-circle-fill me-2"></i>
                                                <strong id="selectedOperadorText"></strong>
                                                <span id="selectedCelular" class="text-muted ms-2"></span>
                                                <br>
                                                <small id="selectedEmail" class="text-muted"></small>
                                            </div>
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
                                    <option value="activo">Activar / Desbloquear</option>
                                    <option value="bloqueado">Bloquear</option>
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
                                       value="{{ date('Y-m-d') }}"
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
                                          minlength="10"
                                          required></textarea>
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <span id="contadorCaracteres">0</span> / 10 caracteres mínimos
                                </div>
                                @error('motivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Vista previa del mensaje -->
                            <div class="col-md-12" id="mensajePreview" style="display: none;">
                                <label class="form-label fw-medium" style="color: #25D366;">
                                    <i class="fab fa-whatsapp me-1"></i>Vista Previa del Mensaje WhatsApp
                                </label>
                                <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto; font-size: 0.85rem; white-space: pre-line;">
                                    <div id="mensajePreviewContent"></div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('bloqueo-operadors.index') }}"
                                       class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Cancelar
                                    </a>
                                    <button type="button"
                                            onclick="validarYMostrarConfirmacion()"
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
                                        <span>Guardar Registro</span>
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

<!-- Modal de Validación de Errores -->
<div class="modal fade" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
                <h5 class="modal-title text-white">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Validación de Formulario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                </div>
                <h6 class="text-center mb-3" id="mensajeError"></h6>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1"></i> Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación Personalizado -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" id="modalHeaderConfirm">
                <h5 class="modal-title text-white" id="modalTituloConfirm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Acción
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyConfirm">
                <!-- Se llenará dinámicamente -->
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="button" class="btn" id="btnConfirmarAccion" onclick="confirmarYEnviar()">
                    <i class="bi bi-check-circle me-1"></i> Sí, continuar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de WhatsApp -->
@if(request('success') == '1')
<div class="modal fade" id="modalWhatsApp" tabindex="-1" aria-labelledby="modalWhatsAppLabel" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);">
                <h5 class="modal-title text-white">
                    <i class="fab fa-whatsapp me-2"></i>Notificación WhatsApp
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="fab fa-whatsapp text-success fa-4x"></i>
                    </div>
                    <h5 class="text-success mb-2">¡Registro guardado exitosamente!</h5>
                    <p class="text-muted">¿Desea enviar la notificación por WhatsApp al operador?</p>
                </div>

                <div class="alert alert-info border-info bg-info bg-opacity-10">
                    <div class="d-flex">
                        <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                        <div>
                            <h6 class="text-info mb-1">Información del envío</h6>
                            <p class="mb-1"><strong>Operador:</strong> {{ request('operador') }}</p>
                            <p class="mb-1"><strong>Acción:</strong>
                                @if(request('estado') == 'bloqueado')
                                    <span class="badge bg-danger">BLOQUEO</span>
                                @else
                                    <span class="badge bg-success">DESBLOQUEO</span>
                                @endif
                            </p>
                            @if(request('email_enviado') == '1')
                            <p class="mb-0 text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                <strong>Email:</strong> Enviado a {{ request('email_destino') }}
                            </p>
                            @else
                            <p class="mb-0 text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Email:</strong> {{ request('email_destino') }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    $whatsappData = base64_decode(request('whatsapp_data'));
                    if ($whatsappData) {
                        $whatsappData = json_decode($whatsappData, true);
                    }
                @endphp

                @if($whatsappData && isset($whatsappData['numero']) && isset($whatsappData['mensaje']))
                <div class="alert alert-success border-success bg-success bg-opacity-10">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <small><strong>WhatsApp listo:</strong> Al hacer clic se abrirá WhatsApp Web con el mensaje preparado automáticamente.</small>
                </div>
                @else
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    <small><strong>WhatsApp no disponible:</strong> El operador no tiene número de teléfono registrado.</small>
                </div>
                @endif
            </div>
            <div class="modal-footer border-0">
                <a href="{{ route('bloqueo-operadors.index') }}" class="btn btn-outline-secondary flex-grow-1">
                    <i class="bi bi-x-circle me-1"></i> Cerrar sin enviar
                </a>
                @if($whatsappData && isset($whatsappData['numero']) && isset($whatsappData['mensaje']))
                <button type="button"
                        class="btn btn-success flex-grow-1"
                        onclick="abrirWhatsAppConJavaScript('{{ $whatsappData['numero'] }}', `{{ addslashes($whatsappData['mensaje']) }}`)">
                    <i class="fab fa-whatsapp me-1"></i> Abrir WhatsApp
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .form-select:focus, .form-control:focus, textarea:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    button[type="button"]:hover {
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

    #operador_minero_id option:hover {
        background-color: rgba(139, 0, 0, 0.1);
    }

    #operador_minero_id option:checked {
        background-color: rgba(139, 0, 0, 0.2);
        font-weight: bold;
    }

    .modal-header-danger {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    }

    .modal-header-success {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    /* Deshabilitar validación HTML5 por defecto */
    input:invalid, textarea:invalid, select:invalid {
        box-shadow: none;
    }
</style>
@endpush

@push('scripts')
<script>
let operadorSeleccionado = null;

// Deshabilitar validación HTML5 nativa
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('formBloqueo').setAttribute('novalidate', 'novalidate');

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').setAttribute('max', today);

    // Si hay success en la URL, mostrar modal de WhatsApp
    @if(request('success') == '1')
    const modalWhatsApp = new bootstrap.Modal(document.getElementById('modalWhatsApp'));
    modalWhatsApp.show();
    @endif

    // Contador de caracteres en tiempo real
    const motivoTextarea = document.getElementById('motivo');
    const contador = document.getElementById('contadorCaracteres');

    motivoTextarea.addEventListener('input', function() {
        const length = this.value.trim().length;
        contador.textContent = length;

        if (length < 10) {
            contador.style.color = '#dc2626';
            contador.style.fontWeight = 'bold';
        } else {
            contador.style.color = '#059669';
            contador.style.fontWeight = 'bold';
        }
    });
});

// Filtrar opciones del select
document.getElementById('searchOperador').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const select = document.getElementById('operador_minero_id');
    const options = select.querySelectorAll('option');

    options.forEach(option => {
        option.style.display = 'block';
    });

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

// Mostrar operador seleccionado
document.getElementById('operador_minero_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];

    if (selectedOption.value !== '') {
        operadorSeleccionado = {
            id: selectedOption.value,
            nombre: selectedOption.getAttribute('data-nombre'),
            razonSocial: selectedOption.textContent.split(' (')[0],
            celular: selectedOption.getAttribute('data-celular'),
            email: selectedOption.getAttribute('data-email'),
            nit: selectedOption.getAttribute('data-nit')
        };

        document.getElementById('selectedOperadorText').textContent = operadorSeleccionado.razonSocial;
        document.getElementById('selectedCelular').textContent = operadorSeleccionado.celular ?
            'Tel: ' + operadorSeleccionado.celular : 'Sin número registrado';
        document.getElementById('selectedEmail').textContent = operadorSeleccionado.email ?
            'Email: ' + operadorSeleccionado.email : 'Sin email registrado';

        document.getElementById('selectedOperador').classList.remove('d-none');
        document.getElementById('searchOperador').value = '';

        const options = this.querySelectorAll('option');
        options.forEach(option => {
            option.style.display = 'block';
        });

        generarVistaPreviaMensaje();
    } else {
        operadorSeleccionado = null;
        document.getElementById('selectedOperador').classList.add('d-none');
        document.getElementById('mensajePreview').style.display = 'none';
    }
});

// Generar vista previa del mensaje WhatsApp
function generarVistaPreviaMensaje() {
    const selectEstado = document.getElementById('estado');
    const textareaMotivo = document.getElementById('motivo');

    if (selectEstado.value && textareaMotivo.value && operadorSeleccionado) {
        const estado = selectEstado.value;
        const motivo = textareaMotivo.value;

        let mensaje = '';
        if (estado === 'bloqueado') {
            mensaje = "*GOBIERNO AUTONOMO DEPARTAMENTAL DE ORURO*\n";
            mensaje += "*SECRETARIA DEPARTAMENTAL DE MINERIA*\n\n";
            mensaje += "*--- DESABILITACION DE CUENTA SIDCOM ---*\n\n";
            mensaje += "Estimado(a): *" + operadorSeleccionado.nombre + "*\n\n";
            mensaje += "Le informamos que su cuenta en el Sistema SIDCOM ha sido *BLOQUEADA*\n\n";
            mensaje += "*MOTIVO:*\n" + motivo + "\n\n";
            mensaje += "*DATOS DEL OPERADOR:*\n";
            mensaje += "- Razon Social: " + operadorSeleccionado.razonSocial + "\n";
            mensaje += "- NIT: " + operadorSeleccionado.nit + "\n";
            mensaje += "- Representante: " + operadorSeleccionado.nombre + "\n\n";
            mensaje += "*CONTACTO:*\n";
            mensaje += "Email: mineria@oruro.gob.bo\n";
            mensaje += "Telefonos: 61831994 - 64050564\n\n";
            mensaje += "_Mensaje automatico - GADOR_";
        } else {
            mensaje = "*GOBIERNO AUTONOMO DEPARTAMENTAL DE ORURO*\n";
            mensaje += "*SECRETARIA DEPARTAMENTAL DE MINERIA*\n\n";
            mensaje += "*--- HABILITACION DE CUENTA SIDCOM ---*\n\n";
            mensaje += "Estimado(a): *" + operadorSeleccionado.nombre + "*\n\n";
            mensaje += "Su cuenta en el Sistema SIDCOM ha sido *HABILITADA*\n\n";
            mensaje += "*MOTIVO:*\n" + motivo + "\n\n";
            mensaje += "*DATOS DEL OPERADOR:*\n";
            mensaje += "- Razon Social: " + operadorSeleccionado.razonSocial + "\n";
            mensaje += "- NIT: " + operadorSeleccionado.nit + "\n";
            mensaje += "- Representante: " + operadorSeleccionado.nombre + "\n\n";
            mensaje += "*CONTACTO:*\n";
            mensaje += "Email: mineria@oruro.gob.bo\n";
            mensaje += "Telefonos: 61831994 - 64050564\n\n";
            mensaje += "_Mensaje automatico - GADOR_";
        }

        document.getElementById('mensajePreviewContent').textContent = mensaje;
        document.getElementById('mensajePreview').style.display = 'block';
    }
}

document.getElementById('estado').addEventListener('change', generarVistaPreviaMensaje);
document.getElementById('motivo').addEventListener('input', generarVistaPreviaMensaje);

function clearOperadorSelection() {
    const select = document.getElementById('operador_minero_id');
    select.value = '';
    operadorSeleccionado = null;
    document.getElementById('selectedOperador').classList.add('d-none');
    document.getElementById('searchOperador').value = '';
    document.getElementById('searchOperador').focus();
    document.getElementById('mensajePreview').style.display = 'none';

    const options = select.querySelectorAll('option');
    options.forEach(option => {
        option.style.display = 'block';
    });
}

// Función para mostrar modal de error personalizado
function mostrarError(mensaje) {
    document.getElementById('mensajeError').textContent = mensaje;
    const modalError = new bootstrap.Modal(document.getElementById('modalError'));
    modalError.show();
}

// Validar y mostrar confirmación
function validarYMostrarConfirmacion() {
    // Validación: Operador seleccionado
    const operadorSelect = document.getElementById('operador_minero_id');
    if (!operadorSelect.value) {
        mostrarError('Por favor, seleccione un operador minero antes de continuar.');
        return false;
    }

    // Validación: Estado seleccionado
    const estado = document.getElementById('estado').value;
    if (!estado) {
        mostrarError('Por favor, seleccione una acción (Bloquear o Desbloquear).');
        return false;
    }

    // Validación: Motivo con mínimo 10 caracteres
    const motivo = document.getElementById('motivo').value.trim();
    if (motivo.length < 10) {
        mostrarError(`El motivo debe tener al menos 10 caracteres. Actualmente tiene ${motivo.length} caracteres.`);
        return false;
    }

    // Validación: Fecha no futura
    const fecha = document.getElementById('fecha').value;
    const hoy = new Date().toISOString().split('T')[0];
    if (fecha > hoy) {
        mostrarError('La fecha no puede ser posterior al día de hoy.');
        return false;
    }

    // Validación: Fecha no vacía
    if (!fecha) {
        mostrarError('Por favor, seleccione una fecha.');
        return false;
    }

    // Si todas las validaciones pasan, mostrar modal de confirmación
    mostrarModalConfirmacion(estado, motivo);
}

// Mostrar modal de confirmación personalizado
function mostrarModalConfirmacion(estado, motivo) {
    const modalHeader = document.getElementById('modalHeaderConfirm');
    const modalBody = document.getElementById('modalBodyConfirm');
    const btnConfirmar = document.getElementById('btnConfirmarAccion');

    if (estado === 'bloqueado') {
        modalHeader.className = 'modal-header border-0 modal-header-danger';
        modalHeader.innerHTML = '<h5 class="modal-title text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar BLOQUEO</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>';

        modalBody.innerHTML =
            `<div class="text-center mb-3">
                <i class="bi bi-lock-fill text-danger" style="font-size: 4rem;"></i>
            </div>
            <h6 class="text-center mb-3">¿Está seguro de BLOQUEAR al operador?</h6>
            <div class="alert alert-danger">
                <strong>Operador:</strong> ${operadorSeleccionado.razonSocial}<br>
                <strong>Motivo:</strong> ${motivo}
            </div>
            <div class="alert alert-warning">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Se enviará automáticamente:</strong>
                <ul class="mb-0 mt-2">
                    <li>✉️ Email de notificación${operadorSeleccionado.email ? ' a ' + operadorSeleccionado.email : ' (no tiene email)'}</li>
                    <li>📱 Mensaje de WhatsApp preparado${operadorSeleccionado.celular ? ' al ' + operadorSeleccionado.celular : ' (no tiene número)'}</li>
                </ul>
            </div>`;

        btnConfirmar.className = 'btn btn-danger';
        btnConfirmar.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Sí, BLOQUEAR';
    } else {
        modalHeader.className = 'modal-header border-0 modal-header-success';
        modalHeader.innerHTML = '<h5 class="modal-title text-white"><i class="bi bi-check-circle-fill me-2"></i>Confirmar DESBLOQUEO</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>';

        modalBody.innerHTML =
            `<div class="text-center mb-3">
                <i class="bi bi-unlock-fill text-success" style="font-size: 4rem;"></i>
            </div>
            <h6 class="text-center mb-3">¿Está seguro de DESBLOQUEAR al operador?</h6>
            <div class="alert alert-success">
                <strong>Operador:</strong> ${operadorSeleccionado.razonSocial}<br>
                <strong>Motivo:</strong> ${motivo}
            </div>
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Se enviará automáticamente:</strong>
                <ul class="mb-0 mt-2">
                    <li>✉️ Email de notificación${operadorSeleccionado.email ? ' a ' + operadorSeleccionado.email : ' (no tiene email)'}</li>
                    <li>📱 Mensaje de WhatsApp preparado${operadorSeleccionado.celular ? ' al ' + operadorSeleccionado.celular : ' (no tiene número)'}</li>
                </ul>
            </div>`;

        btnConfirmar.className = 'btn btn-success';
        btnConfirmar.innerHTML = '<i class="bi bi-unlock-fill me-1"></i> Sí, DESBLOQUEAR';
    }

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    modal.show();
}

function confirmarYEnviar() {
    // Cerrar modal de confirmación
    const modalConfirm = bootstrap.Modal.getInstance(document.getElementById('modalConfirmacion'));
    if (modalConfirm) {
        modalConfirm.hide();
    }

    // Enviar formulario
    document.getElementById('formBloqueo').submit();
}

/**
 * FUNCIÓN PRINCIPAL: Abrir WhatsApp con JavaScript
 * Usa window.open() para asegurar que se abra WhatsApp Web
 */
/**
 * FUNCIÓN CORRECTA: Abrir WhatsApp construyendo la URL en JavaScript
 */
 function abrirWhatsAppConJavaScript(numero, mensaje) {
    try {
        // Codificar el mensaje para URL (JavaScript lo hace bien para WhatsApp Web)
        const mensajeCodificado = encodeURIComponent(mensaje);

        // Construir la URL de WhatsApp Web
        const url = `https://wa.me/${numero}?text=${mensajeCodificado}`;

        // Abrir en nueva pestaña - ESTO es lo que hace que funcione
        window.open(url, '_blank', 'noopener,noreferrer');

        // Cerrar modal después de 500ms
        setTimeout(() => {
            const modalWhatsApp = bootstrap.Modal.getInstance(document.getElementById('modalWhatsApp'));
            if (modalWhatsApp) {
                modalWhatsApp.hide();
            }
            // Redirigir a la lista después de 1 segundo
            setTimeout(() => {
                window.location.href = '{{ route("bloqueo-operadors.index") }}';
            }, 1000);
        }, 500);

    } catch (error) {
        console.error('Error al abrir WhatsApp:', error);
        alert('Error al abrir WhatsApp. Por favor, intente manualmente.');
        window.location.href = '{{ route("bloqueo-operadors.index") }}';
    }
}

/**
 * Para compatibilidad con código anterior
 */
function abrirWhatsApp(url) {
    abrirWhatsAppConJavaScript(url);
}
</script>
@endpush
