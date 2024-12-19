@extends('layouts.master')

@section('title', 'Registar cuenta de streaming')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@push('styles')
@endpush
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row container quote-box ">
    <div class="col-12 col-md-6 col-lg-6">
        <h1>Registra la cuenta de streaming</h1>
    </div>
    <!-- Asegúrate de usar un formulario real -->
    <form id="registrationForm">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Tipo de cuenta</label>
                    <select name="name_service" id="name_service">
                        <option value="">Selecciona una opción</option>
                        <option value="Netflix">Netflix</option>
                        <option value="Spotify">Spotify</option>
                        <option value="Disney">Disney</option>
                        <option value="Hbo_max">Hbo max</option>
                        <option value="Paramount">Paramount</option>
                        <option value="Amazon">Amazon prime</option>
                        <option value="Vix">Vix</option>
                        <option value="Youtube">Youtube</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Correo</label>
                    <input type="email" id="email" name="email">
                </div>
            </div>
        </div><div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Contaseña</label>
                    <input type="password" id="password" name="password">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Precio</label>
                    <input type="text" id="prices" name="prices">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Tipo de pago</label>
                    <select name="type_payment" id="type_payment">
                        <option value="">Selecciona una opción</option>
                        <option value="Gifcard">Gifcard</option>
                        <option value="Card">Tarjeta</option>
                        <option value="Gratis">Gratis</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row justify-content-center d-none">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Nombre del banco</label>
                    <input type="text" id="bank_name" name="bank_name">
                </div>
            </div>
        </div>
        <div class="row justify-content-center d-none">
            <div class="col-12 col-md-6 col-lg-6 ">
                <div class="groupMainTextInput">
                    <label>Ultimos 2 digitos de tarjeta</label>
                    <input type="text" id="card_number" name="card_number">
                </div>
            </div>
        </div>
        <div class="row justify-content-center d-none">
            <div class="col-12 col-md-6 col-lg-6 ">
                <div class="groupMainTextInput">
                    <label>Cuenta que paga</label>
                    <input type="text" id="account_pays" name="account_pays">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Fecha de creacion</label>
                    <input type="date" id="date_create" name="date_create">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Fecha de pago </label>
                    <input type="date" id="date_payment" name="date_payment">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Usuarios maximos por cuenta </label>
                    <input type="text" id="user_max" name="user_max" value="4">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <button class="success"type="submit" id="save_account_streaming" >Guardar cliente</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var fechaHoy = new Date();
        var dia = String(fechaHoy.getDate()).padStart(2, '0');
        var mes = String(fechaHoy.getMonth() + 1).padStart(2, '0');
        var anio = fechaHoy.getFullYear();
        var fechaFormateada = anio + '-' + mes + '-' + dia;
        $('#date_create').val(fechaFormateada);
        fechaHoy.setMonth(fechaHoy.getMonth() + 1);
        dia = String(fechaHoy.getDate()).padStart(2, '0');
        mes = String(fechaHoy.getMonth() + 1).padStart(2, '0');
        anio = fechaHoy.getFullYear();
        fechaFormateada = anio + '-' + mes + '-' + dia;
        $('#date_payment').val(fechaFormateada);

        $("#registrationForm").validate({
            rules: {
                prices: {
                    required: true,
                    minlength: 2
                },
                date_create: {
                    required: true,
                },
                name_service: {
                    required: true
                },
                type_payment: {
                    required: true,
                },
                date_payment: {
                    required: true,
                },
                user_max: {
                    required: true,
                }

            },
            messages: {
                prices: {
                    required: "Por favor, agrega un precio.",
                },
                date_create: {
                    required: "Por favor, agrega la fecha de creacion.",
                },
                name_service: {
                    required: "Por favor, selecciona una cuenta de streaming."
                },
                type_payment: {
                    required: "Por favor, agrega el tipo de pago.",
                },
                date_payment: {
                    required: "Por favor, ingresa la fecha de expiracion.",
                },
                user_max: {
                    required: "Por favor, ingresa los usuarios maximos.",
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

                $("#save_account_streaming").attr("disabled", true);
                var formData = $(form).serializeArray();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route ('add_account')}}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {

                        setTimeout(() => {
                            $("#save_account_streaming").attr("disabled", false);
                        }, 5000);
                        toastr.success('Cuenta agregada', 'Guardado', {
                            timeOut: 5000,
                            positionClass: 'toast-top-right',
                            closeButton: true,
                            progressBar: true,
                            "toastClass": "custom-toast-success",
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
        });
        $('#type_payment').change(function() {
            var value = $(this).val();

            if (value === 'Card') {
                console.log($('#bank_name').parents().eq(2))
                $('#account_pays').parents().eq(2).addClass('d-none');
                $('#bank_name').parents().eq(2).removeClass('d-none');
                $('#card_number').parents().eq(2).removeClass('d-none');
            }else if(value === 'Gifcard'){
                $('#bank_name').parents().eq(2).addClass('d-none');
                $('#card_number').parents().eq(2).addClass('d-none');
                $('#account_pays').parents().eq(2).removeClass('d-none');
            }else if(value === "Gratis"){
                $('#bank_name').parents().eq(2).addClass('d-none');
                $('#card_number').parents().eq(2).addClass('d-none');
                $('#account_pays').parents().eq(2).addClass('d-none');
            }
        });
    });
</script>
@endpush


