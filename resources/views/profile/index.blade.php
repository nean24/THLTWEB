@extends('layouts.app')
@section('title','Hồ sơ')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 px-4">

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

  @php($p = $profile ?? null)

  <div class="profile-card p-4 md:p-8">
    <div class="flex flex-col items-center gap-6 md:flex-row md:items-start">

      <div class="flex flex-col items-center gap-3">
        <div class="relative">
          <img id="profileAvatar" class="w-20 h-20 md:w-32 md:h-32 rounded-full border-4 border-default object-cover"
               src="{{ $p['avatar_url'] ?? asset('images/default-avatar.webp') }}" alt="avatar">
          <button id="editProfileBtn" type="button" class="absolute -bottom-1 -right-1 w-6 h-6 md:w-8 md:h-8 bg-button-primary rounded-full flex items-center justify-center border-3 md:border-4 border-surface hover:bg-button-primary-hover transition-colors cursor-pointer" title="Chỉnh sửa hồ sơ">
            <svg class="w-3 h-3 md:w-4 md:h-4 text-background" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="flex-1 text-center md:text-left space-y-4 w-full">

        <div class="space-y-3 md:space-y-0 md:flex md:items-start md:justify-between md:gap-4">
          <div class="flex flex-col gap-1">
            <h1 id="profileDisplayName" class="text-xl md:text-3xl font-bold text-primary">{{ $p['display_name'] ?? $p['username'] ?? '...' }}</h1>
            <p id="profileUsername" class="text-sm md:text-base text-muted">{{ '@' }}{{ $p['username'] ?? '...' }}</p>
          </div>
        </div>

        <p id="profileBio" class="text-sm md:text-base text-muted leading-relaxed">{{ $p['bio'] ?? 'Chưa có mô tả 🌸' }}</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 pt-4 border-t border-default">
          <div class="text-center p-2 rounded-lg bg-surface">
            <div class="text-base md:text-lg font-bold text-primary" id="postsCount">{{ is_array($myPosts ?? null) ? count($myPosts) : 0 }}</div>
            <div class="text-xs text-muted">Bài viết</div>
          </div>

          <div class="text-center p-2 rounded-lg bg-surface">
            <div class="text-base md:text-lg font-bold text-primary" id="likesGivenCount">{{ is_array($likedPosts ?? null) ? count($likedPosts) : 0 }}</div>
            <div class="text-xs text-muted">Lượt thích</div>
          </div>

          <div class="text-center p-2 rounded-lg bg-surface">
            <div class="text-xs md:text-sm text-muted truncate" id="profileWebsite" title="Website">—</div>
            <div class="text-xs text-muted">Website</div>
          </div>

          <div class="text-center p-2 rounded-lg bg-surface">
            <div class="text-xs md:text-sm text-muted truncate" id="profileLocation" title="Location">—</div>
            <div class="text-xs text-muted">Địa điểm</div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="card overflow-hidden">

    <div class="border-b border-default">
      <div class="flex">
        <button id="myPostsTab" class="tab-active flex-1 px-6 py-4 text-center font-medium border-b-2 tab-button" type="button">
          Bài viết của tôi
        </button>
        <button id="likedPostsTab" class="tab-inactive flex-1 px-6 py-4 text-center font-medium border-b-2 tab-button" type="button">
          Đã thích
        </button>
      </div>
    </div>

    <div class="p-6">
      <div id="myPostsContent" class="tab-content">
        <div id="myPosts" class="space-y-4">
          @forelse(($myPosts ?? []) as $post)
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

              @if (!empty($post['images']))
                <div class="mb-3">
                  @php($imgs = is_string($post['images']) ? json_decode($post['images'], true) : $post['images'])
                  @if(is_array($imgs))
                    @foreach($imgs as $img)
                      <img src="{{ $img }}" class="rounded-lg max-h-[500px] w-auto max-w-full mx-auto border border-default" alt="Post image" loading="lazy">
                    @endforeach
                  @elseif(is_string($post['images']))
                     <img src="{{ $post['images'] }}" class="rounded-lg max-h-[500px] w-auto max-w-full mx-auto border border-default" alt="Post image" loading="lazy">
                  @endif
                </div>
              @endif

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
            <p class="text-muted text-center py-8">Bạn chưa có bài viết nào 📝</p>
          @endforelse
        </div>
      </div>

      <div id="likedPostsContent" class="tab-content hidden">
        <div id="likedPosts" class="space-y-4">
          @forelse(($likedPosts ?? []) as $post)
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

              @if (!empty($post['images']))
                <div class="mb-3">
                  @php($imgs = is_string($post['images']) ? json_decode($post['images'], true) : $post['images'])
                  @if(is_array($imgs))
                    @foreach($imgs as $img)
                      <img src="{{ $img }}" class="rounded-lg max-h-[500px] w-auto max-w-full mx-auto border border-default" alt="Post image" loading="lazy">
                    @endforeach
                  @elseif(is_string($post['images']))
                     <img src="{{ $post['images'] }}" class="rounded-lg max-h-[500px] w-auto max-w-full mx-auto border border-default" alt="Post image" loading="lazy">
                  @endif
                </div>
              @endif

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
            <p class="text-muted text-center py-8">Bạn chưa thích bài viết nào 💕</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

