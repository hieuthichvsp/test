@extends('layouts.guest')
@section('title', 'Đặt lại mật khẩu')
@section('content')

<style>
    .login-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 30px 40px;
        margin-top: 80px;
        max-width: 500px;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }

    .login-header {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 500;
        color: #555;
    }

    .text-muted {
        color: #999;
        font-size: 14px;
        text-align: center;
    }

    .btn-login {
        background-color: #4A90E2;
        border: none;
        color: white;
        font-weight: bold;
        padding: 10px 0;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-login:hover {
        background-color: #357ABD;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(74, 144, 226, 0.4);
    }

    .text-center a {
        color: #4A90E2;
        text-decoration: underline;
    }

    .text-center a:hover {
        color: #357ABD;
    }

    .help-block {
        color: #a94442;
        font-size: 0.875rem;
    }
</style>

<div class="container">
    <div class="login-container">
        <div class="login-header">{{ __('Đặt lại mật khẩu') }}</div>

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('error') }}
        </div>
        @endif

        <div class="text-center mb-4">
            <p class="text-muted">Vui lòng nhập mật khẩu mới của bạn.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">Email</label>
                <input id="email" type="email" class="form-control" name="email"
                    value="{{ $email ?? old('email') }}" required autofocus>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('matkhau') ? ' has-error' : '' }}">
                <label for="matkhau" class="control-label">Mật khẩu mới</label>
                <input id="matkhau" type="password" class="form-control" name="matkhau" required>
                @if ($errors->has('matkhau'))
                <span class="help-block">
                    <strong>{{ $errors->first('matkhau') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group">
                <label for="matkhau-confirm" class="control-label">Xác nhận mật khẩu</label>
                <input id="matkhau-confirm" type="password" class="form-control" name="matkhau_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-login">
                    Đặt lại mật khẩu
                </button>
            </div>

            <div class="text-center mt-4">
                Bạn đã nhớ mật khẩu?
                <a href="{{ route('login') }}" style="text-decoration: none;">
                    Đăng nhập ngay
                </a>
            </div>
        </form>
    </div>
</div>

@endsection