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

@endsection
@push('scripts')
{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Configuración global de AJAX para incluir el token CSRF en todas las peticiones.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Carga inicial de datos.
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
                                    <p class="mb-0"><strong>Perfil:</strong> ${element2.profile}</p>
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
</script>@endpush
