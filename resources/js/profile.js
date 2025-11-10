import { supabase } from './supabase.js'
import { renderPost } from './feed.js'

let currentUser = null
let currentProfile = null

export async function initProfile() {
  // Check if we're on the profile page
  const myPostsContainer = document.getElementById('myPosts')
  if (!myPostsContainer) return

  // Get current user
  const { data: { user } } = await supabase.auth.getUser()
  if (!user) return location.href = "/login"

  currentUser = user

  // Load profile data
  await loadProfileData(user)

  // Load user's posts
  await loadMyPosts(user)

  // Initialize tab switching
  initTabSwitching()

  // Initialize modal functionality
  initEditProfileModal()
}

async function loadProfileData(user) {
  const { data: profile, error } = await supabase
    .from('profiles')
    .select('*')
    .eq('id', user.id)
    .single()

  if (error) {
    console.error('Error loading profile:', error)
    return
  }

  currentProfile = profile

  // Update profile UI
  document.getElementById('profileUsername').textContent =
    profile?.display_name || profile?.username || 'Người dùng'

  document.getElementById('profileAvatar').src =
    profile?.avatar_url || "/images/default-avatar.webp"

  document.getElementById('profileBio').textContent =
    profile?.bio || "Chưa có mô tả 🌸"

  document.getElementById('profileWebsite').textContent =
    profile?.website || "—"

  document.getElementById('profileLocation').textContent =
    profile?.location || "—"

  // Load and display statistics
  await loadProfileStats(user)
}

async function loadProfileStats(user) {
  // Get posts count
  const { data: posts, error: postsError } = await supabase
    .from('posts')
    .select('id')
    .eq('user_id', user.id)

  if (!postsError) {
    document.getElementById('postsCount').textContent = posts?.length || 0
  }

  // Get likes count (posts this user has liked)
  const { data: likes, error: likesError } = await supabase
    .from('likes')
    .select('id')
    .eq('user_id', user.id)

  if (!likesError) {
    document.getElementById('likesGivenCount').textContent = likes?.length || 0
  }
}

