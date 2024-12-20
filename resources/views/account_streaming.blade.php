@extends('layouts.master')

@section('title', 'Cuentas de streaming')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="list_container" class="row container quote-box table-responsive">
        <div class="col-12 col-md-6 col-lg-6">
            <h1>Cuentas disponibles</h1>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Tipo de cuenta</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Usuarios activos</th>
                    <th scope="col">Usuarios maximos</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody id="tbody_account_streaming">
            </tbody>
        </table>
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
            url: "{{ route ('index_account_streaming')}}",
            type: 'GET',
            success: function(response) {
                response.forEach(function(element, index) {
                    let color_tr = {
                        "Netflix": "table-danger",
                        "Spotify": "table-success",
                        "Disney": "table-info",
                        "Hbo_max": "table-purple",
                        "Paramount": "table-light",
                        "Amazon": "table-primary",
                        "Vix": "table-warning",
                        "Youtube": "table-orange"
                    }
                    let account_streaming = `
                        <tr class="${color_tr[element.name_service]}" style="cursor:pointer;" onclick="show_hide_data_acount(${element.id})" >
                            <th scope="row">${element.name_service}</th>
                            <td>${element.email}</td>
                            <td></td>
                            <td>${element.user_max}</td>
                            <td>${element.status}</td>
                        </tr>
                        <tr id="data_account_${element.id}"class="${color_tr[element.name_service]} d-none">
                            <td colspan="2">
                                <div class="description-container col-5">
                                    <p><strong>Datos de la cuenta:</strong></p>
                                    <p>Correo: ${element.email}</p>
                                    <p>Contraseña: ${element.password}</p>
                                    <p>Precio: ${element.prices}</p>
                                    <p>Tipo de cuenta: ${element.type_account}</p>
                                    <p>Metodo de pago: ${element.type_payment}</p>
                                    <p>Banco: ${element.bank_name}</p>
                                    <p>Num Tarjeta: ${element.card_number}</p>
                                    <p>Cuenta que paga: ${element.account_pays}</p>
                                    <p>Fecha de pago: ${element.date_payment}</p>
                                </div>
                            </td>
                            <td colspan="3">
                                <div class="description-container col-2">
                                    <p><strong>Cliente datos</strong></p>
                                </div>
                            </td>
                        </tr>
                        `;

                $('#tbody_account_streaming').append(account_streaming);
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
</script>
@endpush

