<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Item Categories</h1>

    <!-- Button Add -->
    <button
      @click="openAdd"
      class="mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    >
      + Add Category
    </button>

    <!-- Table -->
    <table class="min-w-full bg-white rounded shadow">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="py-2 px-4">ID</th>
          <th class="py-2 px-4">Name</th>
          <th class="py-2 px-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cat in categories" :key="cat.id" class="border-t">
          <td class="py-2 px-4">{{ cat.id }}</td>
          <td class="py-2 px-4">{{ cat.name }}</td>
          <td class="py-2 px-4">
            <button
              @click="openEdit(cat)"
              class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
            >
              Edit
            </button>
            <button
              @click="deleteCategory(cat.id)"
              class="ml-2 bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
            >
              Delete
            </button>
          </td>
        </tr>
        <tr v-if="!categories || categories.length === 0">
          <td colspan="3" class="text-center py-4 text-gray-500">
            No data found
          </td>
        </tr>
      </tbody>
    </table>

    <!-- SidebarForm -->
    <SidebarForm
      v-model:open="showSidebar"
      v-model:modelValue="form"
      title="Category"
      :fields="[
        { model: 'name', label: 'Nama Kategori', type: 'text', placeholder: 'Masukkan nama kategori' }
      ]"
      :mode="isEdit ? 'edit' : 'create'"
      @create="handleCreate"
      @update="handleUpdate"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useItemCategories } from "@/composables/useItemCategories"
import SidebarForm from "@/components/CreateBar.vue"

const {
  categories,
  fetchCategories,
  addCategory,
  updateCategory,
  deleteCategory,
} = useItemCategories()

const showSidebar = ref(false)
const isEdit = ref(false)
const form = ref({ id: null, name: "" })

// open for create
const openAdd = () => {
  isEdit.value = false
  form.value = { id: null, name: "" }
  showSidebar.value = true
}

// open for edit
const openEdit = (cat) => {
  isEdit.value = true
  form.value = { ...cat }
  showSidebar.value = true
}

// create handler
const handleCreate = async (payload) => {
  await addCategory(payload)
  await fetchCategories()
  showSidebar.value = false
}

// update handler
const handleUpdate = async (payload) => {
  await updateCategory(payload.id, { name: payload.name })
  await fetchCategories()
  showSidebar.value = false
}

onMounted(() => {
  fetchCategories()
})
</script>
