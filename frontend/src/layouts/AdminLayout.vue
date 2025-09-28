<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <SidebarAdmin :isOpen="sidebarOpen" />

    <!-- Overlay (mobile) -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-black/40 z-30 md:hidden"
      @click="sidebarOpen = false"
    ></div>

    <!-- Main area -->
    <div class="flex flex-col flex-1 md:ml-64">
      <!-- Navbar -->
      <NavbarAdmin @toggleSidebar="sidebarOpen = !sidebarOpen" />

      <!-- Content -->
      <main class="flex-1 p-6 overflow-y-auto pt-20">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import NavbarAdmin from '@/components/admin/AdminNavbar.vue'
import SidebarAdmin from '@/components/admin/AdminSidebar.vue'

const sidebarOpen = ref(false)
</script>
