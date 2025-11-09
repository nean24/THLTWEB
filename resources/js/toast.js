/*--------------------------------
  Toast Notification System
--------------------------------*/

// Create toast container if it doesn't exist
function createToastContainer() {
  let container = document.getElementById('toast-container')
  if (!container) {
    container = document.createElement('div')
    container.id = 'toast-container'
    container.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 space-y-2'
    document.body.appendChild(container)
  }
  return container
}

// Create and show a toast notification
export function showToast(message, type = 'info', duration = 4000) {
  const container = createToastContainer()

  // Create toast element
  const toast = document.createElement('div')
  toast.className = `
    min-w-80 max-w-md p-4 rounded-xl shadow-lg border backdrop-blur-md
    transform translate-y-full opacity-0 transition-all duration-300 ease-out
    flex items-center gap-3
  `

  // Set colors based on type
  let bgClass, borderClass, iconSvg

  switch (type) {
    case 'success':
      bgClass = 'bg-emerald-50/95 text-emerald-800'
      borderClass = 'border-emerald-200'
      iconSvg = `
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
      `
      break
    case 'error':
      bgClass = 'bg-red-50/95 text-red-800'
      borderClass = 'border-red-200'
      iconSvg = `
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      `
      break
    case 'warning':
      bgClass = 'bg-yellow-50/95 text-yellow-800'
      borderClass = 'border-yellow-200'
      iconSvg = `
        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
      `
      break
    default: // info
      bgClass = 'bg-blue-50/95 text-blue-800'
      borderClass = 'border-blue-200'
      iconSvg = `
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      `
  }

  toast.className += ` ${bgClass} ${borderClass}`

  toast.innerHTML = `
    <div class="shrink-0">
      ${iconSvg}
    </div>
    <div class="flex-1 text-sm font-medium">
      ${message}
    </div>
    <button class="toast-close shrink-0 hover:opacity-70 transition-opacity">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  `

  // Add to container
  container.appendChild(toast)

  // Animate in
  requestAnimationFrame(() => {
    toast.classList.remove('translate-y-full', 'opacity-0')
    toast.classList.add('translate-y-0', 'opacity-100')
  })

  // Auto remove after duration
  const autoRemove = setTimeout(() => removeToast(toast), duration)

  // Close button handler
  const closeBtn = toast.querySelector('.toast-close')
  closeBtn.addEventListener('click', () => {
    clearTimeout(autoRemove)
    removeToast(toast)
  })

  return toast
}

// Remove toast with animation
function removeToast(toast) {
  toast.classList.add('translate-y-full', 'opacity-0')

  setTimeout(() => {
    if (toast.parentNode) {
      toast.parentNode.removeChild(toast)
    }
  }, 300)
}

// Convenience methods
export const toast = {
  success: (message, duration) => showToast(message, 'success', duration),
  error: (message, duration) => showToast(message, 'error', duration),
  warning: (message, duration) => showToast(message, 'warning', duration),
  info: (message, duration) => showToast(message, 'info', duration)
}
