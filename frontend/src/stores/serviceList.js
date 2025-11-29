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
    version: 0, // untuk tracking perubahan data
    lastFetched: null
  }),
  actions: {
    async fetchServiceList(force = false) {
      this.error = null
      const authStore = useAuthStore()
      if (!authStore.token) {
        this.error = 'Unauthorized'
        return
      }

      const cacheTTL = 1000 * 60 * 4 // 4 menit
      const now = Date.now()
      if (!force && this.lastFetched && now - this.lastFetched < cacheTTL && this.serviceList.length > 0) {
        return this.serviceList
      }

      axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`

      this.loading = true
      this.error = null

      try {
        const { data } = await api.get('/service/getService')
        this.serviceList = data
        this.lastFetched = Date.now()

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
      this.version++
      return data
    },
    async getData() {
      const { data } = await api.get('/service/getCustomer')
      return data
    },
    async updateData(id, payload) {
      const { data } = await api.post(`/service/updateService/${id}`, payload)
      return data
    }
  },
})
