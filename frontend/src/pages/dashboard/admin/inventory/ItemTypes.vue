<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Item Types</h1>
      <button
        @click="isOpen = true"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        + Create
      </button>
    </div>

    <div v-if="store.loading" class="text-gray-500">Loading...</div>
    <div v-else-if="store.error" class="text-red-500">{{ store.error }}</div>
    <table v-else class="w-full border border-gray-300">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">#</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Description</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, i) in store.itemTypes" :key="item.id">
          <td class="p-2 border">{{ i + 1 }}</td>
          <td class="p-2 border">{{ item.name }}</td>
          <td class="p-2 border">{{ item.description }}</td>
        </tr>
      </tbody>
    </table>

    <CreateBar
      v-model:open="isOpen"
      title="Create Item Type"
      :fields="formFields"
      :model-value="formData"
      submit-label="Save"
      @update:modelValue="val => formData = val"
      @submit="handleSubmit"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { useItemTypesStore } from '@/stores/itemTypes'
import CreateBar from '@/components/CreateBar.vue'

const store = useItemTypesStore()
const isOpen = ref(false)

const formData = ref({
  name: '',
  description: ''
})

const formFields = [
  { label: 'Name', model: 'name', type: 'text', placeholder: 'Enter name' },
  { label: 'Description', model: 'description', type: 'textarea', placeholder: 'Enter description' }
]

const handleSubmit = async (data) => {
  try {
    await store.createItemType(data)
    isOpen.value = false
    Swal.fire({ icon: 'success', title: 'Success', text: 'Item type created!' })
  } catch (err) {
    console.error(err)
    Swal.fire({ icon: 'error', title: 'Error', text: store.error || 'Failed to create' })
  }
}

onMounted(() => {
  store.fetchItemTypes()
})
</script>
