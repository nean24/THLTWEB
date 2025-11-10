@extends('layouts.app')
@section('title','Bảng tin 🌸')

@section('content')
<div class="space-y-4">

  {{-- Composer --}}
  <section class="bg-white/95 border border-[#f3deea] rounded-2xl p-4 space-y-3 shadow-sm">
    <div class="flex items-center gap-3">
      <img class="w-10 h-10 rounded-full border border-[#f2dfe6] shadow-sm"
           src="{{ asset('images/default-avatar.webp') }}" alt="me">
      <div class="flex-1">
        <textarea id="composer" rows="3" maxlength="500"
          class="w-full p-3 rounded-xl border border-[#f3dde9] bg-[#fff7fb] focus:outline-none focus:ring-2 focus:ring-[#f3c8df]"
          placeholder="Bạn đang nghĩ gì?"></textarea>
        <div class="flex items-center justify-between mt-1 text-xs text-[#a88a99]">
          <span id="loginHint" class="hidden">
            Bạn chưa đăng nhập. <a href="{{ route('login') }}" class="text-[#d36c9d] underline">Đăng nhập</a> để đăng bài.
          </span>
          <span id="charCount">0/500</span>
        </div>
      </div>
    </div>
    <div class="flex justify-end">
      <button id="postBtn"
        class="px-4 py-2 rounded-full bg-[#f6d4e5] hover:bg-[#f2bfd7] text-[#4b3b47] transition">
        Đăng 🌷
      </button>
    </div>
  </section>

  <h2 class="text-sm font-medium text-[#a88a99]">Bài viết mới</h2>

  {{-- Feed container để JS render --}}
  <div id="posts" class="space-y-4"></div>

</div>
@endsection
