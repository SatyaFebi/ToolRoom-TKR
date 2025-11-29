<template>
   <div
    class="fixed top-0 left-0 right-0 bg-[#12385f] shadow z-30 flex items-center justify-end md:justify-between px-8 md:px-12 py-4"
  >
    <!-- Tombol toggle sidebar (mobile) -->
    <button class="md:hidden text-white text-xl mr-auto" @click="$emit('toggleSidebar')">
      ☰
    </button>

    <!-- Teks Halo (posisi dekat sidebar) -->
    <div
      class="absolute left-[280px] text-white font-medium text-xl hidden md:block"
    >
      Halo, {{ userName }}
    </div>

    <!-- Bagian kanan (notifikasi + profil) -->
    <div class="flex items-center gap-6 ml-auto">
      <!-- Notifikasi -->
      <div class="relative cursor-pointer">
        <span
          class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"
          >99+</span
        >
        🔔
      </div>

      <!-- Profil -->
      <div class="relative">
        <button
          @click="isProfileOpen = !isProfileOpen"
          class="flex items-center gap-2 focus:outline-none cursor-pointer p-2 rounded-lg"
        >
          <img src="/assets/img/clown.jpeg" class="w-9 h-9 rounded-full border" alt="profile"/>
          <span class="hidden md:inline text-white font-semibold text-sm">{{ userName }}</span>
        </button>

        <!-- Dropdown -->
        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-1"
        >
          <div
            v-if="isProfileOpen"
            class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-40"
          >
            <a
              href="/dashboard/admin/update"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >Update Profile</a
            >
            <a
              href="#"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >Ganti Password</a
            >
            <a
              href="#"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >Activity Logs</a
            >
            <form @submit.prevent="handleLogout">
              <button
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >Logout
              </button>
            </form>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import useAuth from '@/composables/useAuth'
import router from '@/router'
import { jwtDecode } from "jwt-decode"

const isProfileOpen = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const isLoading = ref(false)
const { logout } = useAuth()
const userName = ref('')

const handleLogout = async () => {
    successMessage.value = ''
    errorMessage.value = ''
    isLoading.value = true
    try {
        await logout()
        successMessage.value = 'Logout sukses! Mengalihkan...'
        router.push('/login')
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Logout gagal. Silakan coba lagi.'
    } finally {
        isLoading.value = false
    }
}

// Biar dropdown ketutup pas klik di luar
const handleClickOutside = (e) => {
  if (!e.target.closest(".relative")) {
    isProfileOpen.value = false
  }
}

onMounted(() => {
   const token = localStorage.getItem('authToken')
   if (token) {
      try {
         const decoded = jwtDecode(token)
         userName.value = decoded.name || decoded.sub
      } catch (err) {
         console.error('Invalid token', err)
      }
   }

  window.addEventListener("click", handleClickOutside)
})
</script>
