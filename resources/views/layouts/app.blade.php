<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
        <title>@yield('title', 'convey')</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            </head>
            <body class="min-h-screen bg-[#fff6f8] text-[#4b3b47] antialiased">

                <header class="px-6 py-4 border-b border-[#f2dfe6] bg-white/60 backdrop-blur-md">
                        <div class="max-w-3xl mx-auto flex items-center justify-between">
                                    <a href="/" class="font-semibold text-lg tracking-wide">
                                                    🌸 CONVEY
                                                                </a>

                                                                            <nav class="flex gap-4 text-sm">
                                                                                            <a href="/" class="hover:text-[#d36c9d] transition">Home</a>
                                                                                                            <a href="/profile" class="hover:text-[#d36c9d] transition">Profile</a>
                                                                                                                        </nav>
                                                                                                                                </div>
                                                                                                                                    </header>

                                                                                                                                        <main class="max-w-3xl mx-auto p-6">
                                                                                                                                                @yield('content')
                                                                                                                                                    </main>

                                                                                                                                                    </body>
                                                                                                                                                    </html>