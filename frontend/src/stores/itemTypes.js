import { defineStore } from 'pinia'
import axios from 'axios'
import { useAuthStore } from './auth'

axios.defaults.baseURL = import.meta.env.VITE_APP_API_BASE_URL

export const useItemTypesStore = defineStore('itemTypes', {
  state: () => ({
    itemTypes: [],
    loading: false,
    error: null,
  }),
  actions: {
    async fetchItemTypes() {
      const authStore = useAuthStore()
      if (!authStore.token) {
        this.error = 'Unauthorized'
        return
      }
      axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
      this.loading = true
      this.error = null
      try {
        const { data } = await axios.get('inventory/item-types')
        this.itemTypes = data
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal fetch item types'
      } finally {
        this.loading = false
      }
    },

    async createItemType(payload) {
      const authStore = useAuthStore()
      if (!authStore.token) throw new Error('Unauthorized')
      axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
      this.loading = true
      this.error = null
      try {
        const { data } = await axios.post('inventory/item-types', payload)
        this.itemTypes.push(data)
        return data
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal membuat item type'
        throw err
      } finally {
        this.loading = false
      }
    },

    async updateItemType(id, payload) {
      const authStore = useAuthStore()
      if (!authStore.token) throw new Error('Unauthorized')
      axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
      this.loading = true
      this.error = null
      try {
        const { data } = await axios.put(`inventory/item-types/${id}`, payload)
        const index = this.itemTypes.findIndex((item) => item.id === id)
        if (index !== -1) this.itemTypes[index] = data
        return data
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal update item type'
        throw err
      } finally {
        this.loading = false
      }
    },

    async deleteItemType(id) {
      const authStore = useAuthStore()
      if (!authStore.token) throw new Error('Unauthorized')
      axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
      this.loading = true
      this.error = null
      try {
        await axios.delete(`inventory/item-types/${id}`)
        this.itemTypes = this.itemTypes.filter((item) => item.id !== id)
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal hapus item type'
        throw err
      } finally {
        this.loading = false
      }
    },
  },
})
