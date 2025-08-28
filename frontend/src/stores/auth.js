import { defineStore } from 'pinia'
import axios from 'axios'

axios.defaults.baseURL = import.meta.env.VITE_APP_API_BASE_URL

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('authToken') || null,
    data: []
  }),
  actions: {
    async login(email, password) {
      const { data } = await axios.post('admin/login', { email, password })
      this.token = data.token
      this.user = data.user
      localStorage.setItem('authToken', data.token)
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
      localStorage.removeItem('authToken')
      delete axios.defaults.headers.common['Authorization']
    },
    async getMe() {
      if (!this.token) return
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
      const { data } = await axios.get('me')
      this.user = data
    },
    async getUserData() {
      try {
        if (this.token) {
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        }
        const { data } = await axios.get('admin/getUserData')
        this.data = data?.data ?? data
        return this.data
      } catch (err) {
        console.error('Gagal fetch user data: ', err)
        throw err
      }
    }
  }
})
