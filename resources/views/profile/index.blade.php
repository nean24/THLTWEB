@extends('layouts.app')

@section('title', 'Hồ sơ')

@section('content')
<div class="bg-white shadow-md rounded-xl p-6 max-w-md mx-auto space-y-4 border border-[#f2dfe6]">

    <div class="text-center">
            <img id="avatar" class="w-24 h-24 rounded-full mx-auto border border-[#f2dfe6] object-cover" src="https://placekitten.com/200/200">
                    <p class="mt-2 text-sm text-[#d36c9d]">Hồ sơ của bạn</p>
                        </div>

                            <div>
                                    <label class="text-xs text-[#a88a99]">Tên hiển thị</label>
                                            <input id="display_name" class="w-full p-2 border border-[#f2dfe6] rounded bg-[#fffafb]">
                                                </div>

                                                    <div>
                                                            <label class="text-xs text-[#a88a99]">Giới thiệu</label>
                                                                    <textarea id="bio" class="w-full p-2 border border-[#f2dfe6] rounded bg-[#fffafb]"></textarea>
                                                                        </div>

                                                                            <button id="saveBtn" class="w-full py-2 bg-[#f6dce8] hover:bg-[#f3cadf] text-[#4b3b47] rounded transition">
                                                                                    Lưu thay đổi 💗
                                                                                        </button>
                                                                                        </div>

                                                                                        <script type="module">
                                                                                        import { supabase } from '/resources/js/supabase.js'

                                                                                        const { data: { user } } = await supabase.auth.getUser()
                                                                                        if (!user) location.href = "/"

                                                                                        const { data: profile } = await supabase
                                                                                          .from('profiles')
                                                                                            .select('*')
                                                                                              .eq('id', user.id)
                                                                                                .single()

                                                                                                display_name.value = profile.display_name ?? ''
                                                                                                bio.value = profile.bio ?? ''
                                                                                                avatar.src = profile.avatar_url ?? "https://placekitten.com/200/200"

                                                                                                saveBtn.onclick = async () => {
                                                                                                  await supabase
                                                                                                      .from('profiles')
                                                                                                          .update({ display_name: display_name.value, bio: bio.value })
                                                                                                              .eq('id', user.id)
                                                                                                                alert("Đã lưu ✨")
                                                                                                                }
                                                                                                                </script>
                                                                                                                @endsection