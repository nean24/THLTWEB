<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','CONVEY')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-[#F8F9FA] text-[#3A3A3C] antialiased">

  <header class="px-6 py-3 border-b border-[#D2D1F0] bg-[#E6DEC7]/80 backdrop-blur-md shadow-sm">
    <div class="max-w-3xl mx-auto flex items-center justify-between">
      <a href="{{ route('home') }}" class="font-semibold text-lg tracking-wide text-[#3A3A3C] hover:text-[#5A5A5C]">
        CONVEY
      </a>
      <nav class="flex gap-6 items-center text-[#5A5A5C]">
        <a id="profileLink" href="{{ route('profile') }}" class="hidden hover:text-[#3A3A3C] transition p-2 rounded-lg hover:bg-[#BFD8FF]/20" title="Profile">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </a>
        <button id="authBtn" class="hover:text-[#3A3A3C] transition p-2 rounded-lg hover:bg-[#BFD8FF]/20" title="">
          <svg id="authIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
        </button>
      </nav>
    </div>
  </header>

  <main class="max-w-2xl mx-auto py-6">
    @yield('content')
  </main>

</body>
</html>
