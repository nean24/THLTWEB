@extends('layouts.app')
@section('title','Đăng nhập')

@section('content')
<div class="space-y-6 max-w-md mx-auto">

  <h1 class="text-xl font-semibold text-[#3A3A3C]">Đăng nhập 🌷</h1>

  <label class="block text-sm text-[#5A5A5C]">Email</label>
  <input id="login_email" type="email"
         class="w-full p-3 rounded-xl border border-[#D2D1F0] bg-[#F8F9FA] focus:ring-2 focus:ring-[#BFD8FF]"
         placeholder="vd: user@example.com">

  <label class="block text-sm text-[#5A5A5C] mt-2">Mật khẩu</label>
  <input id="login_password" type="password"
         class="w-full p-3 rounded-xl border border-[#D2D1F0] bg-[#F8F9FA] focus:ring-2 focus:ring-[#BFD8FF]"
         placeholder="••••••••">

  <button id="loginBtn"
          class="w-full py-3 rounded-xl bg-[#CDE8C5] hover:bg-[#B8D9AF] text-[#3A3A3C] transition">
    Đăng nhập
  </button>

  <p class="text-sm text-center text-[#5A5A5C]">
    Chưa có tài khoản?
    <a href="{{ route('register') }}" class="text-[#3A3A3C] underline">Đăng ký</a>
  </p>

</div>
@endsection
