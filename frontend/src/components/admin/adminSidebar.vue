<template>
  <div
    :class="[
      'fixed top-0 left-0 h-full bg-white shadow-lg z-40 transition-transform duration-300',
      isOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64',
      'md:translate-x-0 md:w-64'
    ]"
  >
    <!-- Sidebar content -->
    <div class="flex items-center gap-6 p-4 border-b border-gray-200">
      <img src="#" alt="logo" class="w-8 h-8" />
      <span class="font-bold text-lg">Dickity</span>
    </div>

    <div class="p-4 ">
      <div v-for="menu, i in sidebarMenu" :key="i">
        <a :href="menu.link" class="flex items-center font-semibold gap-2 p-2 rounded hover:bg-gray-100 mb-0">
            {{ menu.title }}
        </a>
      </div>
      <!-- dst... -->
    </div>
  </div>
</template>

<script setup>
// import { ref } from 'vue'

const sidebarMenu = [
  { title: 'Service', link: '/admin/dashboard' },
  { title: 'Inventory', link: '/admin/dashboard' }
]

defineProps({
  isOpen: Boolean
})
</script>
