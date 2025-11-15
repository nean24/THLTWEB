import { supabase } from './supabase.js'
import { renderPost } from './feed.js'
import { toast } from './toast.js'

const $ = sel => document.querySelector(sel)

export async function loadPostDetail() {
  const container = $('#postDetail')
  if (!container) {
    console.error('Post detail container (#postDetail) not found')
    return
  }

  const postId = window.location.pathname.split('/').pop()

  // Validate postId
  if (!postId || postId === 'post' || postId === 'posts') {
    console.error('Invalid post ID:', postId)
    return
  }

  const { data, error } = await supabase
    .from('posts')
    .select(`
      id, user_id, content, created_at,
      profiles:user_id (username, display_name, avatar_url),
      likes (user_id),
      comments (id)
    `)
    .eq('id', postId)
    .single()

  if (error) {
    console.error('Error loading post detail:', error)
    container.innerHTML = '<div class="text-center text-muted py-8">Không tìm thấy bài viết</div>'
    return
  }

  // Add likes and comments data for renderPost
  const likesCount = data.likes?.length || 0
  const commentsCount = data.comments?.length || 0
  const postWithData = {
    ...data,
    likes_count: likesCount,
    comments_count: commentsCount,
    user_liked: false // Will be updated by like functionality
  }

  container.innerHTML = renderPost(postWithData)
}

export async function loadComments() {
  const list = $('#comments')
  if (!list) {
    console.error('Comments container not found')
    return
  }

  const postId = window.location.pathname.split('/').pop()

  // Simple query without relationship - get comments first
  const { data: comments, error } = await supabase
    .from('comments')
    .select('id, content, created_at, user_id')
    .eq('post_id', postId)
    .order('created_at', { ascending: true })

  if (error) {
    console.error('Error loading comments:', error)
    list.innerHTML = '<p class="text-center text-muted py-4">Không thể tải bình luận</p>'
    return
  }

  if (!comments || comments.length === 0) {
    list.innerHTML = '<p class="text-center text-muted py-4">Chưa có bình luận nào</p>'
    return
  }

  // Get unique user IDs
  const userIds = [...new Set(comments.map(c => c.user_id))]

  // Get profiles separately
  const { data: profiles, error: profileError } = await supabase
    .from('profiles')
    .select('id, username, display_name, avatar_url')
    .in('id', userIds)

  if (profileError) {
    console.error('Error loading profiles:', profileError)
  }

  // Merge profiles with comments
  const commentsWithProfiles = comments.map(comment => ({
    ...comment,
    profiles: profiles?.find(p => p.id === comment.user_id) || {
      username: 'Unknown',
      display_name: 'Unknown User',
      avatar_url: null
    }
  }))

  renderComments(list, commentsWithProfiles)
}

function renderComments(container, comments) {
  if (!comments || comments.length === 0) {
    container.innerHTML = '<p class="text-center text-muted py-8">Chưa có bình luận nào</p>'
    return
  }

  container.innerHTML = comments.map(c => `
    <div class="bg-surface rounded-lg p-3 border border-default hover:bg-surface-hover transition-colors">
      <div class="flex items-start gap-3">
        <img class="w-8 h-8 rounded-full border border-default shrink-0"
             src="${c.profiles?.avatar_url ?? '/images/default-avatar.webp'}"
             alt="Avatar">
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between gap-2 mb-1">
            <span class="text-sm font-medium text-primary truncate">
              ${c.profiles?.display_name ?? c.profiles?.username ?? 'User'}
            </span>
            <span class="text-xs text-muted shrink-0">
              ${new Date(c.created_at).toLocaleDateString('vi-VN', {
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
              })}
            </span>
          </div>
          <p class="text-sm text-primary leading-relaxed whitespace-pre-line">${c.content}</p>
        </div>
      </div>
    </div>
  `).join('')
}

export async function initCommentComposer() {
  const btn = $('#commentBtn')
  const input = $('#commentInput')

  if (!btn || !input) {
    console.error('Comment composer elements not found - Button:', !!btn, 'Input:', !!input)
    return
  }

  const handleComment = async () => {
    const text = input.value.trim()

    if (!text) {
      toast.warning("Vui lòng nhập nội dung bình luận 📝")
      return
    }

    const { data: { user } } = await supabase.auth.getUser()

    if (!user) {
      toast.warning("Vui lòng đăng nhập để bình luận 🔐")
      setTimeout(() => {
        location.href = "/login"
      }, 1500)
      return
    }

    const postId = window.location.pathname.split('/').pop()

    // Disable button and show loading state
    btn.disabled = true
    const originalText = btn.textContent
    btn.textContent = 'Đang gửi...'

    const { data, error } = await supabase.from('comments').insert([{
      post_id: postId,
      user_id: user.id,
      content: text
    }])

    // Re-enable button
    btn.disabled = false
    btn.textContent = originalText

    if (error) {
      console.error('Error posting comment:', error)
      toast.error("Có lỗi khi gửi bình luận. Vui lòng thử lại! 🌧")
      return
    }

    input.value = ''
    toast.success("Gửi bình luận thành công! 💬")
    loadComments()
  }

  // Click handler
  btn.onclick = handleComment

  // Enter key handler (with Shift+Enter for multiline)
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault() // Prevent new line
      handleComment()
    }
    // Shift+Enter allows new line
  })
}
