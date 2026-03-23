<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/login_style.css') }}">

    <!-- Boxicons CSS -->
    <link href='{{ asset('css/boxicons-master/css/boxicons.min.css') }}' rel='stylesheet'>

    <!-- FontAwesome CSS -->
    <link href="{{ asset('css/fontawesome/css/all.min.css') }}" rel="stylesheet">

    <!-- logo -->
    <link rel="icon" href="{{ asset('img/rpro_nav_logo_p_big.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/apple_touch_icon_180x180.png') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Login Form</title>

</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-in">
            <form class="form_div" action="{{ route('auth#homePage') }}" method="post">
                @csrf
                <h1 class="sign_in_header">Sign In</h1>
                <br>
                <input type="text" placeholder="Username" name="username" id="username">
                <input type="password" placeholder="Password" name="password" id="password">
                @if (session('wrong_username_password'))
                    <span style="color: red">{{ session('wrong_username_password') }}</span>
                @endif
                <!-- <input type="text" placeholder="Gate Number" id="gate_number_input"> -->
                <br>
                <button type="submit">Sign In</button>
            </form>
        </div>
        @if (session('not_registered'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('not_registered') }}',
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1 class="welcome_lbl">Welcome Back!</h1>
                    <p class="description_lbl">Enter your personal details to use all of Restaurant-Pro features</p>
                </div>

            </div>
        </div>
    </div>
</body>

<script src="../Script/script.js"></script>


</html>
