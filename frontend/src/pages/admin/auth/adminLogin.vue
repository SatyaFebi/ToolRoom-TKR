<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-sm">
      <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>
      <form @submit.prevent="login" class="flex flex-col">
        <input
          type="email"
          placeholder="Email"
          v-model="email"
          class="border rounded-lg py-2 px-3 mb-3 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
        <input
          type="password"
          placeholder="Password"
          v-model="password"
          class="border rounded-lg py-2 px-3 mb-3 focus:outline-none focus:ring-2 focus:ring-green-500"
        >

        <!-- Checkbox Remember Me -->
        <label class="flex items-center mb-4">
          <input
            type="checkbox"
            v-model="rememberMe"
            class="mr-2 cursor-pointer"
          >
          Remember Me
        </label>

        <button
          type="submit"
          class="border rounded-lg py-2 px-3 bg-green-600 text-white hover:bg-green-800 duration-300 hover:text-slate-300 cursor-pointer"
        >
          Submit
        </button>

        <!-- Pesan sukses / error -->
        <p v-if="successMessage" class="text-green-600 mt-3 text-sm">{{ successMessage }}</p>
        <p v-if="errorMessage" class="text-red-600 mt-3 text-sm">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { apiRequest } from '@/api/apiRequest'
import { ref } from 'vue'
import router from '@/router'

const email = ref('')
const password = ref('')
const rememberMe = ref(false) // Tambahan untuk remember me
const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)

// Cek kalau sebelumnya user pernah centang Remember Me
if (localStorage.getItem('rememberEmail')) {
  email.value = localStorage.getItem('rememberEmail')
}

const login = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  isLoading.value = true

  try {
    const response = await apiRequest(isLoading, 'post', 'admin/login', {
      email: email.value,
      password: password.value,
      remember: rememberMe.value // Kirim ke backend (opsional)
    })

    if (response.token) {
      localStorage.setItem('authToken', response.token)

      // Kalau centang Remember Me → simpan email
      if (rememberMe.value) {
        localStorage.setItem('rememberEmail', email.value)
      } else {
        localStorage.removeItem('rememberEmail')
      }

      router.push('/admin/dashboard')
    }
    successMessage.value = 'Login berhasil'
  } catch (error) {
    errorMessage.value = 'Login gagal. Periksa email dan password.'
    console.error(error)
  } finally {
    isLoading.value = false
  }
}
</script>
