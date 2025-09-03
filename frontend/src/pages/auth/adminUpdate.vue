<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-6">
      <h2 class="text-2xl font-bold text-center mb-6">Update Profile</h2>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <!-- Nama -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1"
            >Nama</label
          >
          <input
            v-model="name"
            type="text"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Masukkan nama"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1"
            >Email</label
          >
          <input
            v-model="email"
            type="email"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Masukkan email"
          />
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1"
            >Password</label
          >
          <input
            v-model="password"
            type="password"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Masukkan password baru"
          />
          <p class="text-xs text-gray-500 mt-1">
            Kosongkan jika tidak ingin mengganti password
          </p>
        </div>

        <p v-if="successMessage" class="text-green-600 text-sm">{{ successMessage }}</p>
        <p v-if="errorMessage" class="text-red-600 text-sm">{{ errorMessage }}</p>

        <!-- Buttons -->
        <div class="flex justify-between gap-3 pt-4">
          <button
            type="button"
            @click="router.back()"
            class="w-1/2 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg"
          >
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue"
import router from '@/router'
import useAuth from '@/composables/useAuth'
import { useAuthStore } from '@/stores/auth'

const successMessage = ref('')
const errorMessage = ref('')

const auth = useAuthStore()
const { updateProfile, getMe } = useAuth()

const name = ref(auth.user?.name || '')
const email = ref(auth.user?.email || '')
const password = ref('')

watch(
    () => auth.user,
    (user) => {
        if (user) {
            name.value = user.name
            email.value = user.email
        }
    },
    { immediate: true }
)

const handleSubmit = async () => {
    await updateProfile({
        name: name.value,
        email: email.value,
        password: password.value
    })
    password.value = '' // Reset biar ga ke cache
    router.push('dashboard')
}

onMounted(async () => {
    if (!auth.user) {
        await getMe()
    }
})
</script>
