import { ref } from "vue"
import { useItemCategoryStore } from "@/stores/itemCategories"
import { storeToRefs } from "pinia"

export function useItemCategories() {
  const store = useItemCategoryStore()
  const { categories, loading, error } = storeToRefs(store)

  // Local state untuk popup
  const showModal = ref(false)
  const editingCategory = ref(null)

  const fetchCategories = async () => {
    await store.fetchCategories()
  }

  const addCategory = async (payload) => {
    await store.addCategory(payload)
    showModal.value = false
  }

  const updateCategory = async (id, payload) => {
    await store.updateCategory(id, payload)
    editingCategory.value = null
    showModal.value = false
  }


  const deleteCategory = async (id) => {
    await store.deleteCategory(id)
  }

  // Open modal untuk tambah
  const openAddModal = () => {
    editingCategory.value = null
    showModal.value = true
  }

  // Open modal untuk edit
  const openEditModal = (category) => {
    editingCategory.value = { ...category }
    showModal.value = true
  }

  // Close modal
  const closeModal = () => {
    showModal.value = false
    editingCategory.value = null
  }

  return {
    categories,
    loading,
    error,
    showModal,
    editingCategory,

    fetchCategories,
    addCategory,
    updateCategory,
    deleteCategory,

    openAddModal,
    openEditModal,
    closeModal,
  }
}
