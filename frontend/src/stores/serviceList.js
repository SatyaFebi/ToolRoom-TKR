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
    lastFetched: null
  }),
  actions: {
    async fetchServiceList(force = false) {
      const authStore = useAuthStore()
      if (!authStore.token) {
        this.error = 'Unauthorized'
        return
      }
      axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`

      const now = new Date()
      const isCacheValid =
        !force &&
        this.serviceList.length > 0 &&
        this.lastFetched &&
        now - this.lastFetched < 1000 * 60 * 10 // 10 menit

      if (isCacheValid) {
        console.log('Menggunakan cache dari state, tidak fetch ulang')
        return this.serviceList
      }

      this.loading = true
      this.error = null

      try {
        const { data } = await api.get('/service/getService')
        this.serviceList = data
        this.lastFetched = new Date()
        sessionStorage.setItem('services', JSON.stringify(data))
        sessionStorage.setItem('services_cached_at', Date.now())
        return data
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal mengambil data service'
      } finally {
        this.loading = false
      }
    },

    invalidateCache () {
      this.lastFetched = null
      console.log('Cache invalidated!')
    },

    async addCustomer(name, no_telp) {
      const { data } = await api.post('/service/addCustomer', { name, no_telp })
      return data
    },
    async getData() {
      const { data } = await api.get('/service/getCustomer')
      return data
    }
  },
})
