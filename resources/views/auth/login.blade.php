@extends('layouts.app')
@section('title','Đăng nhập')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="space-y-6 max-w-md w-full">

    <h1 class="text-xl md:text-2xl font-semibold text-primary text-center">Đăng nhập</h1>

    <div>
      <label class="form-label">Email</label>
      <input id="login_email" type="email" class="form-input" placeholder="vd: user@example.com">
    </div>

    <div>
      <label class="form-label">Mật khẩu</label>
      <input id="login_password" type="password" class="form-input" placeholder="••••••••">
    </div>

    <button id="loginBtn" class="btn btn-primary w-full">
      Đăng nhập
    </button>

    <p class="text-sm text-center text-muted">
      Chưa có tài khoản?
      <a href="{{ route('register') }}" class="link-primary">Đăng ký</a>
    </p>

  </div>
</div>
@endsection
