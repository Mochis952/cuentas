@extends('layouts.master')

@section('title', 'Cuentas de streaming')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="list_container" class="row container quote-box ">
        <div class="col-12 col-md-6 col-lg-6">
            <h1>Cuentas disponibles</h1>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Tipo de cuenta</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Plan contratado</th>
                    <th scope="col">Metodo de pago</th>
                    <th scope="col">Fecha de pago</th>
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
                        <tr class="${color_tr[element.name_service]}">
                            <th scope="row">${element.name_service}</th>
                            <td>correo</td>
                            <td>${element.prices}</td>
                            <td>${element.type_account}</td>
                            <td>${element.type_payment}</td>
                            <td>${element.date_payment}</td>
                            <td>${element.name_service}</td>
                            <td>${element.user_max}</td>
                            <td>${element.status}</td>
                        </tr>`
                console.log(account_streaming)
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
</script>
@endpush

