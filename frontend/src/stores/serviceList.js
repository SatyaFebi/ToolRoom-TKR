import { defineStore } from 'pinia'
import axios from 'axios'
import { useAuthStore } from './auth'
import api from '@/plugins/api'

axios.defaults.baseURL = import.meta.env.VITE_APP_API_BASE_URL

export const useServiceListStore = defineStore('serviceList', {
  state: () => ({
    serviceList: [],
    loading: false,
    error: null,
  }),
  actions: {
    async fetchServiceList() {
      const authStore = useAuthStore()
      if (!authStore.token) {
        this.error = 'Unauthorized'
        return
      }
      axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/service/getService')
        this.serviceList = data
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal mengambil data service'
      } finally {
        this.loading = false
      }
    },
  },
})
