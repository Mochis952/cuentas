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
                    <select name="account_streaming" id="account_streaming">
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
        <div class="row justify-content-center d-none">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Fecha de pago</label>
                    <input type="date" id="date_payment" name="date_payment">
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
                    <input type="text" id="type_payment" name="type_payment">
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
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6 d-none">
                <div class="groupMainTextInput">
                    <label>Ultimos 2 digitos de tarjeta</label>
                    <input type="text" id="card_number" name="card_number">
                </div>
            </div>
        </div>
        <div class="row justify-content-center d-none">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Fecha de vencimiento de la cuenta </label>
                    <input type="date" id="date_expire" name="date_expire">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
    // Configurar la validación del formulario
    $("#registrationForm").validate({
        rules: {
            prices: {
                required: true,
                minlength: 2
            },
            date_payment: {
                required: true,
            },
            account_streaming: {
                required: true
            },
            type_payment: {
                required: true,
            },
            bank_name: {
                required: true,
            },
            card_number: {
                required: true,
            },
            date_expire: {
                required: true,
            }
        },
        messages: {
            prices: {
                required: "Por favor, agrega un precio.",
            },
            date_payment: {
                required: "Por favor, agrega la fecha de pago.",
            },
            account_streaming: {
                required: "Por favor, selecciona una cuenta de streaming."
            },
            type_payment: {
                required: "Por favor, agrega el tipo de pago.",
            },
            bank_name: {
                required: "Por favor, ingresa el nombre del banco.",
            },
            card_number: {
                required: "Por favor, ingresa los 2 digitos finales de la tarjeta.",
            },
            date_expire: {
                required: "Por favor, ingresa la fecha de expiracion.",
            }
        },
        errorElement: 'div',  // El elemento HTML donde se mostrará el error
        errorPlacement: function (error, element) {
            error.addClass('error'); // Añadir la clase .error a los mensajes
            element.closest('.groupMainTextInput').append(error); // Añade el error al contenedor
        },
        highlight: function (element) {
            $(element).addClass('is-invalid'); // Añadir clase de error
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid'); // Remover clase de error si está bien
        },
        submitHandler: function(form) {
            $("#save_account_streaming").attr("disabled", true);
            var formData = {
                date_payment: $("#date_payment").val(),
                prices: $("#prices").val(),
                account_streaming: $("#account_streaming").val(),
                type_payment: $("#type_payment").val(),
                bank_name: $("#bank_name").val(),
                card_number: $("#card_number").val(),
                date_expire: $("#date_expire").val()

            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route ('add_customer')}}", // Cambia esto a la URL de tu servidor
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log("Respuesta del servidor:", response);
                    setTimeout(() => {
                        $("#save_client").attr("disabled", false);
                    }, 5000);
                    toastr.success('Cliente registrado!', 'Guardado', {
                        timeOut: 5000, 
                        positionClass: 'toast-top-right', 
                        closeButton: true, 
                        progressBar: true,
                        "toastClass": "custom-toast-success", 
                    });
                    let email="";
                    let password="";
                    let perfil="";
                    let pin ="";
                    let container_text =    `<div class="row justify-content-center mt-1">
                                                <div class="col-11 col-md-5 col-lg-5 container_text" >
                                                    <div id="message_client" class="box-header row">
                                                        <div class="col-10">
                                                            <span>Datos de la cuenta del cliente</span>
                                                        </div>
                                                        <button class="col-2 bg-transparent border-0" onclick="copy_text(event)">
                                                        <svg id="copy-button" class="copy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                            <path d="M19 21H9c-1.1 0-2-.9-2-2V7h2v12h10v2zm3-16h-8c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 12h-8V7h8v10zM15 3H5c-1.1 0-2 .9-2 2v12h2V5h10V3z"/>
                                                        </svg>
                                                        Copiar
                                                        </button>
                                                        <br>
                                                        <span id="text_client" class="col-10 p-0">
                                                            Correo: ${email} \n 
                                                            Contraseña: ${password} \n
                                                            Perfil: ${perfil} \n
                                                            Pin: ${pin}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>`;
                $("#registrationForm").append(container_text);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    $("#save_client").attr("disabled", false);
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
});
    function copy_text(event) {
        event.preventDefault(); // Evita que el botón recargue la página

        // Selecciona el contenido del span
        var $tempElement = $("<textarea>");
        $("body").append($tempElement);
        $tempElement.val($("#text_client").text()).select();
        document.execCommand("copy");
        $tempElement.remove(); // Elimina el elemento temporal

        // Mostrar mensaje de éxito (opcional)
        toastr.success('Texto copiado!', 'Copy', {
                        timeOut: 1000, 
                        positionClass: 'toast-top-right', 
                        closeButton: true, 
                        progressBar: true,
                        "toastClass": "custom-toast-success", 
                    });
    }

</script>
@endpush


