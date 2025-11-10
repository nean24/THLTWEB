@extends('layouts.app')
@section('title','Hồ sơ')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 px-4">

  <!-- Profile Header -->
  <div class="profile-card p-4 md:p-8">
    <div class="flex flex-col items-center gap-6 md:flex-row md:items-start">
      <!-- Avatar Section -->
      <div class="flex flex-col items-center gap-3">
        <div class="relative">
          <img id="profileAvatar" class="w-20 h-20 md:w-32 md:h-32 rounded-full border-4 border-default object-cover"
               src="{{ asset('images/default-avatar.webp') }}" alt="avatar">
          <div class="absolute -bottom-1 -right-1 w-6 h-6 md:w-8 md:h-8 bg-button-primary rounded-full flex items-center justify-center border-3 md:border-4 border-surface">
            <svg class="w-3 h-3 md:w-4 md:h-4 text-background" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Profile Info -->
      <div class="flex-1 text-center md:text-left space-y-4 w-full">
        <!-- Name and Edit Button -->
        <div class="space-y-3 md:space-y-0 md:flex md:items-start md:justify-between md:gap-4">
          <div class="flex flex-col gap-1">
            <h1 id="profileDisplayName" class="text-xl md:text-3xl font-bold text-primary">...</h1>
            <p id="profileUsername" class="text-sm md:text-base text-muted">@...</p>
          </div>
          <button id="editProfileBtn" class="mx-auto md:mx-0 px-3 py-2 bg-button-primary text-background rounded-lg hover:bg-button-primary-hover transition-colors text-xs md:text-sm font-medium flex items-center gap-2">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Chỉnh sửa
          </button>
        </div>

        <!-- Bio -->
        <p id="profileBio" class="text-sm md:text-base text-muted leading-relaxed">...</p>

        <!-- Stats and Info in Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 pt-4 border-t border-default">
          <!-- Stats -->
          <div class="text-center p-2 rounded-lg bg-surface">
            <div class="text-base md:text-lg font-bold text-primary" id="postsCount">0</div>
            <div class="text-xs text-muted">Bài viết</div>
          </div>

          <div class="text-center p-2 rounded-lg bg-surface">
            <div class="text-base md:text-lg font-bold text-primary" id="likesGivenCount">0</div>
            <div class="text-xs text-muted">Lượt thích</div>
          </div>

          <!-- Additional Info -->
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

  <!-- Posts Section -->
  <div class="card overflow-hidden">
    <!-- Tab Navigation -->
    <div class="border-b border-default">
      <div class="flex">
        <button id="myPostsTab" class="tab-active flex-1 px-6 py-4 text-center font-medium border-b-2 tab-button">
          Bài viết của tôi
        </button>
        <button id="likedPostsTab" class="tab-inactive flex-1 px-6 py-4 text-center font-medium border-b-2 tab-button">
          Đã thích
        </button>
      </div>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
      <div id="myPostsContent" class="tab-content">
        <div id="myPosts" class="space-y-4"></div>
      </div>

      <div id="likedPostsContent" class="tab-content hidden">
        <div id="likedPosts" class="space-y-4"></div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal-backdrop fixed inset-0 hidden items-center justify-center z-50 p-4">
  <div class="modal max-w-md w-full max-h-[90vh] overflow-y-auto mx-4">
    <div class="p-4 md:p-6">
      <!-- Modal Header -->
      <div class="flex items-center justify-between mb-4 md:mb-6">
        <h2 class="text-lg md:text-xl font-bold text-primary">Chỉnh sửa hồ sơ</h2>
        <button id="closeModalBtn" class="p-1.5 hover:bg-surface-hover rounded-full transition-colors">
          <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Modal Form -->
      <form id="editProfileForm" class="space-y-3 md:space-y-4">
        <div>
          <label class="form-label text-sm">Tên hiển thị</label>
          <input type="text" id="editDisplayName" class="form-input text-sm" placeholder="Nhập tên hiển thị">
        </div>

        <div>
          <label class="form-label text-sm">Tên người dùng</label>
          <input type="text" id="editUsername" class="form-input text-sm" placeholder="Nhập tên người dùng">
        </div>

        <div>
          <label class="form-label text-sm">Giới thiệu bản thân</label>
          <textarea id="editBio" rows="3" class="form-input resize-none text-sm" placeholder="Viết một chút về bản thân..."></textarea>
        </div>

        <div>
          <label class="form-label text-sm">URL Avatar</label>
          <input type="url" id="editAvatarUrl" class="form-input text-sm" placeholder="https://example.com/avatar.jpg">
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
