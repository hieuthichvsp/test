@extends('layouts.guest')
@section('title', 'Đổi mật khẩu')
@section('content')

<style>
    .update-password-container {
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

    .update-password-header {
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

    .help-block {
        color: #a94442;
        font-size: 0.875rem;
    }

    .btn-update-password,
    .btn-cancel {
        background-color: #4A90E2;
        border: none;
        color: white;
        font-weight: bold;
        padding: 10px 0;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-cancel {
        background-color: #6c757d;
    }

    .btn-update-password:hover,
    .btn-cancel:hover {
        opacity: 0.9;
    }

    .form-actions {
        margin-top: 20px;
    }
</style>

<div class="container">
    <div class="update-password-container">
        <div class="update-password-header">{{ __('Đổi mật khẩu') }}</div>

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('profile.passwordUpdate', ['id' => Auth::user()->id]) }}">
            @csrf
            @method('PUT')

            <div class="form-group{{ $errors->has('matkhau_old') ? ' has-error' : '' }}">
                <label for="matkhau_old" class="control-label">Mật khẩu cũ</label>
                <input id="matkhau_old" type="password" class="form-control" name="matkhau_old" required>
                @if ($errors->has('matkhau_old'))
                <span class="help-block">
                    <strong>{{ $errors->first('matkhau_old') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('matkhau_new') ? ' has-error' : '' }}">
                <label for="matkhau_new" class="control-label">Mật khẩu mới</label>
                <input id="matkhau_new" type="password" class="form-control" name="matkhau_new" required>
                @if ($errors->has('matkhau_new'))
                <span class="help-block">
                    <strong>{{ $errors->first('matkhau_new') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('matkhau_confirmation') ? ' has-error' : '' }}">
                <label for="matkhau_confirmation" class="control-label">Xác nhận mật khẩu mới</label>
                <input id="matkhau_confirmation" type="password" class="form-control" name="matkhau_confirmation" required>
                @if ($errors->has('matkhau_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('matkhau_confirmation') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-update-password">
                            {{ __('Đổi mật khẩu') }}
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('home') }}" class="btn btn-cancel">
                            Hủy
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection