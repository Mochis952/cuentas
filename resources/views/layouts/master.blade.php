<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-reboot.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>@yield('title', 'Master')</title>


    @stack('styles')
</head>
<body>
    <nav class="row navbar navbar-expand-lg bg-light menu">
        <div class="logo col-3">
            <!-- <img src="{{ asset('img/logo.png') }}" alt="logo" style="width:100%"> -->
        </div>
        <div class="col-lg-6">
            <ul class="navbar-nav d-flex flex-row justify-content-around w-100">
                <li class="nav-item">
                    <a href="{{ route('register') }}"
                        class="nav-link {{ Route::is('register') ? 'active' : '' }}">
                        Registrar clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user_list') }}"
                        class="nav-link {{ Route::is('user_list') ? 'active' : '' }}">
                        Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('account_streaming') }}"
                        class="nav-link {{ Route::is('account_streaming') ? 'active' : '' }}">
                        Cuentas
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="master">
        @yield('content')
    </div>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
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
    @stack('scripts')
</body>
</html>
