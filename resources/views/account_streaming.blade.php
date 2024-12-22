@extends('layouts.master')

@section('title', 'Cuentas de streaming')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                                <th scope="col" style="width: 30%">Cuenta</th>
                                <th scope="col">Correo</th>
                                <th scope="col" style="width: 11%">🧑</th>
                                <th scope="col" style="width: 12%">Max</th>
                                <th scope="col" style="width: 9%">⚡</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_account_streaming">
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
            url: "{{ route ('index_account_streaming')}}",
            type: 'GET',
            success: function(response) {
                console.log(response);
                response.forEach(function(element, index) {
                    let customer_accounts = element.customer_accounts;
                    var data_customers = "";
                    customer_accounts.forEach(function(element2, index) {
                        data_customers = data_customers + `
                            <div class="col-6">
                                <p>Cliente: ${element2.customer.name_customer_facebook}</p>
                                <p>Metodo de contacto: ${element2.customer.contact_method}</p>
                                <p>Proxima fecha de pago: ${element2.date_expiration}</p>
                                <p>Perfil: ${element2.profile}</p>
                                <hr>
                            </div>
                        `;
                    });

                    console.log(data_customers)
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
                            <td>${element.user_active}</td>
                            <td>${element.user_max}</td>
                            <td>${element.status == "active" ? '🟢' : '🔴'}</td>
                            </tr>
                        <tr id="data_account_${element.id}"class="${color_tr[element.name_service]} d-none">
                            <td colspan="1">
                                <div class="description-container col-5">
                                    <p><strong>Datos de la cuenta:</strong></p>
                                    <p>Correo:<br>${element.email}</p>
                                    <p>Contraseña:<br>${element.password}</p>
                                    <p>Precio:${element.prices}</p>
                                    <p>Tipo de cuenta:<br>${element.type_account}</p>
                                    <p>Metodo de pago:<br>${element.type_payment}</p>
                                    ${element.bank_name ? `<p>Banco: ${element.bank_name}</p>` : ''}
                                    ${element.card_number ? `<p>Num Tarjeta: ${element.card_number}</p>` : ''}
                                    ${element.account_pays ? `<p>Cuenta que paga:<br>${element.account_pays}</p>` : ''}
                                    <p>Fecha de pago:<br>${element.date_payment}</p>
                                </div>
                            </td>
                            <td colspan="4">
                                <div class="description-container col-12 row">
                                    <p><strong>Cliente datos</strong></p>
                                    ${data_customers}
                                </div>
                            </td>
                        </tr>
                        `;

                $('#tbody_account_streaming').append(account_streaming);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
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

