import { supabase } from './supabase.js'
import { toast } from './toast.js'

function esc(str) {
  return str?.replace(/[&<>"']/g, m => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;"
  }[m])) ?? ''
}

/*--------------------------------
  Render Post HTML
--------------------------------*/
export function renderPost(post) {
  return `
  <article class="post p-5 shadow-sm hover:shadow-md transition-shadow">
    <!-- Header với avatar và thông tin user -->
    <div class="flex items-center gap-3">
      <img class="w-10 h-10 rounded-full border border-default shadow-sm"
           src="${post.profiles?.avatar_url || '/images/default-avatar.webp'}">
      <div class="flex flex-col">
        <span class="text-sm font-semibold text-black">
          ${post.profiles?.display_name || post.profiles?.username || "user"}
        </span>
        <span class="text-xs text-muted">
          ${new Date(post.created_at).toLocaleString('vi-VN', {
            year: 'numeric', month: 'short', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
          })}
        </span>
      </div>
    </div>

    <!-- Nội dung bài viết -->
    <p class="text-black whitespace-pre-line text-sm leading-relaxed mb-2">
      ${esc(post.content)}
    </p>


    <!-- Action buttons -->
    <div class="flex items-center px-1">
      <div class="flex items-center gap-6">
        <button class="like-btn hover:text-button-primary-hover transition-colors flex items-center gap-2 text-sm text-muted hover:bg-surface-hover px-3 py-2 rounded-lg" data-post-id="${post.id}">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
          <span class="like-count font-medium">${post.likes_count || 0}</span>
        </button>
        <a href="/post/${post.id}" class="hover:text-button-primary-hover transition-colors flex items-center gap-2 text-sm text-muted hover:bg-surface-hover px-3 py-2 rounded-lg">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          <span class="font-medium">${post.comments_count || 0}</span>
        </a>
      </div>
    </div>
  </article>
  `
}

/*Tải danh sách bài viết lên feed*/
export async function loadFeed() {
  const container = document.getElementById('posts')
  if (!container) return

  const { data: { user } } = await supabase.auth.getUser()

  const { data, error } = await supabase
    .from('posts')
    .select(`
      id, user_id, content, created_at,
      profiles:user_id (username, display_name, avatar_url),
      likes (user_id),
      comments (id)
    `)
    .order('created_at', { ascending: false })

  if (error) {
    console.error(error)
    return
  }

  const postsWithData = data.map(post => {
    const likes = post.likes || []
    const comments = post.comments || []
    const likesCount = likes.length
    const commentsCount = comments.length
    const userLiked = user ? likes.some(like => like.user_id === user.id) : false

    return {
      ...post,
      likes_count: likesCount,
      comments_count: commentsCount,
      user_liked: userLiked
    }
  })

  container.innerHTML = postsWithData.map(p => renderPost(p)).join('')

  if (user) {
    postsWithData.forEach(post => {
      if (post.user_liked) {
        const likeBtn = container.querySelector(`[data-post-id="${post.id}"]`)
        if (likeBtn) {
          const heartIcon = likeBtn.querySelector('svg')
          heartIcon.setAttribute('fill', 'currentColor')
          likeBtn.classList.add('text-red-500')
        }
      }
    })
  }
}

/*--------------------------------
  Composer: đăng bài mới trên feed
--------------------------------*/
export function initComposer() {
  const composer = document.getElementById('composer')
  const postBtn = document.getElementById('postBtn')
  const charCount = document.getElementById('charCount')
  const loginHint = document.getElementById('loginHint')
  if (!composer || !postBtn) return

  supabase.auth.getUser().then(async ({ data }) => {
    const user = data.user

    if (!user) {
      loginHint?.classList.remove('hidden')
      postBtn.disabled = true
      return
    }

    composer.addEventListener('input', () => {
      charCount.textContent = `${composer.value.length}/500`
    })

    postBtn.addEventListener('click', async () => {
      const text = composer.value.trim()
      if (!text) {
        toast.warning("Vui lòng nhập nội dung bài viết.")
        return
      }

      postBtn.disabled = true
      postBtn.textContent = 'Đang đăng...'

      const { error } = await supabase.from('posts').insert([{
        user_id: user.id,
        content: text
      }])

      postBtn.disabled = false
      postBtn.textContent = 'Đăng'

      if (error) {
        console.error('Error posting:', error)
        toast.error("Có lỗi khi đăng bài. Vui lòng thử lại! 🌧")
      } else {
        composer.value = ''
        charCount.textContent = '0/500'
        toast.success("Đăng bài thành công!")
        loadFeed()
      }
    })
  })
}

/*Init feed: Khởi tạo feed và load dữ liệu*/
export function initFeed() {
  console.log('Initializing feed...')
  loadFeed()
}

export function initLikeHandlers() {
  document.addEventListener('click', async (e) => {
    if (!e.target.closest('.like-btn')) return

    const likeBtn = e.target.closest('.like-btn')
    const postId = likeBtn.dataset.postId

    // Get current user
    const { data: { user } } = await supabase.auth.getUser()
    if (!user) return location.href = "/login"

    const { data: existingLike } = await supabase
      .from('likes')
      .select('id')
      .eq('post_id', postId)
      .eq('user_id', user.id)
      .single()

    const heartIcon = likeBtn.querySelector('svg')
    const countSpan = likeBtn.querySelector('.like-count')
    let currentCount = parseInt(countSpan.textContent) || 0

    if (existingLike) {

      await supabase
        .from('likes')
        .delete()
        .eq('post_id', postId)
        .eq('user_id', user.id)

      heartIcon.setAttribute('fill', 'none')
      likeBtn.classList.remove('text-red-500')
      countSpan.textContent = Math.max(0, currentCount - 1)
    } else {
      await supabase
        .from('likes')
        .insert({ post_id: postId, user_id: user.id })

      heartIcon.setAttribute('fill', 'currentColor')
      likeBtn.classList.add('text-red-500')
      countSpan.textContent = currentCount + 1
    }
  })
}
