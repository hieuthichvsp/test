<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản lý thiết bị')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #ddd;
            background-image: url('{{ asset("images/background.webp") }}');
            background-position: center;
            background-repeat: no-repeat;
            transition: background-image 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
    @yield('styles')
</head>

<body>
    @yield('content')
</body>
<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
@yield('scripts')

</html>