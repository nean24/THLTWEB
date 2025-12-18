@extends('layouts.app')
@section('title','Đăng nhập')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="space-y-6 max-w-md w-full">

    <h1 class="text-xl md:text-2xl font-semibold text-primary text-center">Đăng nhập</h1>

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

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
      @csrf

      <div>
        <label class="form-label">Email</label>
        <input name="email" value="{{ old('email') }}" type="email" class="form-input" placeholder="vd: user@example.com" required>
      </div>

      <div>
        <label class="form-label">Mật khẩu</label>
        <input name="password" type="password" class="form-input" placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn btn-primary w-full">
        Đăng nhập
      </button>
    </form>

    <p class="text-sm text-center text-muted">
      Chưa có tài khoản?
      <a href="{{ route('register') }}" class="link-primary">Đăng ký</a>
    </p>

  </div>
</div>
@endsection
