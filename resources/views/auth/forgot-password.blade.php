@extends('layouts.guest')
@section('title', 'Quên mật khẩu')
@section('content')

<style>
    .reset-password-container {
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

    .reset-password-header {
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

    .btn-reset-password {
        background-color: #4A90E2;
        border: none;
        color: white;
        font-weight: bold;
        padding: 10px 0;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-reset-password:hover {
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
</style>

<div class="container">
    <div class="reset-password-container">
        <div class="reset-password-header">{{ __('Khôi phục mật khẩu') }}</div>

        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('status') }}
        </div>
        @endif

        <div class="text-center mb-4">
            <p class="text-muted">Vui lòng nhập địa chỉ email đã đăng ký. Chúng tôi sẽ gửi link đặt lại mật khẩu qua email của bạn.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">Email</label>
                <input id="email" type="email" class="form-control" name="email"
                    value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-reset-password">
                    Gửi link khôi phục
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