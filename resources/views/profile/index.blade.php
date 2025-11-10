@extends('layouts.app')
@section('title','Hồ sơ')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

  <!-- Profile Header -->
  <div class="bg-white rounded-2xl shadow-sm border border-[#f2dfe6] p-8">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
      <!-- Avatar Section -->
      <div class="flex flex-col items-center gap-3">
        <div class="relative">
          <img id="profileAvatar" class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-[#f2dfe6] object-cover"
               src="{{ asset('images/default-avatar.webp') }}" alt="avatar">
          <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-[#d36c9d] rounded-full flex items-center justify-center border-4 border-white">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Profile Info -->
      <div class="flex-1 text-center md:text-left space-y-3">
        <div class="flex flex-col md:flex-row md:items-center gap-3">
          <h1 id="profileUsername" class="text-2xl md:text-3xl font-bold text-[#4b3b47]">...</h1>
          <button id="editProfileBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-[#d36c9d] text-white rounded-lg hover:bg-[#b8588a] transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Chỉnh sửa hồ sơ
          </button>
        </div>

        <p id="profileBio" class="text-[#6f5b69] text-lg leading-relaxed max-w-2xl">...</p>

        <!-- Profile Stats -->
        <div class="flex flex-wrap gap-6 text-sm text-[#6f5b69] pt-2">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#d36c9d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            <span><strong id="postsCount">0</strong> bài viết</span>
          </div>
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#d36c9d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span><strong id="likesGivenCount">0</strong> lượt thích</span>
          </div>
        </div>

        <!-- Additional Info -->
        <div class="flex flex-wrap gap-6 text-sm text-[#6f5b69] pt-2">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#a88a99]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
            </svg>
            <span id="profileWebsite">—</span>
          </div>
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#a88a99]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span id="profileLocation">—</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Posts Section -->
  <div class="bg-white rounded-2xl shadow-sm border border-[#f2dfe6] overflow-hidden">
    <!-- Tab Navigation -->
    <div class="border-b border-[#f2dfe6]">
      <div class="flex">
        <button id="myPostsTab" class="flex-1 px-6 py-4 text-center font-medium border-b-2 border-[#d36c9d] text-[#4b3b47] tab-button active">
          Bài viết của tôi
        </button>
        <button id="likedPostsTab" class="flex-1 px-6 py-4 text-center font-medium border-b-2 border-transparent text-[#a88a99] tab-button hover:text-[#6f5b69] hover:border-[#f2dfe6]">
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
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
  <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
    <div class="p-6">
      <!-- Modal Header -->
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-[#4b3b47]">Chỉnh sửa hồ sơ</h2>
        <button id="closeModalBtn" class="p-2 hover:bg-[#f2dfe6] rounded-full transition-colors">
          <svg class="w-5 h-5 text-[#6f5b69]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Modal Form -->
      <form id="editProfileForm" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-[#4b3b47] mb-2">Tên hiển thị</label>
          <input type="text" id="editDisplayName"
                 class="w-full px-4 py-3 border border-[#f2dfe6] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d36c9d] focus:border-transparent"
                 placeholder="Nhập tên hiển thị">
        </div>

        <div>
          <label class="block text-sm font-medium text-[#4b3b47] mb-2">Tên người dùng</label>
          <input type="text" id="editUsername"
                 class="w-full px-4 py-3 border border-[#f2dfe6] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d36c9d] focus:border-transparent"
                 placeholder="Nhập tên người dùng">
        </div>

        <div>
          <label class="block text-sm font-medium text-[#4b3b47] mb-2">Giới thiệu bản thân</label>
          <textarea id="editBio" rows="3"
                    class="w-full px-4 py-3 border border-[#f2dfe6] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d36c9d] focus:border-transparent resize-none"
                    placeholder="Viết một chút về bản thân..."></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-[#4b3b47] mb-2">Website</label>
          <input type="url" id="editWebsite"
                 class="w-full px-4 py-3 border border-[#f2dfe6] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d36c9d] focus:border-transparent"
                 placeholder="https://example.com">
        </div>

        <div>
          <label class="block text-sm font-medium text-[#4b3b47] mb-2">Địa điểm</label>
          <input type="text" id="editLocation"
                 class="w-full px-4 py-3 border border-[#f2dfe6] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d36c9d] focus:border-transparent"
                 placeholder="Nhập địa điểm">
        </div>

        <div>
          <label class="block text-sm font-medium text-[#4b3b47] mb-2">URL Avatar</label>
          <input type="url" id="editAvatarUrl"
                 class="w-full px-4 py-3 border border-[#f2dfe6] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d36c9d] focus:border-transparent"
                 placeholder="https://example.com/avatar.jpg">
        </div>

        <!-- Modal Buttons -->
        <div class="flex gap-3 pt-4">
          <button type="button" id="cancelEditBtn"
                  class="flex-1 px-4 py-3 border border-[#f2dfe6] text-[#6f5b69] rounded-lg hover:bg-[#f2dfe6] transition-colors">
            Hủy
          </button>
          <button type="submit"
                  class="flex-1 px-4 py-3 bg-[#d36c9d] text-white rounded-lg hover:bg-[#b8588a] transition-colors">
            Lưu thay đổi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
