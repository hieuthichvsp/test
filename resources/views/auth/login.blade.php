@extends('layouts.guest')
@section('title', 'Đăng nhập')
@section('content')

<style>
    body {
        color: #000
    }

    .login-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 40px 50px 10px 50px;
        margin-top: 40px;
        max-width: 500px;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }

    .login-logo {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-logo img {
        max-width: 100px;
        height: auto;
    }

    .login-header {
        text-align: center;
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 20px;
    }


    .btn-login {
        width: 100%;
        padding: 10px 0;
        transition: all 0.3s ease;
    }

    a {
        color: #333;
        text-decoration: none;
    }

    .text-muted a:hover {
        color: #357ABD;
        text-decoration: none;

    }

    .alert-dismissible .close {
        top: -5px;
        right: -5px;
    }

    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-wrapper input {
        padding-right: 40px;
        width: 100%;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .toggle-password:hover {
        color: #333;
    }

    .control-label {
        font-weight: bold;
    }
</style>

<div class="container">
    <div class="login-container">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-responsive center-block">
        </div>
        <div class="login-header">Quản trị thiết bị</div>

        @if (session('updatePasswordSuccess'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="fa fa-check-circle"></i> {{ session('updatePasswordSuccess') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}" style="margin-bottom: 30px;">
                <label for="email" class="control-label">Email</label>
                <input id="email" type="email" class="form-control" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('matkhau') ? ' has-error' : '' }}">
                <label for="matkhau" class="control-label">Mật khẩu</label>
                <div class="password-wrapper">
                    <input id="matkhau" type="password" class="form-control" name="matkhau" required autocomplete="current-password">
                    <i class="fa fa-eye toggle-password" aria-hidden="true"></i>
                </div>
                @if ($errors->has('matkhau'))
                <span class="help-block">
                    <strong>{{ $errors->first('matkhau') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="checkbox">
                            <label style="font-weight: normal;">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span style="padding-top:2px;">Remember me</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6 text-right" style="padding-top: 10px;">
                        <a href="{{ route('password.request') }}" style="text-decoration: none;">Quên mật khẩu?</a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-login">
                    Đăng nhập
                </button>
            </div>

            <div class="form-group text-center">
                <div class="login-message alert d-none"></div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.toggle-password').on('click', function() {
            const input = $('#matkhau');
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            // Đổi icon giữa eye và eye-slash
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
@endsection