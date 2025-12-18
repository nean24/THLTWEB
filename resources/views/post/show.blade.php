@extends('layouts.app')
@section('title','Bài viết')

@section('content')
<div class="space-y-6 px-4 md:px-0">

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

  {{-- Bài viết --}}
  <div>
    @if (!empty($post))
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

            <span class="flex items-center gap-2 text-sm text-muted hover:bg-surface-hover px-3 py-2 rounded-lg">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
              </svg>
              <span class="font-medium">{{ (int)($post['comment_count'] ?? 0) }}</span>
            </span>
          </div>
        </div>
      </article>
    @else
      <div class="text-center text-muted py-8">Không tìm thấy bài viết</div>
    @endif
  </div>

  {{-- Danh sách bình luận --}}
  <div>
    <h3 class="text-sm font-semibold text-muted mb-4">Bình luận</h3>
    <div class="space-y-3 mb-6">
      @forelse(($comments ?? []) as $c)
        @php($p = $c['profiles'] ?? null)
        <div class="bg-surface rounded-lg p-3 border border-default hover:bg-surface-hover transition-colors">
          <div class="flex items-start gap-3">
            <img class="w-8 h-8 rounded-full border border-default shrink-0"
                 src="{{ $p['avatar_url'] ?? asset('images/default-avatar.webp') }}"
                 alt="Avatar">
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2 mb-1">
                <span class="text-sm font-medium text-primary truncate">
                  {{ $p['display_name'] ?? $p['username'] ?? 'User' }}
                </span>
                <span class="text-xs text-muted shrink-0">
                  {{ \Carbon\Carbon::parse($c['created_at'] ?? null)->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i') }}
                </span>
              </div>
              <p class="text-sm text-primary leading-relaxed whitespace-pre-line">{{ $c['content'] ?? '' }}</p>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center text-muted py-4">Chưa có bình luận nào</p>
      @endforelse
    </div>
  </div>

  {{-- Composer bình luận --}}
  <div class="bg-surface rounded-lg p-4 border border-default">
    <form method="POST" action="{{ route('posts.comment', ['id' => request()->route('id')]) }}" class="space-y-3">
      @csrf

      <textarea name="content" rows="3"
        class="form-input resize-none"
        placeholder="Viết bình luận..." {{ empty($authUser) ? 'disabled' : '' }}>{{ old('content') }}</textarea>

      <div class="flex justify-end">
        <button type="submit" class="btn btn-primary text-sm px-4 py-2" {{ empty($authUser) ? 'disabled' : '' }}>
          Gửi
        </button>
      </div>

      @if (empty($authUser))
        <div class="text-xs text-muted">
          Bạn cần <a href="{{ route('login') }}" class="link-primary">đăng nhập</a> để bình luận.
        </div>
      @endif
    </form>
  </div>

</div>
@endsection
