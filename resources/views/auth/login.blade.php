@extends('layouts.app')
@section('title','Đăng nhập')

@section('content')
<div class="space-y-6 max-w-md mx-auto">

  <h1 class="text-xl font-semibold text-surface">Đăng nhập 🌷</h1>

  <label class="form-label text-surface/80">Email</label>
  <input id="login_email" type="email" class="form-input" placeholder="vd: user@example.com">

  <label class="form-label text-surface/80 mt-2">Mật khẩu</label>
  <input id="login_password" type="password" class="form-input" placeholder="••••••••">

  <button id="loginBtn" class="btn btn-primary w-full">
    Đăng nhập
  </button>

  <p class="text-sm text-center text-surface/80">
    Chưa có tài khoản?
    <a href="{{ route('register') }}" class="link-primary">Đăng ký</a>
  </p>

</div>
@endsection
