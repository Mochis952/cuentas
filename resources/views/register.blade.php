@extends('layouts.master')

@section('title', 'Registar usuarios')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@push('styles')
@endpush
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row container quote-box ">
    <div class="col-12 col-md-6 col-lg-6">
        <h1>Registra al cliente</h1>
    </div>
    <!-- Asegúrate de usar un formulario real -->
    <form id="registrationForm">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Nombre del cliente</label>
                    <input type="text" id="customer_name" name="customer_name">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Teléfono del cliente</label>
                    <input type="text" id="customer_phone_number" name="customer_phone_number">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Medio de contacto</label>
                    <select name="contact_medium" id="contact_medium">
                        <option value="-1">Selecciona una opción</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Whatsapp">Whatsapp</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <label>Cuenta de streaming</label>
                    <select name="account_streaming" id="account_streaming">
                        <option value="-1">Selecciona una opción</option>
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
                    <label>Meses pagados</label>
                    <input type="text" id="months_paid" name="months_paid" value="1">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="groupMainTextInput">
                    <button class="success"type="submit" id="save_client" >Guardar cliente</button>
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
            customer_name: {
                required: true,
                minlength: 3
            },
            account_streaming: {
                required: true
            },
            contact_medium:{
                required: true
            },
            months_paid:{
                required:true
            }
        },
        messages: {
            customer_name: {
                required: "Por favor, ingresa el nombre del cliente.",
                minlength: "El nombre debe tener al menos 3 caracteres."
            },
            account_streaming: {
                required: "Por favor, selecciona una cuenta de streaming."
            },
            contact_medium: {
                required: "Por favor, selecciona el medio de contacto"
            },
            months_paid: {
                required: "Por favor, coloca los meses pagados"
            }
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
        submitHandler: function(form) {
            $("#save_client").attr("disabled", true);
            var formData = {
                customer_name: $("#customer_name").val(),
                customer_phone_number: $("#customer_phone_number").val(),
                account_streaming: $("#account_streaming").val(),
                contact_method: $("#contact_medium").val()
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route ('add_customer')}}",
                type: 'POST',
                data: formData,

                success: function(response) {
                    account_details(response);
                    generates_toasts_success("Guardado!", "Cliente registrado!")
                    setTimeout(() => {
                        $("#save_client").attr("disabled", false);
                    }, 5000);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    generates_toasts_error("Error!", error)
                    $("#save_client").attr("disabled", false);
                }
            });
        }
    });
});
    function copy_text(event) {
        event.preventDefault();

        var $tempElement = $("<textarea>");
        $("body").append($tempElement);
        $tempElement.val($("#text_client").text()).select();
        document.execCommand("copy");
        $tempElement.remove();

        toastr.success('Texto copiado!', 'Copy', {
            timeOut: 1000,
            positionClass: 'toast-top-right',
            closeButton: true,
            progressBar: true,
            "toastClass": "custom-toast-success",
        });
    }
    function account_details(data_account){
        let email="";
        let password="";
        let perfil="";
        let pin ="";
        let container_text =
            `<div class="row justify-content-center mt-1">
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
    }
    function generates_toasts_success(main_text, secondary_text){
        toastr.success(secondary_text, main_text, {
            timeOut: 5000,
            positionClass: 'toast-top-right',
            closeButton: true,
            progressBar: true,
            "toastClass": "custom-toast-success",
        });
    }
    function generates_toasts_error(main_text, secondary_text){
        toastr.error(secondary_text, main_text, {
            timeOut: 5000,
            positionClass: 'toast-top-right',
            closeButton: true,
            progressBar: true,
            "toastClass": "custom-toast-error",
        });
    }

</script>
@endpush


