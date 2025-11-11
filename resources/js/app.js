// Complete app.js with all imports and initialization
import './bootstrap'
import { initAuthUI, initLogin, initRegister } from './auth.js'
import { initFeed, initComposer, initLikeHandlers } from './feed.js'
import { initProfile } from './profile.js'
import { initOnboarding } from './onboarding.js'
import { loadPostDetail, loadComments, initCommentComposer } from './comments.js'

document.addEventListener("DOMContentLoaded", async () => {
  console.log('=== APP INITIALIZATION ===')
  console.log('Current path:', window.location.pathname)

  // Always initialize auth UI
  initAuthUI()

  // Page-specific initialization
  const path = window.location.pathname

  if (path === '/' || path === '/home') {
    console.log('Initializing home page')
    initFeed() // This now includes initLikeHandlers()
    initComposer()
    await initOnboarding()
  } else if (path === '/profile') {
    console.log('Initializing profile page')
    initProfile()
  } else if (path === '/login') {
    console.log('Initializing login page')
    initLogin()
  } else if (path === '/register') {
    console.log('Initializing register page')
    initRegister()
  } else if (path.startsWith('/posts/') || path.startsWith('/post/')) {
    console.log('Initializing post detail page for path:', path)
    // Post detail page - support both /posts/:id and /post/:id
    initLikeHandlers() // For post detail likes
    loadPostDetail()
    loadComments()
    initCommentComposer()
  } else {
    console.log('Unknown route:', path)
  }
})
