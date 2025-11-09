import './bootstrap'
import { initAuthUI, initLogin, initRegister } from './auth.js'
import { loadFeed, initComposer, initLikeHandlers } from './feed.js'
import { initProfile } from './profile.js'
import './toast.js' // Import toast system

document.addEventListener("DOMContentLoaded", () => {
  initAuthUI()
  initLogin()
  initRegister()
  loadFeed()
  initComposer()
  initLikeHandlers()
  initProfile()
})
