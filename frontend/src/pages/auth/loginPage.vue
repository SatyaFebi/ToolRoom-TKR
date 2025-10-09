<template>
  <div class="min-h-screen flex items-center justify-center bg-[#12385f]">
    <div class="w-full max-w-5xl mx-4 md:mx-0 bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">
      <!-- Left: Background image (visible on md+ screens) -->
      <div class="hidden md:block relative">
        <div
          class="absolute inset-0 bg-cover bg-center"
          :style="`background-image: url('/assets/img/bgsamping.jpeg')`"
          aria-hidden="true"
        ></div>
        <!-- Optional decorative overlay to match original look -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-/90"></div>
        <!-- You can place logo or illustration here if needed -->
        <div class="absolute left-6 top-6">
          <!-- <img src="/assets/img/logo.png" alt="logo" class="w-28" /> -->
        </div>
      </div>

      <!-- Right: Login form -->
      <div class="flex items-center justify-center p-8">
        <div class="w-full md:w-[412px]">
          <!-- Small top logo for the card (mobile) -->
          <div class="flex items-center gap-2 mb-0.5 mt-2">
            <img src="/assets/img/logo.png" alt="logo" class="w-14" />
             <h2 class="text-2xl md:text-3xl font-semibold text-[#12385f] mb-2 text-center md:text-left">Selamat Datang</h2>
          </div>

         
          <p class="text-sm text-gray-500 mb-6 pl-16 text-center md:text-left">Silakan masuk menggunakan akun Anda</p>

          <!-- Error / Success messages -->
          <div v-if="errorMessage" class="mb-4 text-red-600 text-sm">{{ errorMessage }}</div>
          <div v-if="successMessage" class="mb-4 text-green-600 text-sm">{{ successMessage }}</div>

          <!-- Email field -->
          <label class="block mb-2 text-xs text-gray-700">Email</label>
          <div class="flex items-center gap-3 mb-4">
            <img src="/assets/img/user.png" alt="user" class="w-5 h-5 object-contain" />
            <input
              type="email"
              v-model="email"
              placeholder="Masukan email"
              class="flex-1 py-3 px-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#12385f]"
            />
          </div>

          <!-- Password field -->
          <label class="block mb-2 text-xs text-gray-700">Password</label>
          <div class="flex items-center gap-3 mb-2 relative">
            <img src="/assets/img/password.png" alt="pwd" class="w-5 h-5 object-contain" />
            <input
              :type="isPasswordVisible ? 'text' : 'password'"
              v-model="password"
              id="passwordInput"
              placeholder="Masukan password"
              class="flex-1 py-3 px-4 border rounded-lg pr-10 focus:outline-none focus:ring-2 focus:ring-[#12385f]"
            />
            <!-- toggle eye -->
            <button type="button" @click="togglePassword" class="absolute right-3 top-2/4 -translate-y-1/2">
              <img src="/assets/img/mata.png" alt="toggle" class="w-5 h-5" />
            </button>
          </div>

          <!-- Remember me and forgot -->
          <div class="flex items-center justify-between mb-6 text-sm">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" v-model="rememberMe" class="form-checkbox h-4 w-4 rounded" />
              <span>Remember me</span>
            </label>

          </div>

          <!-- Login button -->
          <button
            @click="handleLogin"
            :disabled="isLoading"
            class="w-full py-3 rounded-lg text-black font-semibold shadow-lg hover:shadow-xl transition disabled:opacity-90"
            :class="{'bg-[#12385f]': !isLoading, 'bg-[#12385f]': isLoading}">
            <span v-if="!isLoading">Login</span>
            <span v-else>Loading...</span>
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

<!-- No heavy custom CSS here — Tailwind utilities handle the styling.
     If you need tiny custom tweaks, add them here scoped. -->
<style scoped>
/* Keep small helpers if needed */
</style>
