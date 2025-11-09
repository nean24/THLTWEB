@extends('layouts.app')
@section('title','Hồ sơ')

@section('content')
<div class="space-y-6">

  <div class="flex items-center gap-4">
    <img id="profileAvatar" class="w-16 h-16 rounded-full border border-[#f2dfe6]"
         src="{{ asset('images/default-avatar.webp') }}" alt="avatar">
    <div>
      <h2 id="profileUsername" class="text-lg font-semibold text-[#4b3b47]">...</h2>
      <p id="profileBio" class="text-sm text-[#a88a99]">...</p>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-3 text-sm text-[#6f5b69]">
    <div><span class="opacity-70">Website:</span> <span id="profileWebsite">—</span></div>
    <div><span class="opacity-70">Location:</span> <span id="profileLocation">—</span></div>
  </div>

  <h3 class="text-sm font-semibold text-[#a88a99]">Bài viết của bạn</h3>
  <div id="myPosts" class="space-y-3"></div>

</div>
@endsection
