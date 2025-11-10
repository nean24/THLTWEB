@extends('layouts.app')
@section('title','Đăng ký')

@section('content')
<div class="space-y-6 max-w-md mx-auto">

  <h1 class="text-xl font-semibold text-primary">Tạo tài khoản</h1>

  <div>
    <label class="form-label">Email</label>
    <input id="reg_email" type="email" class="form-input" placeholder="vd: user@example.com">
  </div>

  <div>
    <label class="form-label">Mật khẩu</label>
    <input id="reg_password" type="password" class="form-input" placeholder="••••••••">
  </div>

  <div>
    <label class="form-label">Nhập lại mật khẩu</label>
    <input id="reg_confirm_password" type="password" class="form-input" placeholder="••••••••">
  </div>

  <button id="registerBtn" class="btn btn-primary w-full">
    Đăng ký
  </button>

  <p class="text-sm text-center text-muted">
    Đã có tài khoản?
    <a href="{{ route('login') }}" class="link-primary">Đăng nhập</a>
  </p>

</div>
@endsection
