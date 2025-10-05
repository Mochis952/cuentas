@extends('layouts.master')

@section('title', 'Cuentas de streaming')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- SweetAlert2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* Estilos para la tabla y sus componentes */
    .table-container {
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 1rem;
        overflow-x: auto; /* Para responsividad en móviles */
    }
    .table th.col-service { width: 15%; }
    .table th.col-email { width: 25%; }
    .table th.col-users, .table th.col-max, .table th.col-status { width: 8%; text-align: center; }
    .table th.col-actions { width: 12%; text-align: center; }

    .main-row {
        cursor: pointer;
    }

    /* Fila de detalles expandida */
    .details-row td {
        padding: 0 !important;
        background-color: #f8f9fa;
        border: none;
    }
    .details-container {
        padding: 1.5rem;
    }
    .details-card {
        border: 1px solid #dee2e6;
        box-shadow: none;
        height: 100%;
    }
    .details-card .card-header {
        font-weight: 600;
        background-color: #e9ecef;
        border-bottom: 1px solid #dee2e6;
    }
    .details-card .list-group-item {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0 !important;
    }
    .details-card .list-group-item:last-child {
        border-bottom: none !important;
    }
    .details-card strong {
        color: #343a40;
    }

    /* Icono de expansión */
    .expand-chevron {
        transition: transform 0.3s ease-in-out;
        font-size: 0.9rem;
        vertical-align: middle;
    }
    .expand-chevron.rotated {
        transform: rotate(180deg);
    }

    /* Chat bubble styles */
    .chat-bubble {
        padding: 10px 15px;
        border-radius: 20px;
        margin-bottom: 10px;
        max-width: 75%;
        position: relative;
        clear: both;
        word-wrap: break-word;
    }
    .chat-bubble.from-me {
        background-color: #dcf8c6;
        float: right;
    }
    .chat-bubble.from-them {
        background-color: #fff;
        float: left;
    }
    .chat-timestamp {
        font-size: 0.75rem;
        color: #999;
        display: block;
        text-align: right;
        margin-top: 5px;
    }
