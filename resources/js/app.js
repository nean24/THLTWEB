import './bootstrap'
import { initAuthUI, initLogin, initRegister } from './auth.js'
import { loadFeed, initComposer, initLikeHandlers } from './feed.js'
import { initProfile } from './profile.js'
import { initOnboarding } from './onboarding.js'
import './toast.js' // Import toast system

document.addEventListener("DOMContentLoaded", async () => {
  initAuthUI()
  initLogin()
  initRegister()
await initOnboarding()
  loadFeed()
  initComposer()
  initLikeHandlers()
  initProfile()

})
