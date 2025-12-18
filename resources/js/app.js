// Minimal app.js (server-rendered UI)
// Keep JS minimal and only for optional UX (no Supabase calls).

document.addEventListener('DOMContentLoaded', () => {
  // Prevent double-submit on forms
  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
      const btn = form.querySelector('button[type="submit"]')
      if (btn) {
        btn.disabled = true
        btn.dataset.originalText = btn.textContent
      }
    })
  })

  // Profile tabs
  const myPostsTab = document.getElementById('myPostsTab')
  const likedPostsTab = document.getElementById('likedPostsTab')
  const myPostsContent = document.getElementById('myPostsContent')
  const likedPostsContent = document.getElementById('likedPostsContent')

  if (myPostsTab && likedPostsTab && myPostsContent && likedPostsContent) {
    myPostsTab.addEventListener('click', () => {
      myPostsTab.classList.add('tab-active')
      myPostsTab.classList.remove('tab-inactive')
      likedPostsTab.classList.remove('tab-active')
      likedPostsTab.classList.add('tab-inactive')

      myPostsContent.classList.remove('hidden')
      likedPostsContent.classList.add('hidden')
    })

    likedPostsTab.addEventListener('click', () => {
      likedPostsTab.classList.add('tab-active')
      likedPostsTab.classList.remove('tab-inactive')
      myPostsTab.classList.remove('tab-active')
      myPostsTab.classList.add('tab-inactive')

      likedPostsContent.classList.remove('hidden')
      myPostsContent.classList.add('hidden')
    })
  }

  // Edit profile modal
  const editProfileBtn = document.getElementById('editProfileBtn')
  const editProfileModal = document.getElementById('editProfileModal')
  const closeModalBtn = document.getElementById('closeModalBtn')
  const cancelEditBtn = document.getElementById('cancelEditBtn')

  const openModal = (modal) => {
    modal.classList.remove('hidden')
    modal.classList.add('flex')
    document.body.style.overflow = 'hidden'
  }

  const closeModal = (modal) => {
    modal.classList.add('hidden')
    modal.classList.remove('flex')
    document.body.style.overflow = ''
  }

  if (editProfileBtn && editProfileModal) {
    editProfileBtn.addEventListener('click', () => openModal(editProfileModal))
    closeModalBtn?.addEventListener('click', () => closeModal(editProfileModal))
    cancelEditBtn?.addEventListener('click', () => closeModal(editProfileModal))

    editProfileModal.addEventListener('click', (e) => {
      if (e.target === editProfileModal) closeModal(editProfileModal)
    })

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !editProfileModal.classList.contains('hidden')) {
        closeModal(editProfileModal)
      }
    })
  }

  // Onboarding modal (UI-only). Server decides whether to include/show it.
  const onboardingModal = document.getElementById('onboardingModal')
  const obClose = document.getElementById('ob_close')

  if (onboardingModal) {
    // if server renders without 'hidden', lock scroll
    if (!onboardingModal.classList.contains('hidden')) {
      document.body.style.overflow = 'hidden'
    }

    obClose?.addEventListener('click', () => {
      onboardingModal.classList.add('hidden')
      document.body.style.overflow = ''
    })

    onboardingModal.addEventListener('click', (e) => {
      if (e.target === onboardingModal) {
        onboardingModal.classList.add('hidden')
        document.body.style.overflow = ''
      }
    })
  }
})
