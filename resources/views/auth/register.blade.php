@extends('layouts.app')
@section('title','Đăng ký')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="space-y-6 max-w-md w-full">

    <h1 class="text-xl md:text-2xl font-semibold text-primary text-center">Tạo tài khoản</h1>

    <div>
      <label class="form-label">Email</label>
      <input id="reg_email" type="email" class="form-input" placeholder="vd: user@example.com" required>
    </div>

    <div>
      <label class="form-label">Mật khẩu</label>
      <input id="reg_password" type="password" class="form-input" placeholder="••••••••" required>
    </div>

    <div>
      <label class="form-label">Nhập lại mật khẩu</label>
      <input id="reg_confirm_password" type="password" class="form-input" placeholder="••••••••" required>
    </div>

    <div id="reg_error" class="text-xs text-red-500 hidden"></div>

    <button id="registerBtn" class="btn btn-primary w-full">
      Đăng ký
    </button>

    <p class="text-sm text-center text-muted">
      Đã có tài khoản?
      <a href="{{ route('login') }}" class="link-primary">Đăng nhập</a>
    </p>

  </div>
</div>
@endsection
