@extends('layouts.app')

@section('title', 'Bảng tin 🌸')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

  <h1 class="text-xl font-semibold text-[#d36c9d] mb-4">
      Bảng tin 🌸
        </h1>

          {{-- Danh sách bài viết sẽ được load bằng Supabase --}}
            <div id="posts" class="space-y-4"></div>

            </div>

            <script type="module">
            import { supabase } from '/resources/js/supabase.js'

            // Lấy bài viết + kèm profile user (username, avatar)
            async function loadFeed() {
              const { data, error } = await supabase
                  .from('posts')
                      .select(`
                            id,
                                  content,
                                        images,
                                              created_at,
                                                    profiles:user_id (
                                                            username,
                                                                    avatar_url
                                                                          )
                                                                              `)
                                                                                  .order('created_at', { ascending: false })

                                                                                    if (error) {
                                                                                        console.error(error)
                                                                                            return
                                                                                              }

                                                                                                const postsEl = document.getElementById('posts')
                                                                                                  postsEl.innerHTML = ''

                                                                                                    data.forEach(post => {
                                                                                                        postsEl.innerHTML += `
                                                                                                              <article class="bg-white border border-[#f2dfe6] rounded-xl p-4 space-y-2">
                                                                                                                      
                                                                                                                              <div class="flex items-center gap-2">
                                                                                                                                        <img class="w-8 h-8 rounded-full border border-[#f2dfe6]" 
                                                                                                                                                    src="${post.profiles?.avatar_url ?? 'https://placekitten.com/200/200'}"/>
                                                                                                                                                              <span class="text-sm font-medium">${post.profiles?.username ?? 'user'}</span>
                                                                                                                                                                      </div>

                                                                                                                                                                              <p class="text-[#4b3b47] whitespace-pre-line text-sm leading-relaxed">
                                                                                                                                                                                        ${post.content}
                                                                                                                                                                                                </p>

                                                                                                                                                                                                        <a href="/post/${post.id}" class="text-xs text-[#d36c9d] hover:underline">
                                                                                                                                                                                                                  Bình luận...
                                                                                                                                                                                                                          </a>
                                                                                                                                                                                                                                </article>
                                                                                                                                                                                                                                    `
                                                                                                                                                                                                                                      })
                                                                                                                                                                                                                                      }

                                                                                                                                                                                                                                      loadFeed()
                                                                                                                                                                                                                                      </script>

                                                                                                                                                                                                                                      @endsection