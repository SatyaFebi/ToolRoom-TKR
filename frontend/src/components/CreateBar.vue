<template>
  <div v-if="open" class="fixed inset-y-0 right-0 z-50 flex">
    <div
      class="flex-1 bg-black bg-opacity-30"
      @click="closeBar"
    ></div>

    <div class="bg-white w-96 h-full shadow-xl p-6 relative">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">
          {{ mode === 'edit' ? `Edit ${title}` : `Tambah ${title}` }}
        </h2>
        <button
          @click="closeBar"
          class="text-gray-500 hover:text-gray-700 text-2xl"
        >
          &times;
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div v-for="field in fields" :key="field.model" class="flex flex-col">
          <label class="mb-1 font-semibold">{{ field.label }}</label>

          <input
            v-if="field.type === 'text'"
            v-model="localForm[field.model]"
            type="text"
            :placeholder="field.placeholder"
            class="border px-3 py-2 rounded"
          />

          <textarea
            v-else-if="field.type === 'textarea'"
            v-model="localForm[field.model]"
            :placeholder="field.placeholder"
            class="border px-3 py-2 rounded"
          ></textarea>

          <select
            v-else-if="field.type === 'select'"
            v-model="localForm[field.model]"
            class="border px-3 py-2 rounded"
          >
            <option value="" disabled>Pilih {{ field.label }}</option>
            <option
              v-for="opt in field.options"
              :key="opt.value"
              :value="opt.value"
            >
              {{ opt.label }}
            </option>
          </select>
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
          {{ mode === 'edit' ? 'Update' : 'Simpan' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  open: Boolean,
  title: { type: String, default: 'Data' },
  fields: { type: Array, default: () => [] },
  modelValue: { type: Object, default: () => ({}) },
  mode: { type: String, default: 'create' },
})

const emit = defineEmits(['update:open', 'update:modelValue', 'create', 'update'])

const localForm = ref({ ...props.modelValue })

watch(
  () => props.modelValue,
  (val) => {
    localForm.value = { ...val }
  }
)

watch(
  localForm,
  (val) => {
    emit('update:modelValue', val)
  },
  { deep: true }
)

const closeBar = () => {
  emit('update:open', false)
}

const handleSubmit = () => {
  if (props.mode === 'edit') {
    emit('update', localForm.value)
  } else {
    emit('create', localForm.value)
  }
}
</script>
