@extends('layouts.master')

@section('title', 'Cuentas de streaming')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
@endpush
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                <th scope="col" style="width: 23%">Cliente</th>
                                <th scope="col" style="width: 23%">Cuenta</th>
                                <th scope="col" style="width: 9%">📲</th>
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
<script>
    $(document).ready(function() {
        get_data_account_streaming();
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

                    let customer_accounts = element.customer_accounts;
                    var dateExpiration = null;
                    var name_service = null;
                    var id = null
                    customer_accounts.forEach(function(element, index) { //todas las cuentas que tienen
                        dateExpiration = element.date_expiration
                        name_service = element.account_streaming.name_service
                        id = element.id
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
                            <td>${contact_icon[element.contact_method]}</td>
                            <td>${day}</td>
                            <td>
                                <button type="button" class="btn btn-success p-2" style="font-size: .7rem !important;" onclick="bill_payment(${id},this)">Pago</button>
                                <button type="button" class="btn btn-danger p-2" style="font-size: .7rem !important;" onclick="delete_user(${id},this)">Eliminar</button>
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

</script>
@endpush

