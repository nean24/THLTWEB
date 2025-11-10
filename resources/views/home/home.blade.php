@extends('layouts.app')
@section('title','Bảng tin 🌸')

@section('content')
<div class="space-y-4">

  {{-- Composer --}}
  <section class="bg-[#E6DEC7]/95 border border-[#D2D1F0] rounded-2xl p-4 space-y-3 shadow-sm">
    <div class="flex items-center gap-3">
      <img class="w-10 h-10 rounded-full border border-[#D2D1F0] shadow-sm"
           src="{{ asset('images/default-avatar.webp') }}" alt="me">
      <div class="flex-1">
        <textarea id="composer" rows="3" maxlength="500"
          class="w-full p-3 rounded-xl border border-[#BFD8FF] bg-[#F8F9FA] focus:outline-none focus:ring-2 focus:ring-[#CDE8C5]"
          placeholder="Bạn đang nghĩ gì?"></textarea>
        <div class="flex items-center justify-between mt-1 text-xs text-[#5A5A5C]">
          <span id="loginHint" class="hidden">
            Bạn chưa đăng nhập. <a href="{{ route('login') }}" class="text-[#3A3A3C] underline">Đăng nhập</a> để đăng bài.
          </span>
          <span id="charCount">0/500</span>
        </div>
      </div>
    </div>
    <div class="flex justify-end">
      <button id="postBtn"
        class="px-4 py-2 rounded-full bg-[#CDE8C5] hover:bg-[#B8D9AF] text-[#3A3A3C] transition">
        Đăng 🌷
      </button>
    </div>
  </section>

  <h2 class="text-sm font-medium text-[#5A5A5C]">Bài viết mới</h2>

  {{-- Feed container để JS render --}}
  <div id="posts" class="space-y-4"></div>

</div>
@endsection
