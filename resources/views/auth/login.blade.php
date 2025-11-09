@extends('layouts.app')
@section('title','Đăng nhập')

@section('content')
<div class="space-y-6 max-w-md mx-auto">

  <h1 class="text-xl font-semibold text-[#54434f]">Đăng nhập 🌷</h1>

  <label class="block text-sm text-[#6f5b69]">Email</label>
  <input id="login_email" type="email"
         class="w-full p-3 rounded-xl border border-[#f3dde9] bg-[#fffafb] focus:ring-2 focus:ring-[#f3c8df]"
         placeholder="vd: user@example.com">

  <label class="block text-sm text-[#6f5b69] mt-2">Mật khẩu</label>
  <input id="login_password" type="password"
         class="w-full p-3 rounded-xl border border-[#f3dde9] bg-[#fffafb] focus:ring-2 focus:ring-[#f3c8df]"
         placeholder="••••••••">

  <button id="loginBtn"
          class="w-full py-3 rounded-xl bg-[#f6d4e5] hover:bg-[#f2bfd7] text-[#4b3b47] transition">
    Đăng nhập
  </button>

  <p class="text-sm text-center text-[#a88a99]">
    Chưa có tài khoản?
    <a href="{{ route('register') }}" class="text-[#d36c9d] underline">Đăng ký</a>
  </p>

</div>
@endsection
