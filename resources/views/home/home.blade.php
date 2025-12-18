@extends('layouts.app')
@section('title','Bảng tin')

@section('content')
<div class="space-y-4 px-4 md:px-0">

  {{-- Composer --}}
  <section class="post-composer p-3 md:p-4 space-y-3 shadow-sm">

    @if (session('flash_error'))
      <div class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
        {{ session('flash_error') }}
      </div>
    @endif

    @if (session('flash_success'))
      <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
        {{ session('flash_success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('posts.store') }}" class="space-y-3">
      @csrf

      <div class="flex items-center gap-3">
        <img class="w-8 h-8 md:w-10 md:h-10 rounded-full border border-default shadow-sm"
             src="{{ asset('images/default-avatar.webp') }}" alt="me">
        <div class="flex-1">
          <textarea name="content" rows="2" maxlength="500"
            class="form-input focus:outline-none focus:ring-2 focus:ring-button-primary text-sm md:text-base"
            placeholder="Bạn đang nghĩ gì?" {{ empty($authUser) ? 'disabled' : '' }}>{{ old('content') }}</textarea>

          <div class="flex items-center justify-between mt-1 text-xs text-muted">
            @if (empty($authUser))
              <span class="text-primary">
                Bạn chưa đăng nhập. <a href="{{ route('login') }}" class="link-primary">Đăng nhập</a> để đăng bài.
              </span>
            @else
              <span></span>
            @endif
            <span>0/500</span>
          </div>
        </div>
      </div>

      <div class="flex justify-end">
        <button type="submit" class="btn btn-primary text-sm" {{ empty($authUser) ? 'disabled' : '' }}>
          Đăng
        </button>
      </div>
    </form>
  </section>

  <h2 class="text-sm font-medium text-muted px-2">Bài viết mới</h2>

  {{-- Feed (server-rendered) --}}
  <div class="space-y-4">
    @forelse(($posts ?? []) as $post)
      @php($profile = $post['profiles'] ?? null)

      <article class="post p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3">
          <img class="w-10 h-10 rounded-full border border-default shadow-sm"
               src="{{ $profile['avatar_url'] ?? asset('images/default-avatar.webp') }}">
          <div class="flex flex-col">
            <span class="text-sm font-semibold text-black">
              {{ $profile['display_name'] ?? $profile['username'] ?? 'user' }}
            </span>
            <span class="text-xs text-muted">
              {{ \Carbon\Carbon::parse($post['created_at'] ?? null)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
            </span>
          </div>
        </div>

        <p class="text-black whitespace-pre-line text-sm leading-relaxed mb-2">
          {{ $post['content'] ?? '' }}
        </p>

        <div class="flex items-center px-1">
          <div class="flex items-center gap-6">

            <form method="POST" action="{{ route('posts.like', ['id' => $post['id']]) }}">
              @csrf
              <button class="like-btn hover:text-button-primary-hover transition-colors flex items-center gap-2 text-sm text-muted hover:bg-surface-hover px-3 py-2 rounded-lg" type="submit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="like-count font-medium">{{ (int)($post['like_count'] ?? 0) }}</span>
              </button>
            </form>

            <a href="{{ route('post.show', ['id' => $post['id']]) }}" class="hover:text-button-primary-hover transition-colors flex items-center gap-2 text-sm text-muted hover:bg-surface-hover px-3 py-2 rounded-lg">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
              </svg>
              <span class="font-medium">{{ (int)($post['comment_count'] ?? 0) }}</span>
            </a>
          </div>
        </div>
      </article>
    @empty
      <div class="text-sm text-muted text-center py-10">
        Chưa có bài viết nào.
      </div>
    @endforelse
  </div>

</div>

{{-- Onboarding Modal (server-driven) --}}
@if (!empty($showOnboarding ?? null))
<div id="onboardingModal" class="modal-backdrop fixed inset-0 z-50">
  <div class="modal relative mx-auto mt-24 w-[92%] max-w-md">
    <div class="p-6">
      <div class="flex items-start justify-between gap-3">
        <h3 class="text-lg font-semibold text-primary mb-3">Chào mừng 🌸</h3>
        <button id="ob_close" type="button" class="p-1.5 hover:bg-surface-hover rounded-full transition-colors">
          <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <p class="text-sm text-muted mb-4">
        Hãy đặt tên người dùng và thông tin hiển thị trước khi vào bảng tin.
      </p>

      <form method="POST" action="{{ route('onboarding.store') }}" class="space-y-3">
        @csrf

        <label class="form-label">Username *</label>
        <input name="username" class="form-input" placeholder="vd: nean" value="{{ old('username', $onboardingProfile['username'] ?? '') }}" required>

        <label class="form-label">Tên hiển thị</label>
        <input name="display_name" class="form-input" placeholder="vd: Sắc Lê" value="{{ old('display_name', $onboardingProfile['display_name'] ?? '') }}">

        <label class="form-label">Bio</label>
        <textarea name="bio" rows="3" class="form-input resize-none" placeholder="Giới thiệu ngắn gọn...">{{ old('bio', $onboardingProfile['bio'] ?? '') }}</textarea>

        <div class="flex justify-end gap-3 pt-4">
          <button type="submit" class="btn btn-primary">
            Lưu & tiếp tục
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endsection
