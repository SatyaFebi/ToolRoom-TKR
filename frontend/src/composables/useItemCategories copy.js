import { defineStore } from 'pinia'
import axios from 'axios'

export const useItemCategoriesStore = defineStore('itemCategories', {
  state: () => ({
    itemCategories: [],
    itemCategory: null,
    loading: false,
    error: null,
  }),

  actions: {
    async fetchItemCategories() {
      this.loading = true
      this.error = null
      try {
        const res = await axios.get('/api/item-categories')
        this.itemCategories = res.data
      } catch (err) {
        this.error = err.response?.data?.message || err.message
      } finally {
        this.loading = false
      }
    },

    async fetchItemCategory(id) {
      this.loading = true
      this.error = null
      try {
        const res = await axios.get(`/api/item-categories/${id}`)
        this.itemCategory = res.data
      } catch (err) {
        this.error = err.response?.data?.message || err.message
      } finally {
        this.loading = false
      }
    },

    async createItemCategory(payload) {
      this.loading = true
      this.error = null
      try {
        const res = await axios.post('/api/item-categories', payload)
        this.itemCategories.push(res.data)
        return res.data
      } catch (err) {
        this.error = err.response?.data?.message || err.message
        throw err
      } finally {
        this.loading = false
      }
    },

    async updateItemCategory(id, payload) {
      this.loading = true
      this.error = null
      try {
        const res = await axios.put(`/api/item-categories/${id}`, payload)
        const index = this.itemCategories.findIndex((c) => c.id === id)
        if (index !== -1) this.itemCategories[index] = res.data
        return res.data
      } catch (err) {
        this.error = err.response?.data?.message || err.message
        throw err
      } finally {
        this.loading = false
      }
    },

    async deleteItemCategory(id) {
      this.loading = true
      this.error = null
      try {
        await axios.delete(`/api/item-categories/${id}`)
        this.itemCategories = this.itemCategories.filter((c) => c.id !== id)
      } catch (err) {
        this.error = err.response?.data?.message || err.message
        throw err
      } finally {
        this.loading = false
      }
    },
  },
})
