import { supabase } from './supabase.js'
import { toast } from './toast.js'

export function initAuthUI() {
  const authBtn = document.getElementById('authBtn')
  const authIcon = document.getElementById('authIcon')
  const profileLink = document.getElementById('profileLink')

  supabase.auth.getUser().then(({ data }) => {
    const user = data.user
    if (user) {
      profileLink?.classList.remove('hidden')
      authBtn.title = 'Logout'
      // Change to logout icon
      authIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>'
      authBtn.onclick = async () => {
        await supabase.auth.signOut()
        location.href = '/login'
      }
    } else {
      profileLink?.classList.add('hidden')
      authBtn.title = 'Login'
      // Change to login icon
      authIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>'
      authBtn.onclick = () => location.href = '/login'
    }
  })
}

export function initLogin() {
  const btn = document.getElementById('loginBtn')
  if (!btn) return

  btn.onclick = async () => {
    const email = document.getElementById('login_email').value.trim()
    const password = document.getElementById('login_password').value.trim()

    if (!email || !password) {
      return toast.warning("Vui lòng nhập đầy đủ email và mật khẩu 📝")
    }

    const { error } = await supabase.auth.signInWithPassword({
      email,
      password
    })

    if (error) return toast.error("Sai email hoặc password 🌧")

    toast.success("Đăng nhập thành công! 🌸")
    location.href = '/'
  }
}

export function initRegister() {
  const btn = document.getElementById('registerBtn')
  if (!btn) return

  btn.onclick = async () => {
    const email = document.getElementById('reg_email').value.trim()
    const password = document.getElementById('reg_password').value.trim()
    const confirmPassword = document.getElementById('reg_confirm_password').value.trim()

    if (!email || !password || !confirmPassword) {
      return toast.warning("Vui lòng nhập đầy đủ thông tin 📝")
    }

    if (password !== confirmPassword) {
      return toast.warning("Mật khẩu và xác nhận mật khẩu không khớp 🌧")
    }

    const { data, error } = await supabase.auth.signUp({
      email,
      password
    })

    if (error) return toast.error(error.message)

    toast.success("Đăng ký thành công 🎉")
    location.href = '/login'
  }
}
