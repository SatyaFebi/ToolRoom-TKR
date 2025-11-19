<template>
  <div class="min-h-screen flex items-center justify-center bg-[#12385f]">
    <div class="w-full max-w-5xl mx-4 bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2 md:mx-5">

      <!-- Left: Background image (visible on md+ screens) -->
      <div class="hidden md:block relative">
        <div
          class="absolute inset-0 bg-cover bg-center"
          :style="`background-image: url('/assets/img/bgsamping.jpeg')`"
          aria-hidden="true"
        ></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-/90"></div>
      </div>

      <!-- Right: Login form -->
      <div class="flex items-center justify-center p-8">
        <div class="w-full md:w-[412px]">
          <!-- Logo & Header -->
          <div class="flex items-center gap-2 mb-0.5 mt-2">
            <img src="/assets/img/logo.png" alt="logo" class="w-14" />
            <h2 class="text-2xl md:text-3xl font-semibold text-[#12385f] mb-2 text-center md:text-left">
              Selamat Datang
            </h2>
          </div>
          <p class="text-md text-gray-500 mb-6 pl-0 text-left md:text-left">
            Silakan masuk menggunakan akun Anda
          </p>

          <!-- Error / Success messages -->
          <!-- <div v-if="errorMessage" class="mb-4 text-red-600 text-sm">{{ errorMessage }}</div>
          <div v-if="successMessage" class="mb-4 text-green-600 text-sm">{{ successMessage }}</div> -->

          <!-- Email field -->
          <label class="block mb-2 text-md text-gray-700">Email</label>
          <div class="flex items-center gap-3 mb-4 relative">
            <font-awesome-icon :icon="['fas', 'user']" class="text-gray-500 w-5 h-5" />
            <input
              type="email"
              v-model="email"
              placeholder="Masukan email"
              class="flex-1 py-3 px-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#12385f]"
            />
          </div>

          <!-- Password field -->
          <label class="block mb-2 text-md text-gray-700">Password</label>
          <div class="flex items-center gap-3 mb-5 relative">
            <!-- Icon kunci -->
            <font-awesome-icon :icon="['fas', 'lock']" class="text-gray-500 w-5 h-5" />

            <!-- Input -->
            <input
              :type="isPasswordVisible ? 'text' : 'password'"
              v-model="password"
              id="passwordInput"
              placeholder="Masukan password"
              class="flex-1 py-3 px-4 border rounded-lg pr-10 focus:outline-none focus:ring-2 focus:ring-[#12385f]"
            />

            <!-- Toggle eye -->
            <button
              type="button"
              @click="togglePassword"
              class="absolute right-3 top-2/4 -translate-y-1/2 text-gray-600 hover:text-[#12385f] transition"
            >
              <font-awesome-icon
                :icon="isPasswordVisible ? ['fas', 'eye-slash'] : ['fas', 'eye']"
                class="w-5 h-5"
              />
            </button>
          </div>

          <!-- Remember me -->
          <div class="flex items-center justify-between mb-8 text-sm">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" v-model="rememberMe" class="form-checkbox h-4 w-4 rounded" />
              <span>Ingat saya</span>
            </label>
          </div>

          <!-- Login button -->
          <button
            @click="handleLogin"
            :disabled="isLoading"
            class="w-full py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2 disabled:cursor-not-allowed disabled:bg-[#1e4b7a] disabled:text-white/70"
            :class="{
              'bg-[#12385f] text-white hover:bg-[#164677]': !isLoading,
              'bg-[#1e4b7a] text-white/80': isLoading
            }"
          >
            <template v-if="!isLoading">
              <span>Login</span>
            </template>
            <template v-else>
              <svg
                class="animate-spin h-5 w-5 text-white/80"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8v8z"
                ></path>
              </svg>
              <span>Loading...</span>
            </template>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import useAuth from '@/composables/useAuth'
import { ref } from 'vue'
import router from '@/router'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)
const isPasswordVisible = ref(false)

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

const togglePassword = () => {
  isPasswordVisible.value = !isPasswordVisible.value
}
</script>

<style scoped>
</style>
