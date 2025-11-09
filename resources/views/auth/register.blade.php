@extends('layouts.app')
@section('title','Đăng ký')

@section('content')
<div class="space-y-6 max-w-md mx-auto">

  <h1 class="text-xl font-semibold text-[#54434f]">Tạo tài khoản 🎀</h1>

  <label class="block text-sm text-[#6f5b69]">Tên người dùng</label>
  <input id="reg_username" type="text"
         class="w-full p-3 rounded-xl border border-[#f3dde9] bg-[#fffafb] focus:ring-2 focus:ring-[#f3c8df]"
         placeholder="vd: nean">

  <label class="block text-sm text-[#6f5b69] mt-2">Email</label>
  <input id="reg_email" type="email"
         class="w-full p-3 rounded-xl border border-[#f3dde9] bg-[#fffafb] focus:ring-2 focus:ring-[#f3c8df]"
         placeholder="vd: user@example.com">

  <label class="block text-sm text-[#6f5b69] mt-2">Mật khẩu</label>
  <input id="reg_password" type="password"
         class="w-full p-3 rounded-xl border border-[#f3dde9] bg-[#fffafb] focus:ring-2 focus:ring-[#f3c8df]"
         placeholder="••••••••">

  <button id="registerBtn"
          class="w-full py-3 rounded-xl bg-[#f6d4e5] hover:bg-[#f2bfd7] text-[#4b3b47] transition">
    Đăng ký
  </button>

  <p class="text-sm text-center text-[#a88a99]">
    Đã có tài khoản?
    <a href="{{ route('login') }}" class="text-[#d36c9d] underline">Đăng nhập</a>
  </p>

</div>
@endsection