</style>
@endpush
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="list_container" class="container-fluid quote-box">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Cuentas disponibles</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="table-container">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="col-service">Cuenta</th>
                                <th scope="col" class="col-email">Correo</th>
                                <th scope="col" class="col-users" data-bs-toggle="tooltip" title="Usuarios Activos">🧑</th>
                                <th scope="col" class="col-max" data-bs-toggle="tooltip" title="Usuarios Máximos">Max</th>
                                <th scope="col" class="col-status" data-bs-toggle="tooltip" title="Estado">⚡</th>
                                <th scope="col" class="col-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_account_streaming">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel"><i class="fas fa-edit me-2"></i>Editar Cuenta de Streaming</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="background-color: #f8f9fa !important;">
                    <form id="editAccountForm" class="p-3">
                        <input type="hidden" id="edit_account_id" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Servicio -->
                                <div class="mb-3">
                                    <label for="edit_name_service" class="form-label">Servicio</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tv"></i></span>
                                        <input type="text" class="form-control" id="edit_name_service" name="name_service" readonly>
                                    </div>
                                </div>
                                <!-- Correo -->
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Correo</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="edit_email" name="email">
                                    </div>
                                </div>
                                <!-- Contraseña -->
                                <div class="mb-3">
                                    <label for="edit_password" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="text" class="form-control" id="edit_password" name="password">
                                    </div>
                                </div>
                                <!-- Precio -->
                                <div class="mb-3">
                                    <label for="edit_prices" class="form-label">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        <input type="number" class="form-control" id="edit_prices" name="prices">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Tipo de Cuenta -->
                                <div class="mb-3">
                                    <label for="edit_type_account" class="form-label">Tipo de Cuenta</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <input type="text" class="form-control" id="edit_type_account" name="type_account">
                                    </div>
                                </div>
                                <!-- Método de Pago -->
                                <div class="mb-3">
                                    <label for="edit_type_payment" class="form-label">Método de Pago</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="text" class="form-control" id="edit_type_payment" name="type_payment">
                                    </div>
                                </div>
                                <!-- Fecha de Pago -->
                                <div class="mb-3">
                                    <label for="edit_date_payment" class="form-label">Fecha de Pago</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="edit_date_payment" name="date_payment">
                                    </div>
                                </div>
                                <!-- Estado -->
                                 <div class="mb-3">
                                    <label for="edit_status" class="form-label">Estado</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                        <select class="form-select" id="edit_status" name="status">
                                            <option value="active">Activo</option>
                                            <option value="inactive">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" onclick="deleteAccount()"><i class="fas fa-trash-alt me-1"></i>Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="saveChanges()"><i class="fas fa-save me-1"></i>Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Profile Modal -->
    <div class="modal fade" id="update_profile_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <form id="update_profile">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Datos del perfil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="profile" class="form-label">Perfil</label>
                                    <select class="form-select" name="profile" id="profile">
                                        <option value="-1">Selecciona una opción</option>
                                        <option value="1">Perfil 1</option>
                                        <option value="2">Perfil 2</option>
                                        <option value="3">Perfil 3</option>
                                        <option value="4">Perfil 4</option>
                                        <option value="5">Perfil 5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="pin_profile" class="form-label">Pin del perfil</label>
                                    <input type="text" class="form-control" id="pin_profile" name="pin_profile">
                                </div>
                            </div>
                        </div>
                        <div class="d-none">
                            <input type="text" id="id_user" name="id_user">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="update_profile_button"type="submit" class="btn btn-primary w-100">Actualizar perfil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chat History Modal -->
    <div class="modal fade" id="chatHistoryModal" tabindex="-1" aria-labelledby="chatHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatHistoryModalLabel">Historial de Conversación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="chatHistoryBody" style="background-color: #e5ddd5; overflow-y: auto; padding-bottom: 20px;">
                    <!-- Chat messages will be loaded here -->
                </div>
                <div class="modal-footer bg-light">
                    <div class="w-100">
                        <div id="quickRepliesContainer" class="mb-2">
                            <!-- Quick replies will be loaded here -->
                        </div>
                        <div class="input-group">
                            <input type="text" id="chatMessageInput" class="form-control" placeholder="Escribe un mensaje...">
                            <button class="btn btn-primary" type="button" id="sendMessageBtn">
                                <i class="fas fa-paper-plane"></i> Enviar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // 1. Configuración global y listeners
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // 2. Listeners para la funcionalidad de Chat
        $('#sendMessageBtn').on('click', function() {
            sendMessage();
        });

        $('#chatMessageInput').on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) { // Enter key without Shift
                e.preventDefault();
                sendMessage();
            }
        });

        $('#quickRepliesContainer').on('click', '.quick-reply-btn', function() {
            const message = $(this).text();
            $('#chatMessageInput').val(message).focus();
        });

        // 3. Configuración del plugin de validación
        if ($.fn.validate) {
            $("#update_profile").validate({
                rules: {
                    profile: {
                        required: true,
                        min: 1
                    },
                },
                messages: {
                    profile: {
                        required: "Por favor, selecciona un perfil.",
                        min: "Por favor, selecciona un perfil válido."
                    },
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    $("#update_profile_button").prop("disabled", true);
                    var formData = $(form).serialize();

                    $.ajax({
                        url: "/customer_account/update_profile",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success("Perfil actualizado correctamente", "Éxito");
                            $("#update_profile_button").prop("disabled", false);
                            $('#update_profile_modal').modal('hide');
                            get_data_account_streaming();
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocurrió un error.";
                            toastr.error("No se actualizó el perfil. " + errorMessage, "Error");
                            $("#update_profile_button").prop("disabled", false);
                        }
                    });
                }
            });
        }

        // 4. Carga inicial de datos
        get_data_account_streaming();
    });

    function get_data_account_streaming() {
        $.ajax({
            url: "{{ route('index_account_streaming') }}",
            type: 'GET',
            success: function(response) {
                let tbody = $('#tbody_account_streaming');
                tbody.empty(); // Limpiar tabla antes de repoblar

                response.forEach(function(element) {
                    let customer_accounts = element.customer_accounts;

                    let color_tr = {
                        "Netflix": "table-danger", "Spotify": "table-success", "Disney": "table-info",
                        "Hbo_max": "table-purple", "Paramount": "table-light", "Amazon": "table-primary",
                        "Vix": "table-warning", "Youtube": "table-orange"
                    };
                    let row_color = color_tr[element.name_service] || 'table-light';

                    // --- Construcción de la lista de clientes para la vista de detalles ---
                    var data_customers_html = "";
                    if (customer_accounts.length > 0) {
                        data_customers_html = '<ul class="list-group list-group-flush">';
                        customer_accounts.forEach(function(element2) {
                            data_customers_html += `
                                <li class="list-group-item">
                                    <p class="mb-1"><strong>Cliente:</strong> ${element2.customer.name_customer_facebook}</p>
                                    <p class="mb-1"><strong>Contacto:</strong> ${element2.customer.contact_method}</p>
                                    <p class="mb-1"><strong>Próximo Pago:</strong> ${element2.date_expiration}</p>
                                    <p class="mb-1"><strong>Perfil:</strong> ${element2.profile}</p>
                                    <div class="mt-2 text-end">
                                        <button class="btn btn-success btn-sm" title="Registrar Pago" onclick="bill_payment(${element2.id}, this)"><i class="fas fa-dollar-sign"></i></button>
                                        <button class="btn btn-info btn-sm" title="Ver Historial" onclick='viewHistory(event, ${JSON.stringify(element2.customer)})'><i class="fas fa-history"></i></button>
                                        <button class="btn btn-warning btn-sm" title="Editar Cliente" onclick="edit_customer(${element2.id}, this)"><i class="fas fa-user-edit"></i></button>
                                        <button class="btn btn-danger btn-sm" title="Eliminar Cliente" onclick="delete_customer(${element2.id}, this)"><i class="fas fa-trash"></i></button>
                                    </div>
                                </li>`;
                        });
                        data_customers_html += '</ul>';
                    } else {
                        data_customers_html = '<div class="card-body text-center text-muted"><p class="mb-0">No hay clientes asignados.</p></div>';
                    }

                    // --- Plantilla de las filas de la tabla ---
                    let account_streaming_row = `
                        <tr class="main-row ${row_color}" onclick="show_hide_data_acount(${element.id})">
                            <th scope="row">${element.name_service}</th>
                            <td>${element.email}</td>
                            <td class="text-center">${element.user_active}</td>
                            <td class="text-center">${element.user_max}</td>
                            <td class="text-center">${element.status == "active" ? '🟢' : '🔴'}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary" onclick='openEditModal(${JSON.stringify(element)}, event)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <i id="chevron_${element.id}" class="fas fa-chevron-down expand-chevron ms-2"></i>
                            </td>
                        </tr>
                        <tr id="data_account_${element.id}" class="details-row d-none">
                            <td colspan="6">
                                <div class="details-container">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="card details-card">
                                                <div class="card-header">Datos de la Cuenta</div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><strong>Correo:</strong> ${element.email}</li>
                                                    <li class="list-group-item"><strong>Contraseña:</strong> ${element.password}</li>
                                                    <li class="list-group-item"><strong>Precio:</strong> ${element.prices}</li>
                                                    <li class="list-group-item"><strong>Tipo:</strong> ${element.type_account}</li>
                                                    <li class="list-group-item"><strong>Método Pago:</strong> ${element.type_payment}</li>
                                                    ${element.bank_name ? `<li class="list-group-item"><strong>Banco:</strong> ${element.bank_name}</li>` : ''}
                                                    ${element.card_number ? `<li class="list-group-item"><strong>Num Tarjeta:</strong> ${element.card_number}</li>` : ''}
                                                    ${element.account_pays ? `<li class="list-group-item"><strong>Cuenta que paga:</strong> ${element.account_pays}</li>` : ''}
                                                    <li class="list-group-item"><strong>Fecha de pago:</strong> ${element.date_payment}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card details-card">
                                                <div class="card-header">Clientes Asignados</div>
                                                ${data_customers_html}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.append(account_streaming_row);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                toastr.error('Ocurrió un error al cargar los datos.', 'Error', { timeOut: 5000 });
            }
        });
    }

    function show_hide_data_acount(elementId) {
        $('#data_account_' + elementId).toggleClass('d-none');
        $('#chevron_' + elementId).toggleClass('rotated');
    }

    function openEditModal(account, event) {
        event.stopPropagation(); // Prevenir que se dispare el click de la fila
        $('#editAccountModal').modal('show');

        // Poblar el formulario del modal
        $('#edit_account_id').val(account.id);
        $('#edit_name_service').val(account.name_service);
        $('#edit_email').val(account.email);
        $('#edit_password').val(account.password);
        $('#edit_prices').val(account.prices);
        $('#edit_type_account').val(account.type_account);
        $('#edit_type_payment').val(account.type_payment);
        $('#edit_date_payment').val(account.date_payment);
        $('#edit_status').val(account.status);
    }

    function saveChanges() {
        const accountId = $('#edit_account_id').val();
        const formData = $('#editAccountForm').serialize();

        $.ajax({
            url: `/account-streaming/update/${accountId}`,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#editAccountModal').modal('hide');
                toastr.success(response.message, 'Éxito', { timeOut: 3000 });
                get_data_account_streaming(); // Recargar los datos
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                toastr.error(xhr.responseJSON.message || 'No se pudo actualizar la cuenta.', 'Error', { timeOut: 5000 });
            }
        });
    }

    function deleteAccount() {
        const accountId = $('#edit_account_id').val();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede deshacer! Para confirmar, escribe 'eliminar' abajo.",
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'eliminar',
            showCancelButton: true,
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            preConfirm: (inputValue) => {
                if (inputValue !== 'eliminar') {
                    Swal.showValidationMessage('Debes escribir "eliminar" para confirmar.');
                    return false; // BUG FIX: Previene que la promesa se resuelva si la validación falla.
                }
                return inputValue;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/account-streaming/delete/${accountId}`,
                    type: 'DELETE',
                    success: function(response) {
                        $('#editAccountModal').modal('hide');
                        toastr.warning(response.message, 'Eliminado', { timeOut: 3000 });
                        get_data_account_streaming(); // Recargar los datos
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        toastr.error(xhr.responseJSON.message || 'No se pudo eliminar la cuenta.', 'Error', { timeOut: 5000 });
                    }
                });
            }
        });
    }

    function bill_payment(customerAccountId, element){
        event.stopPropagation();
        if (!confirm('¿Confirmas pago?')) {
            return;
        }
        $(element).prop('disabled', true);
        $.ajax({
            url: "/customer_account/update/" + customerAccountId,
            type: 'POST',
            success: function(response) {
                toastr.success("Pago actualizado. Gracias por el pago :)", 'Éxito');
                setTimeout(() => {
                    get_data_account_streaming(); // Recargar todo
                }, 1000);
            },
            error: function(xhr, status, error) {
                $(element).prop('disabled', false);
                let errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocurrió un error.";
                toastr.error("No se actualizó el pago. " + errorMessage, 'Error');
            }
        });
    }

    function delete_customer(customerAccountId, element){
        event.stopPropagation();
        if (!confirm('¿Estás seguro de que deseas eliminar este cliente? Esta acción no se puede deshacer.')) {
            return;
        }
        $(element).prop('disabled', true);
        $.ajax({
            url: "/customer_account/delete/"+customerAccountId,
            type: 'DELETE',
            success: function(response) {
                toastr.success("Cliente eliminado.", 'Eliminado');
                setTimeout(() => {
                    get_data_account_streaming(); // Recargar todo
                }, 1000);
            },
            error: function(xhr, status, error) {
                $(element).prop('disabled', false);
                let errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocurrió un error.";
                toastr.error("Error al eliminar cliente. " + errorMessage, 'Error');
            }
        });
    }

    function edit_customer(customerAccountId, element){
        event.stopPropagation();
        reset_modal_update_profile();
        $('#id_user').val(customerAccountId);
        $('#update_profile_modal').modal('show');
    }

    function reset_modal_update_profile(){
        $('#id_user').val('');
        $('#profile').val('-1');
        $('#pin_profile').val('');
        if ($.fn.validate) {
            $('#update_profile').validate().resetForm();
            $('.is-invalid').removeClass('is-invalid');
        }
    }

    function viewHistory(event, customer) {
        event.stopPropagation();

        const contactId = customer.customer_phone_number;
        if (!contactId) {
            toastr.error('Este cliente no tiene un número de contacto para ver el historial.', 'Error');
            return;
        }

        const modalElement = document.getElementById('chatHistoryModal');
        const modal = new bootstrap.Modal(modalElement);
        const modalBody = $('#chatHistoryBody');

        // Store contactId in the modal's data attribute to be used by sendMessage
        $(modalElement).data('contact-id', contactId);
        
        $('#chatHistoryModalLabel').text(`Historial de: ${customer.name_customer_facebook || contactId}`);
        modal.show();
        modalBody.html('<div class="d-flex justify-content-center p-5"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>');

        // --- Cargar Respuestas Rápidas ---
        const quickRepliesContainer = $('#quickRepliesContainer');
        quickRepliesContainer.empty();
        const quickReplies = [
            "¡Hola! Te escribo para recordarte que tu suscripción vence pronto. ¿Deseas renovar?",
            "Tu servicio ha expirado. Si deseas continuar, por favor realiza tu pago.",
            "¡Gracias por tu pago! Hemos reactivado tu servicio.",
            "Recibido. En un momento te confirmo.",
            "¿Cómo estás? ¿Necesitas ayuda con algo?"
        ];
        quickReplies.forEach(reply => {
            const button = `<button type="button" class="btn btn-outline-secondary btn-sm me-1 mb-1 quick-reply-btn">${reply}</button>`;
            quickRepliesContainer.append(button);
        });

        $.ajax({
            url: `/chat/history/${contactId}`,
            type: 'GET',
            success: function(response) {
                modalBody.empty();
                if (response.messages && response.messages.length > 0) {
                    response.messages.forEach(function(message) {
                        appendMessageToChat(message.body, message.fromMe, message.timestamp);
                    });
                    modalBody.scrollTop(modalBody[0].scrollHeight);
                } else {
                    modalBody.html('<p class="text-center text-muted p-5">No hay mensajes en el historial.</p>');
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = "No se pudo cargar el historial. Verifique que la ruta y el controlador existan.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                modalBody.html(`<div class="alert alert-danger m-3">${errorMessage}</div>`);
            }
        });
    }

    function sendMessage() {
        const modalElement = $('#chatHistoryModal');
        const contactId = modalElement.data('contact-id');
        const messageInput = $('#chatMessageInput');
        const message = messageInput.val().trim();

        if (!message) {
            return; // Don't send empty messages
        }

        $('#sendMessageBtn').prop('disabled', true);

        // --- Backend endpoint to send the message ---
        // IMPORTANT: You need to create this route and controller method in Laravel.
        $.ajax({
            url: '/chat/send', // The new endpoint you need to create
            type: 'POST',
            data: {
                contactId: contactId,
                message: message,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Append the sent message to the chat UI
                appendMessageToChat(message, true, (Date.now() / 1000));
                messageInput.val(''); // Clear input
                $('#chatHistoryBody').scrollTop($('#chatHistoryBody')[0].scrollHeight);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'No se pudo enviar el mensaje.', 'Error');
            },
            complete: function() {
                $('#sendMessageBtn').prop('disabled', false);
            }
        });
    }

    function appendMessageToChat(body, fromMe, timestamp) {
        if (!body) return;

        const modalBody = $('#chatHistoryBody');
        // If the "no history" message is present, remove it.
        if (modalBody.find('p.text-center').length > 0) {
            modalBody.empty();
        }

        const messageDate = new Date(timestamp * 1000);
        const formattedTime = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        const bubbleClass = fromMe ? 'from-me' : 'from-them';
        const chatMessage = `
            <div class="chat-bubble ${bubbleClass}">
                ${body.replace(/\n/g, '<br>')}
                <span class="chat-timestamp">${formattedTime}</span>
            </div>
        `;
        modalBody.append(chatMessage);
    }
</script>
@endpush
