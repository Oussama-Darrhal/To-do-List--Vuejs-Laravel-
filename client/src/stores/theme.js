import { defineStore } from 'pinia'

const STORAGE_KEY = 'theme_preference'

export const useThemeStore = defineStore('theme', {
  state: () => ({
    mode: /** @type {'light'|'dark'|'system'} */ ('system'),
  }),
  getters: {
    isDark(state) {
      if (state.mode === 'dark') return true
      if (state.mode === 'light') return false
      return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    },
  },
  actions: {
    init() {
      const saved = localStorage.getItem(STORAGE_KEY)
      if (saved === 'light' || saved === 'dark' || saved === 'system') {
        this.mode = saved
      }
      this.apply()
      if (window.matchMedia) {
        const mq = window.matchMedia('(prefers-color-scheme: dark)')
        if (mq.addEventListener) {
          mq.addEventListener('change', this.apply)
        } else if (mq.addListener) {
          mq.addListener(this.apply)
        }
      }
    },
    setMode(mode) {
      this.mode = mode
      localStorage.setItem(STORAGE_KEY, mode)
      this.apply()
    },
    toggle() {
      this.setMode(this.isDark ? 'light' : 'dark')
    },
    apply: function() {
      const root = document.documentElement
      if (this.isDark) root.classList.add('dark')
      else root.classList.remove('dark')
    }
  }
})


