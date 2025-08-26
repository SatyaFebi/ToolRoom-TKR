import { defineStore } from 'pinia'

export const useItemCategoryStore = defineStore('itemCategory', {
  state: () => ({
    categories: [],
    loading: false,
    error: null
  }),
  actions: {
    async fetchCategories() {
      this.loading = true
      this.error = null
      try {
        const res = await fetch('/api/item-categories')
        if (!res.ok) throw new Error('Failed to fetch categories')
        this.categories = await res.json()
      } catch (err) {
        this.error = err.message
      } finally {
        this.loading = false
      }
    },
    async createCategory(data) {
      try {
        const res = await fetch('/api/item-categories', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
        })
        if (!res.ok) throw new Error('Failed to create category')
        const newCategory = await res.json()
        this.categories.push(newCategory)
      } catch (err) {
        this.error = err.message
      }
    },
    async updateCategory(id, data) {
      try {
        const res = await fetch(`/api/item-categories/${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
        })
        if (!res.ok) throw new Error('Failed to update category')
        const updated = await res.json()
        const index = this.categories.findIndex(c => c.id === id)
        if (index !== -1) this.categories[index] = updated
      } catch (err) {
        this.error = err.message
      }
    },
    async deleteCategory(id) {
      try {
        const res = await fetch(`/api/item-categories/${id}`, { method: 'DELETE' })
        if (!res.ok) throw new Error('Failed to delete category')
        this.categories = this.categories.filter(c => c.id !== id)
      } catch (err) {
        this.error = err.message
      }
    }
  }
})
