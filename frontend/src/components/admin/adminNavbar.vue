<template>
  <nav
    class="fixed top-0 left-0 right-0 h-20 bg-gradient-to-r from-blue-300 to-white shadow-lg shadow-blue-200/40 z-30 flex items-center px-6 md:px-12 backdrop-blur-sm"
  >
    <!-- Tombol Sidebar (Mobile) -->
    <button 
      class="md:hidden text-white text-2xl mr-4 hover:scale-110 transition" 
      @click="$emit('toggleSidebar')"
    >
      ☰
    </button>

    <!-- Teks Selamat Datang -->
    <h2 class="hidden md:block ml-64 text-gray-800 font-bold text-xl tracking-wide drop-shadow-sm ">
      Selamat datang, {{ userName }}
    </h2>

    <!-- Bagian kanan -->
    <div class="flex items-center gap-6 ml-auto">
      <!-- Notifikasi -->
      <div class="relative cursor-pointer group">
        <BellIcon class="w-7 h-7 text-gray-700 group-hover:text-blue-600 transition-all duration-200" />
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full shadow">
          99+
        </span>
      </div>

      <!-- Profil -->
      <div class="relative">
        <button
          @click="isProfileOpen = !isProfileOpen"
          class="flex items-center gap-3 hover:bg-blue-100/30 px-3 py-2 rounded-lg transition"
        >
          <img 
            src="/assets/img/clown.jpeg" 
            class="w-10 h-10 rounded-full border-2 border-white shadow" 
          />
          <span class="hidden md:inline text-gray-800 font-medium">{{ userName }}</span>
        </button>

        <!-- Dropdown -->
        <transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="transform opacity-0 -translate-y-2"
          enter-to-class="transform opacity-100 translate-y-0"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="transform opacity-100 translate-y-0"
          leave-to-class="transform opacity-0 -translate-y-2"
        >
          <div
            v-if="isProfileOpen"
            class="absolute right-0 mt-3 w-52 bg-white border rounded-lg shadow-lg py-2 z-50"
          >
            <a href="/dashboard/admin/update" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Update Profile</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Ganti Password</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Activity Logs</a>

            <form @submit.prevent="handleLogout">
              <button class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Logout</button>
            </form>
          </div>
        </transition>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { BellIcon } from "@heroicons/vue/24/outline"
import { jwtDecode } from "jwt-decode"
import useAuth from "@/composables/useAuth"
import router from "@/router"

const isProfileOpen = ref(false)
const userName = ref("")
const { logout } = useAuth()

const handleLogout = async () => {
  try {
    await logout()
    router.push("/login")
  } catch (err) {
    console.error(err)
  }
}

const handleClickOutside = (e) => {
  if (!e.target.closest(".relative")) {
    isProfileOpen.value = false
  }
}

onMounted(() => {
  const token = localStorage.getItem("authToken")
  if (token) {
    try {
      const decoded = jwtDecode(token)
      userName.value = decoded.name || decoded.sub || "User"
    } catch {
      userName.value = "User"
    }
  }
  window.addEventListener("click", handleClickOutside)
})
</script>
