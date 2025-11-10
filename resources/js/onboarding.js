import { supabase } from './supabase.js'

// helper nhỏ
const $ = sel => document.querySelector(sel)

export async function initOnboarding() {
  const modal = $('#onboardingModal')
  if (!modal) return

  const { data: { user } } = await supabase.auth.getUser()
  if (!user) return // chưa đăng nhập thì thôi

  // lấy profile
  const { data: profile, error } = await supabase
    .from('profiles')
    .select('id, username, display_name, bio, onboarded')
    .eq('id', user.id).single()

  if (error) { console.error(error); return }

  // Nếu đã onboarded -> không mở modal
  if (profile?.onboarded) return

  // Chưa onboarded -> mở modal + khóa nền
  openModal(modal)

  // gán placeholder mặc định
  $('#ob_username').value = profile?.username || (user.user_metadata?.username ?? '').trim()
  $('#ob_display_name').value = profile?.display_name || ''
  $('#ob_bio').value = profile?.bio || ''

  // Validate + lưu
  $('#ob_save').onclick = () => saveProfile(user.id, modal)
}

function openModal(modal) {
  modal.classList.remove('hidden')
  document.body.style.overflow = 'hidden' // khoá scroll nền
}

function closeModal(modal) {
  modal.classList.add('hidden')
  document.body.style.overflow = ''
}

async function saveProfile(userId, modal) {
  const err = $('#ob_error')
  err.classList.add('hidden'); err.textContent = ''

  let username = $('#ob_username').value.trim()
  const display_name = $('#ob_display_name').value.trim()
  const bio = $('#ob_bio').value.trim()

  // ràng buộc username: 3–24 ký tự, a-z0-9_ (case-insensitive)
  if (!/^[a-zA-Z0-9_]{3,24}$/.test(username)) {
    err.textContent = 'Username 3–24 ký tự, chỉ chữ/số/_.'
    err.classList.remove('hidden'); return
  }

  // kiểm tra trùng (case-insensitive)
  const { data: exists } = await supabase
    .from('profiles')
    .select('id')
    .neq('id', userId)
    .ilike('username', username)   // so khớp không phân biệt hoa thường
    .maybeSingle()

  if (exists) {
    err.textContent = 'Username đã được sử dụng.'
    err.classList.remove('hidden'); return
  }

  // chuẩn hoá lower-case lưu vào username (tuỳ bạn)
  username = username.toLowerCase()

  // cập nhật profile + đánh dấu onboarded
  const { error: upErr } = await supabase
    .from('profiles')
    .update({
      username,
      display_name: display_name || username,
      bio,
      onboarded: true
    })
    .eq('id', userId)

  if (upErr) {
    err.textContent = upErr.message || 'Không thể lưu, thử lại sau.'
    err.classList.remove('hidden'); return
  }

  // đồng bộ metadata username cho session hiện tại (để header dùng)
  await supabase.auth.updateUser({ data: { username } })

  closeModal(modal)

  // reload feed/profile cho chắc
  window.location.reload()
}
