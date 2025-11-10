@extends('layouts.app')
@section('title','Bảng tin 🌸')

@section('content')
<div class="space-y-4">

  {{-- Composer --}}
  <section class="post-composer p-4 space-y-3 shadow-sm">
    <div class="flex items-center gap-3">
      <img class="w-10 h-10 rounded-full border border-default shadow-sm"
           src="{{ asset('images/default-avatar.webp') }}" alt="me">
      <div class="flex-1">
        <textarea id="composer" rows="3" maxlength="500"
          class="form-input bg-background text-surface focus:outline-none focus:ring-2 focus:ring-button-primary placeholder-surface/60"
          placeholder="Bạn đang nghĩ gì?"></textarea>
        <div class="flex items-center justify-between mt-1 text-xs text-surface/80">
          <span id="loginHint" class="hidden">
            Bạn chưa đăng nhập. <a href="{{ route('login') }}" class="link-primary">Đăng nhập</a> để đăng bài.
          </span>
          <span id="charCount">0/500</span>
        </div>
      </div>
    </div>
    <div class="flex justify-end">
      <button id="postBtn" class="btn btn-primary">
        Đăng 🌷
      </button>
    </div>
  </section>

  <h2 class="text-sm font-medium text-surface/80">Bài viết mới</h2>

  {{-- Feed container để JS render --}}
  <div id="posts" class="space-y-4"></div>

</div>
@endsection
