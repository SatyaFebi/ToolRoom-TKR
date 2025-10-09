<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-sm">
      <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>
      <form @submit.prevent="handleLogin" class="flex flex-col">
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
          class="border flex justify-center align-items-center text-center rounded-lg py-2 px-3 bg-green-600 text-white hover:bg-green-800 duration-300 hover:text-slate-300 cursor-pointer"
          :disabled="isLoading"
        >
          <div v-if="isLoading === true">
            <svg class="mr-3 size-5 animate-spin " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="#ffffff" d="M272 112C272 85.5 293.5 64 320 64C346.5 64 368 85.5 368 112C368 138.5 346.5 160 320 160C293.5 160 272 138.5 272 112zM272 528C272 501.5 293.5 480 320 480C346.5 480 368 501.5 368 528C368 554.5 346.5 576 320 576C293.5 576 272 554.5 272 528zM112 272C138.5 272 160 293.5 160 320C160 346.5 138.5 368 112 368C85.5 368 64 346.5 64 320C64 293.5 85.5 272 112 272zM480 320C480 293.5 501.5 272 528 272C554.5 272 576 293.5 576 320C576 346.5 554.5 368 528 368C501.5 368 480 346.5 480 320zM139 433.1C157.8 414.3 188.1 414.3 206.9 433.1C225.7 451.9 225.7 482.2 206.9 501C188.1 519.8 157.8 519.8 139 501C120.2 482.2 120.2 451.9 139 433.1zM139 139C157.8 120.2 188.1 120.2 206.9 139C225.7 157.8 225.7 188.1 206.9 206.9C188.1 225.7 157.8 225.7 139 206.9C120.2 188.1 120.2 157.8 139 139zM501 433.1C519.8 451.9 519.8 482.2 501 501C482.2 519.8 451.9 519.8 433.1 501C414.3 482.2 414.3 451.9 433.1 433.1C451.9 414.3 482.2 414.3 501 433.1z"/></svg>
          </div>
          <div v-else>Submit</div>
        </button>

        <!-- Pesan sukses / error -->
        <p v-if="successMessage" class="text-green-600 mt-3 text-sm">{{ successMessage }}</p>
        <p v-if="errorMessage" class="text-red-600 mt-3 font-semibold text-sm">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>

import useAuth from '@/composables/useAuth'
import { ref } from 'vue'
import router from '@/router'

const email = ref('')
const password = ref('')
const rememberMe = ref(false) // Tambahan untuk remember me
const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)

const { login } = useAuth()

// Cek kalau sebelumnya user pernah centang Remember Me
if (localStorage.getItem('rememberEmail')) {
  email.value = localStorage.getItem('rememberEmail')
}

const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''
  successMessage.value = ''
  try {
    await login(email.value, password.value, rememberMe.value)
    if (rememberMe.value) {
      localStorage.setItem('rememberEmail', email.value)
    } else {
      localStorage.removeItem('rememberEmail')
    }

    successMessage.value = 'Login sukses! Mengalihkan...'
    router.push('/dashboard/admin')
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Login gagal. Silakan coba lagi.'
  } finally {
    isLoading.value = false
  }
}

</script>
