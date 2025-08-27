import { defineStore } from 'pinia'
import axios from 'axios'

axios.defaults.baseURL = import.meta.env.VITE_APP_API_BASE_URL

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null
  }),
  actions: {
    async login(email, password) {
      const { data } = await axios.post('admin/login', { email, password })
      this.token = data.token
      this.user = data.user
      localStorage.setItem('token', data.token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
    },
    async logout() {
      try {
        await axios.post('admin/logout')
      } catch (err) {
        console.warn('Logout API failed', err)
      }
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    },
    async fetchUser() {
      if (!this.token) return
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
      const { data } = await axios.get('me')
      this.user = data
    }
  }
})
