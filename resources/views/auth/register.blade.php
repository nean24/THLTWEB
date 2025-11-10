@extends('layouts.app')
@section('title','Đăng ký')

@section('content')
<div class="space-y-6 max-w-md mx-auto">

  <h1 class="text-xl font-semibold text-surface">Tạo tài khoản 🎀</h1>

  <label class="form-label text-surface/80">Tên người dùng</label>
  <input id="reg_username" type="text" class="form-input" placeholder="vd: nean">

  <label class="form-label text-surface/80 mt-2">Email</label>
  <input id="reg_email" type="email" class="form-input" placeholder="vd: user@example.com">

  <label class="form-label text-surface/80 mt-2">Mật khẩu</label>
  <input id="reg_password" type="password" class="form-input" placeholder="••••••••">

  <button id="registerBtn" class="btn btn-primary w-full">
    Đăng ký
  </button>

  <p class="text-sm text-center text-surface/80">
    Đã có tài khoản?
    <a href="{{ route('login') }}" class="link-primary">Đăng nhập</a>
  </p>

</div>
@endsection
