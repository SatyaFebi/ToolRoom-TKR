import { defineStore } from 'pinia'
import api from '@/plugins/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    data: null
  }),
  actions: {
    setToken(token) {
      this.token = token
      if (token) {
        localStorage.setItem('authToken', token)
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`
      } else {
        localStorage.removeItem('authToken')
        delete api.defaults.headers.common['Authorization']
      }
    },
    async register(payload) {
      const { data } = await api.post('admin/register', payload)
      return data.user ?? data
    },
    async login(email, password) {
      const { data } = await api.post('admin/login', { email, password })
      this.setToken(data.token)
      this.user = data.user
      return data
    },
    async logout() {
      try {
        await api.post('admin/logout')
      } catch (e) {
        console.error(e)
      }
      this.user = null
      this.setToken(null)
    },
    async update(data) {
      const res = await api.post('admin/updateProfile', data)
      this.user = res.data.user
      return res.data
    },
    async getMe() {
      const res = await api.get('/me')
      this.user = res.data.user ?? res.data
      return this.user
    },
    async getUserData() {
        const { data } = await api.get('admin/getUserData')
        this.data = data?.data ?? data
        return this.data
    },
    async getUserRole() {
      const { data } = await api.get('/getRole')
      this.data = data?.data ?? data
      return this.data
    },
    async editUser(id, payload) {
      const { data } = await api.post(`admin/editUser/${id}`, payload)
      return data.user ?? data
    },
    async deleteUser(id) {
      const { data } = await api.post(`admin/deleteUser/${id}`)
      return data
    }
  }
})
