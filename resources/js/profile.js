import { supabase } from './supabase.js'
import { renderPost } from './feed.js'

export async function initProfile() {
  const container = document.getElementById('myPosts')
  if (!container) return

  const { data: { user } } = await supabase.auth.getUser()
  if (!user) return location.href = "/login"

  const { data: profile } = await supabase
    .from('profiles')
    .select('*')
    .eq('id', user.id)
    .single()

  document.getElementById('profileUsername').textContent =
    profile.display_name || profile.username

  document.getElementById('profileAvatar').src =
    profile.avatar_url || "/images/default-avatar.webp"

  document.getElementById('profileBio').textContent =
    profile.bio || "Chưa có mô tả 🌸"

  const { data: posts } = await supabase.from('posts').select(`
    id, user_id, content, created_at, like_count, comment_count,
    profiles:user_id (username, display_name, avatar_url)
  `).eq('user_id', user.id).order('created_at', { ascending: false })

  container.innerHTML = ''
  posts.forEach(p => container.appendChild(renderPost(p, false)))
}