<div id="editProfileModal" class="modal-backdrop fixed inset-0 hidden items-center justify-center z-50 p-4">
  <div class="modal max-w-md w-full max-h-[90vh] overflow-y-auto mx-4">
    <div class="p-4 md:p-6">

      <div class="flex items-center justify-between mb-4 md:mb-6">
        <h2 class="text-lg md:text-xl font-bold text-primary">Chỉnh sửa hồ sơ</h2>
        <button id="closeModalBtn" type="button" class="p-1.5 hover:bg-surface-hover rounded-full transition-colors">
          <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" class="space-y-3 md:space-y-4" enctype="multipart/form-data">
        @csrf

        <div>
          <label class="form-label text-sm">Tên hiển thị</label>
          <input type="text" name="display_name" id="editDisplayName" value="{{ old('display_name', $p['display_name'] ?? '') }}" class="form-input text-sm" placeholder="Nhập tên hiển thị">
        </div>

        <div>
          <label class="form-label text-sm">Tên người dùng</label>
          <input type="text" name="username" id="editUsername" value="{{ old('username', $p['username'] ?? '') }}" class="form-input text-sm" placeholder="Nhập tên người dùng" required>
        </div>

        <div>
          <label class="form-label text-sm">Giới thiệu bản thân</label>
          <textarea name="bio" id="editBio" rows="3" class="form-input resize-none text-sm" placeholder="Viết một chút về bản thân...">{{ old('bio', $p['bio'] ?? '') }}</textarea>
        </div>

        <div>
          <label class="form-label text-sm">Avatar</label>
          <div class="flex items-center gap-3">
            <img id="avatarPreview" src="{{ $p['avatar_url'] ?? asset('images/default-avatar.webp') }}" class="w-12 h-12 rounded-full border border-default object-cover">
            <input type="file" name="avatar_file" id="avatarInput" class="text-sm text-muted file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-surface-hover file:text-primary hover:file:bg-surface transition-colors" accept="image/*">
          </div>
          <input type="hidden" name="avatar_url" id="editAvatarUrl" value="{{ old('avatar_url', $p['avatar_url'] ?? '') }}">
        </div>

        <!-- Modal Buttons -->
        <div class="flex gap-3 pt-3 md:pt-4">
          <button type="button" id="cancelEditBtn" class="btn btn-outline flex-1 text-sm py-2">
            Hủy
          </button>
          <button type="submit" class="btn btn-secondary flex-1 text-sm py-2">
            Lưu thay đổi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');

    if (avatarInput && avatarPreview) {
      avatarInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            avatarPreview.src = e.target.result;
          }
          reader.readAsDataURL(file);
        }
      });
    }
  });
</script>
