@extends('layouts.app')
@section('title','Bảng tin')

@section('content')
<div class="space-y-4 px-4 md:px-0">

  {{-- Composer --}}
  <section class="post-composer p-3 md:p-4 space-y-3 shadow-sm">
    <div class="flex items-center gap-3">
      <img class="w-8 h-8 md:w-10 md:h-10 rounded-full border border-default shadow-sm"
           src="{{ asset('images/default-avatar.webp') }}" alt="me">
      <div class="flex-1">
        <textarea id="composer" rows="2" md:rows="3" maxlength="500"
          class="form-input focus:outline-none focus:ring-2 focus:ring-button-primary text-sm md:text-base"
          placeholder="Bạn đang nghĩ gì?"></textarea>
        <div class="flex items-center justify-between mt-1 text-xs text-muted">
          <span id="loginHint" class="text-primary hidden">
            Bạn chưa đăng nhập. <a href="{{ route('login') }}" class="link-primary">Đăng nhập</a> để đăng bài.
          </span>
          <span id="charCount">0/500</span>
        </div>
      </div>
    </div>
    <div class="flex justify-end">
      <button id="postBtn" class="btn btn-primary text-sm">
        Đăng
      </button>
    </div>
  </section>

  <h2 class="text-sm font-medium text-muted px-2">Bài viết mới</h2>

  {{-- Feed container để JS render --}}
  <div id="posts" class="space-y-4"></div>

</div>

{{-- Onboarding Modal (ẩn mặc định) --}}
<div id="onboardingModal"
     class="modal-backdrop fixed inset-0 z-50 hidden">
  <div class="modal relative mx-auto mt-24 w-[92%] max-w-md">
    <div class="p-6">
      <h3 class="text-lg font-semibold text-primary mb-3">Chào mừng 🌸</h3>
      <p class="text-sm text-muted mb-4">
        Hãy đặt tên người dùng và thông tin hiển thị trước khi vào bảng tin.
      </p>

      <label class="form-label">Username *</label>
      <input id="ob_username" class="form-input" placeholder="vd: nean">

      <label class="form-label">Tên hiển thị</label>
      <input id="ob_display_name" class="form-input" placeholder="vd: Sắc Lê">

      <label class="form-label">Bio</label>
      <textarea id="ob_bio" rows="3" class="form-input resize-none" placeholder="Giới thiệu ngắn gọn..."></textarea>

      <div id="ob_error" class="text-xs text-red-500 mb-3 hidden"></div>

      <div class="flex justify-end gap-3 pt-4">
        <button id="ob_save" class="btn btn-primary">
          Lưu & tiếp tục
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
