@extends('layouts.app')
@section('title','Bài viết')

@section('content')
<div class="space-y-6 px-4 md:px-0">

  {{-- Bài viết --}}
  <div id="postDetail"></div>

  {{-- Danh sách bình luận --}}
  <div>
    <h3 class="text-sm font-semibold text-muted mb-4">Bình luận</h3>
    <div id="comments" class="space-y-3 mb-6"></div>
  </div>

  {{-- Composer bình luận --}}
  <div class="bg-surface rounded-lg p-4 border border-default">
    <div class="space-y-3">
      <textarea id="commentInput" rows="3"
        class="form-input resize-none"
        placeholder="Viết bình luận..."></textarea>
      <div class="flex justify-end">
        <button id="commentBtn" class="btn btn-primary text-sm px-4 py-2">
          Gửi
        </button>
      </div>
    </div>
  </div>

</div>
@endsection
