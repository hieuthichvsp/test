<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Quản lý thiết bị">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <title>@yield('title', 'Trang chủ')</title>
    @include('layouts.styles')
</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                @include('layouts.sidebar')
            </div>
        </nav>
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="background-color: #2f4050; border-color:#2f4050;" href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    @include('layouts.profile')
                </nav>
            </div>
            <div class="main-content">
                @yield('content')
            </div>
            @include('layouts.footer')
        </div>
    </div>
    @include('layouts.scripts')
    @include('layouts.toast')
</body>

</html>