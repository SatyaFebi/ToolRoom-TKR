<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white rounded shadow-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Admin Login</h1>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Email</label>
        <input
          v-model="email"
          type="email"
          placeholder="admin@example.com"
          class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300"
        />
      </div>

      <div class="mb-4 relative">
        <label class="block text-gray-700 mb-1">Password</label>
        <input
          :type="showPassword ? 'text' : 'password'"
          v-model="password"
          placeholder="••••••••"
          class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300"
        />
        <button
          type="button"
          @click="showPassword = !showPassword"
          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600"
        >
          {{ showPassword ? '🙈' : '👁️' }}
        </button>
      </div>

      <div class="mb-4 flex items-center">
        <input type="checkbox" id="remember" v-model="rememberMe" class="mr-2" />
        <label for="remember" class="text-gray-700">Remember Me</label>
      </div>

      <button
        @click="submit"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors"
      >
        Login
      </button>

      <p v-if="error" class="mt-4 text-red-500 text-sm text-center">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import useAuth from '@/composables/useAuth'

const router = useRouter()
const { login } = useAuth()

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const showPassword = ref(false)
const error = ref('')

onMounted(() => {
  const savedEmail = localStorage.getItem('remember_email')
  const savedPassword = localStorage.getItem('remember_password')
  if (savedEmail && savedPassword) {
    email.value = savedEmail
    password.value = atob(savedPassword)
    rememberMe.value = true
  }
})

const submit = async () => {
  error.value = ''
  try {
    await login(email.value, password.value)

    if (rememberMe.value) {
      localStorage.setItem('remember_email', email.value)
      localStorage.setItem('remember_password', btoa(password.value))
    } else {
      localStorage.removeItem('remember_email')
      localStorage.removeItem('remember_password')
    }

    router.push({ name: 'Home' })
  } catch (err) {
    error.value = err.response?.data?.message || 'Login gagal. Cek email dan password.'
  }
}
</script>