async function loadMyPosts(user) {
  const container = document.getElementById('myPosts')
  if (!container) return

  const { data: posts, error } = await supabase
    .from('posts')
    .select(`
      id, user_id, content, created_at,
      profiles:user_id (username, display_name, avatar_url),
      likes (user_id)
    `)
    .eq('user_id', user.id)
    .order('created_at', { ascending: false })

  if (error) {
    console.error('Error loading my posts:', error)
    container.innerHTML = '<p class="text-navy-light text-center py-8">Không thể tải bài viết</p>'
    return
  }

  if (!posts || posts.length === 0) {
    container.innerHTML = '<p class="text-navy-light text-center py-8">Bạn chưa có bài viết nào 📝</p>'
    return
  }

  // Add likes count and user's like status to each post
  const postsWithLikes = posts.map(post => {
    const likes = post.likes || []
    const likesCount = likes.length
    const userLiked = likes.some(like => like.user_id === user.id)

    return {
      ...post,
      likes_count: likesCount,
      user_liked: userLiked
    }
  })

  container.innerHTML = postsWithLikes.map(p => renderPost(p)).join('')

  // Update like button styles for liked posts
  postsWithLikes.forEach(post => {
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

async function loadLikedPosts(user) {
  const container = document.getElementById('likedPosts')
  if (!container) return

  // Show loading state
  container.innerHTML = '<p class="text-navy-light text-center py-8 loading">Đang tải...</p>'

  const { data: likedPosts, error } = await supabase
    .from('likes')
    .select(`
      post_id,
      created_at,
      posts:post_id (
        id, user_id, content, created_at,
        profiles:user_id (username, display_name, avatar_url)
      )
    `)
    .eq('user_id', user.id)
    .order('created_at', { ascending: false })

  if (error) {
    console.error('Error loading liked posts:', error)
    container.innerHTML = '<p class="text-navy-light text-center py-8">Không thể tải bài viết đã thích</p>'
    return
  }

  if (!likedPosts || likedPosts.length === 0) {
    container.innerHTML = '<p class="text-navy-light text-center py-8">Bạn chưa thích bài viết nào 💕</p>'
    return
  }

  // Get likes count for each post
  const postIds = likedPosts.map(item => item.posts.id)
  const { data: allLikes } = await supabase
    .from('likes')
    .select('post_id, user_id')
    .in('post_id', postIds)

  // Extract posts and add likes data
  const posts = likedPosts.map(item => {
    const post = item.posts
    const postLikes = allLikes?.filter(like => like.post_id === post.id) || []
    const likesCount = postLikes.length
    const userLiked = postLikes.some(like => like.user_id === user.id)

    return {
      ...post,
      likes_count: likesCount,
      user_liked: userLiked
    }
  })

  container.innerHTML = posts.map(p => renderPost(p)).join('')

  // Update like button styles for liked posts
  posts.forEach(post => {
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

function initTabSwitching() {
  const myPostsTab = document.getElementById('myPostsTab')
  const likedPostsTab = document.getElementById('likedPostsTab')
  const myPostsContent = document.getElementById('myPostsContent')
  const likedPostsContent = document.getElementById('likedPostsContent')

  if (!myPostsTab || !likedPostsTab || !myPostsContent || !likedPostsContent) return

  let likedPostsLoaded = false

  myPostsTab.addEventListener('click', () => {
    // Update tab styles
    myPostsTab.classList.add('tab-active')
    myPostsTab.classList.remove('tab-inactive')
    likedPostsTab.classList.remove('tab-active')
    likedPostsTab.classList.add('tab-inactive')

    // Update content visibility
    myPostsContent.classList.remove('hidden')
    likedPostsContent.classList.add('hidden')
  })

  likedPostsTab.addEventListener('click', async () => {
    // Update tab styles
    likedPostsTab.classList.add('tab-active')
    likedPostsTab.classList.remove('tab-inactive')
    myPostsTab.classList.remove('tab-active')
    myPostsTab.classList.add('tab-inactive')

    // Update content visibility
    likedPostsContent.classList.remove('hidden')
    myPostsContent.classList.add('hidden')

    // Load liked posts if not already loaded
    if (currentUser && !likedPostsLoaded) {
      await loadLikedPosts(currentUser)
      likedPostsLoaded = true
    }
  })
}

// Initialize edit profile modal
function initEditProfileModal() {
  const editProfileBtn = document.getElementById('editProfileBtn')
  const editProfileModal = document.getElementById('editProfileModal')
  const closeModalBtn = document.getElementById('closeModalBtn')
  const cancelEditBtn = document.getElementById('cancelEditBtn')
  const editProfileForm = document.getElementById('editProfileForm')

  if (!editProfileBtn || !editProfileModal) return

  // Open modal with animation
  editProfileBtn.addEventListener('click', () => {
    openEditProfileModal()
  })

  // Close modal with animation
  const closeModal = () => {
    editProfileModal.style.animation = 'fadeOut 0.2s ease-in-out'
    setTimeout(() => {
      editProfileModal.classList.add('hidden')
      editProfileModal.classList.remove('flex')
      editProfileModal.style.animation = ''
    }, 200)
  }

  closeModalBtn?.addEventListener('click', closeModal)
  cancelEditBtn?.addEventListener('click', closeModal)

  // Close modal when clicking outside
  editProfileModal.addEventListener('click', (e) => {
    if (e.target === editProfileModal) {
      closeModal()
    }
  })

  // Handle form submission
  editProfileForm?.addEventListener('submit', async (e) => {
    e.preventDefault()

    // Show loading state
    const submitBtn = editProfileForm.querySelector('button[type="submit"]')
    const originalText = submitBtn.textContent
    submitBtn.textContent = 'Đang lưu...'
    submitBtn.disabled = true

    try {
      await saveProfileChanges()
      closeModal()
    } catch (error) {
      console.error('Error saving profile:', error)
    } finally {
      submitBtn.textContent = originalText
      submitBtn.disabled = false
    }
  })

  // Close modal on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !editProfileModal.classList.contains('hidden')) {
      closeModal()
    }
  })

  // Auto-resize textarea
  const bioTextarea = document.getElementById('editBio')
  bioTextarea?.addEventListener('input', function() {
    this.style.height = 'auto'
    this.style.height = this.scrollHeight + 'px'
  })
}

function openEditProfileModal() {
  const editProfileModal = document.getElementById('editProfileModal')
  if (!editProfileModal || !currentProfile) return

  // Fill form with current data
  document.getElementById('editDisplayName').value = currentProfile.display_name || ''
  document.getElementById('editUsername').value = currentProfile.username || ''
  document.getElementById('editBio').value = currentProfile.bio || ''
  document.getElementById('editWebsite').value = currentProfile.website || ''
  document.getElementById('editLocation').value = currentProfile.location || ''
  document.getElementById('editAvatarUrl').value = currentProfile.avatar_url || ''

  // Show modal with animation
  editProfileModal.classList.remove('hidden')
  editProfileModal.classList.add('flex')

  // Add backdrop and modal animations
  editProfileModal.classList.add('modal-backdrop')
  const modalContent = editProfileModal.querySelector('div > div')
  modalContent?.classList.add('modal-content')

  // Focus first input
  setTimeout(() => {
    document.getElementById('editDisplayName')?.focus()
  }, 100)
}

async function saveProfileChanges() {
  if (!currentUser) return

  // Validate required fields
  const username = document.getElementById('editUsername').value.trim()
  if (!username) {
    alert('Tên người dùng không được để trống')
    return
  }

  const updatedData = {
    display_name: document.getElementById('editDisplayName').value.trim() || null,
    username: username,
    bio: document.getElementById('editBio').value.trim() || null,
    website: document.getElementById('editWebsite').value.trim() || null,
    location: document.getElementById('editLocation').value.trim() || null,
    avatar_url: document.getElementById('editAvatarUrl').value.trim() || null
  }

  const { data, error } = await supabase
    .from('profiles')
    .update(updatedData)
    .eq('id', currentUser.id)
    .select()
    .single()

  if (error) {
    console.error('Error updating profile:', error)

    // Handle specific errors
    if (error.code === '23505') {
      alert('Tên người dùng này đã được sử dụng. Vui lòng chọn tên khác.')
    } else {
      alert('Có lỗi khi cập nhật hồ sơ. Vui lòng thử lại.')
    }
    throw error
  }

  // Update current profile data
  currentProfile = { ...currentProfile, ...data }

  // Reload profile display with animation
  await loadProfileData(currentUser)

  // Show success message
  showSuccessToast('Hồ sơ đã được cập nhật thành công!')
}

// Success toast notification
function showSuccessToast(message) {
  const toast = document.createElement('div')
  toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300'
  toast.textContent = message

  document.body.appendChild(toast)

  // Animate in
  setTimeout(() => {
    toast.classList.remove('translate-x-full')
  }, 100)

  // Animate out and remove
  setTimeout(() => {
    toast.classList.add('translate-x-full')
    setTimeout(() => {
      document.body.removeChild(toast)
    }, 300)
  }, 3000)
}
