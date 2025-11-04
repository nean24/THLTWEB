@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="bg-white shadow-md rounded-xl p-6 max-w-sm mx-auto space-y-4 border border-[#f2dfe6]">

    <h1 class="text-center text-xl font-semibold text-[#d36c9d]">Chào mừng 🌸</h1>

        <input id="email" class="w-full p-2 border border-[#f2dfe6] rounded bg-[#fffafb]" placeholder="Email">
            <input id="password" type="password" class="w-full p-2 border border-[#f2dfe6] rounded bg-[#fffafb]" placeholder="Mật khẩu">

                <button id="loginBtn" class="w-full py-2 bg-[#f6dce8] hover:bg-[#f3cadf] text-[#4b3b47] rounded transition">
                        Đăng nhập
                            </button>

                                <button id="signupBtn" class="w-full py-2 border border-[#f2dfe6] rounded bg-white hover:bg-[#fffafb] transition">
                                        Tạo tài khoản mới
                                            </button>

                                            </div>

                                            <script type="module">
                                            import { supabase } from '/resources/js/supabase.js'

                                            loginBtn.onclick = async () => {
                                              const { error } = await supabase.auth.signInWithPassword({
                                                  email: email.value,
                                                      password: password.value
                                                        })
                                                          if (error) return alert(error.message)
                                                            location.href = "/profile"
                                                            }

                                                            signupBtn.onclick = async () => {
                                                              const { error } = await supabase.auth.signUp({
                                                                  email: email.value,
                                                                      password: password.value
                                                                        })
                                                                          if (error) return alert(error.message)
                                                                            alert("Kiểm tra email để kích hoạt ✨")
                                                                            }
                                                                            </script>
                                                                            @endsection