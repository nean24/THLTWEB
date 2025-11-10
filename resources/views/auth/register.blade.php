@extends('layouts.app')
@section('title','Đăng ký')

@section('content')
<div class="space-y-6 max-w-md mx-auto">

  <h1 class="text-xl font-semibold text-[#3A3A3C]">Tạo tài khoản 🎀</h1>

  <label class="block text-sm text-[#5A5A5C]">Tên người dùng</label>
  <input id="reg_username" type="text"
         class="w-full p-3 rounded-xl border border-[#D2D1F0] bg-[#F8F9FA] focus:ring-2 focus:ring-[#BFD8FF]"
         placeholder="vd: nean">

  <label class="block text-sm text-[#5A5A5C] mt-2">Email</label>
  <input id="reg_email" type="email"
         class="w-full p-3 rounded-xl border border-[#D2D1F0] bg-[#F8F9FA] focus:ring-2 focus:ring-[#BFD8FF]"
         placeholder="vd: user@example.com">

  <label class="block text-sm text-[#5A5A5C] mt-2">Mật khẩu</label>
  <input id="reg_password" type="password"
         class="w-full p-3 rounded-xl border border-[#D2D1F0] bg-[#F8F9FA] focus:ring-2 focus:ring-[#BFD8FF]"
         placeholder="••••••••">

  <button id="registerBtn"
          class="w-full py-3 rounded-xl bg-[#CDE8C5] hover:bg-[#B8D9AF] text-[#3A3A3C] transition">
    Đăng ký
  </button>

  <p class="text-sm text-center text-[#5A5A5C]">
    Đã có tài khoản?
    <a href="{{ route('login') }}" class="text-[#3A3A3C] underline">Đăng nhập</a>
  </p>

</div>
@endsection
