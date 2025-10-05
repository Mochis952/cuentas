@extends('layouts.master')

@section('title', 'Cuentas de streaming')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="groupMainTextInput">
                                    <label>Perfil</label>
                                    <select name="profile" id="profile">
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
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="groupMainTextInput">
                                    <label>Pin del perfil</label>
                                    <input type="text" id="pin_profile" name="pin_profile">
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center d-none">
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="groupMainTextInput">
                                    <label>ID PERFIL</label>
                                    <input type="id_user" id="id_user" name="id_user">
                                </div>
                            </div>
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
    <div id="list_container" class="container-fluid quote-box">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Lista de Usuarios</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="table-container">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 15%">Cliente</th>
                                <th scope="col" style="width: 15%">Cuenta</th>
                                <th scope="col" style="width: 30%">Datos</th>
                                <th scope="col" style="width: 9%">📅</th>
                                <th scope="col">Accion</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_customer">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {
        get_data_account_streaming();
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
    });
    function get_data_account_streaming(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route ('index_customer')}}",
            type: 'GET',
            success: function(response) {
                console.log(response);
                response.forEach(function(element, index) { //todos los usuarios
                    console.log(" primero");
                    console.log(element);
                    let customer_accounts = element.customer_accounts;
                    var dateExpiration = null;
                    var name_service = null;
                    var data_account =null;
                    var id = null
                    customer_accounts.forEach(function(element, index) { //todas las cuentas que tienen
                        dateExpiration = element.date_expiration
                        name_service = element.account_streaming.name_service
                        id = element.id
                        data_account = "Correo: " + element.account_streaming.email + " <br> Contraseña: " + element.account_streaming.password + " <br> Perfil: " + element.profile + " <br> Pin: " + element.pin_profile;
                    });
                    const today = new Date();
                    let expirationDate = new Date(dateExpiration);
                    let differenceInTime = expirationDate - today;
                    let differenceInDays = Math.ceil(differenceInTime / (1000 * 60 * 60 * 24));
                    let day = dateExpiration.split("-")[2];
                    let color = null;
                    if (differenceInDays <= 3 && differenceInDays >= 0) {
                        color = 1;
                    } else if (differenceInDays > 3) {
                        color = 4;
                    } else {
                        color = 0;
                    }
                    let color_tr = {
                        "0": "table-danger",
                        "4": "table-success",
                        "1": "table-warning",
                    }
                    let contact_icon = {
                        "Facebook" : '<i class="fab fa-facebook"></i>',
                        "Whatsapp" : '<i class="fab fa-whatsapp"></i>'
                    }
                    let user_name = element.contact_method == "Facebook" ? element.name_customer_facebook : element.customer_phone_number;
                    let customer = `
                        <tr id="${id}" class="${color_tr[color]}" style="cursor:pointer;" onclick="show_hide_data_acount(${id})" >
                            <th scope="row">${user_name}</th>
                            <td>${name_service}</td>
                            <td>${data_account}</td>
                            <td>${day}</td>
                            <td>
                                <button type="button" class="btn btn-success p-2" style="font-size: .5rem !important;" onclick="bill_payment(${id},this)">Pago</button>
                                <button type="button" class="btn btn-danger p-2" style="font-size: .5rem !important;" onclick="delete_user(${id},this)">Eliminar</button>
                                <button type="button" class="btn btn-warning p-2" style="font-size: .5rem !important;" onclick="update_profile(${id},this)">Actualizar</button>
                                <button class="btn btn-info btn-sm" title="Ver Historial" onclick='viewHistory(event, ${JSON.stringify(element)})'><i class="fas fa-history"></i></button>
                            </td>
                            </tr>
                        `;
                $('#tbody_customer').append(customer);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                $("#save_account_streaming").attr("disabled", false);
                toastr.error('Error', error, {
                    timeOut: 5000,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                    progressBar: true,
                    "toastClass": "custom-toast-error",
                });

            }
        });
    }
    function show_hide_data_acount(element){
        if ($('#data_account_' + element).hasClass('d-none')) {
            $('#data_account_'+element).removeClass('d-none');
        } else {
            $('#data_account_'+element).addClass('d-none');
        }
    }
    function bill_payment(id, element){
        if (!confirm('¿Confirmas pago?')) {
            return;
        }
        $(element).prop('disabled', true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/customer_account/update/" + id,
            type: 'POST',
            success: function(response) {
                setTimeout(() => {
                    $(element).prop('disabled', false);
                }, 5000);
                $('#'+id).removeClass('table-danger').removeClass('table-warning');
                $('#'+id).addClass('table-success');
                generates_toasts_success("Pago actualizado", "Gracias por el pago :)")
            },
            error: function(xhr, status, error) {
                $(element).prop('disabled', false);
                generates_toasts_error("No se actualizó el pago", "Error: " + error);
            }
        });
    }
    function delete_user(id, element){
        if (!confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
            return;
        }
        $(element).prop('disabled', true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/customer_account/delete/"+id,
            type: 'DELETE',
            success: function(response) {
                $('#'+id).remove();
                generates_toasts_success("Usuario eliminado", "Espero que vuelva :( ")
            },
            error: function(xhr, status, error) {
                $(element).prop('disabled', false);
                generates_toasts_error("Error al eliminar usuario", "Error: " + error)
            }
        });
    }
    function update_profile(id, element){
        reset_modal_update_profile();
        $('#id_user').val(id);
        $('#update_profile_modal').modal('show');
    }
    function reset_modal_update_profile(){
        $('#id_user').val('');
        $('#profile').val('');
        $('#pin_profile').val('');
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
    $("#update_profile").validate({
            rules: {
                id_user: {
                    required: false,
                },
                profile: {
                    required: true
                },
            },
            messages: {
                id_user: {
                    required: "Se seleciono mal el usuario.",
                },
                profile: {
                    required: "Por favor, selecciona una perfil."
                },
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('error');
                element.closest('.groupMainTextInput').append(error);
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
                var formData = $(form).serializeArray();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/customer_account/update_profile",
                    type: 'POST',
                    data: formData,

                    success: function(response) {
                        generates_toasts_success("Perfil actualizado", "Actualizacion correcta")
                        $("#update_profile_button").prop("disabled", false);
                        $('#update_profile_modal').modal('hide')
                    },
                    error: function(xhr, status, error) {
                        generates_toasts_error("No se actualizó el perfil", "Error: " + error);
                        $("#update_profile_button").prop("disabled", false);;
                    }
                });
            }
        });

</script>
@endpush

