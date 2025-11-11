<!-- src/layouts/LandingPageLayout.vue -->
<template>
  <div class="min-h-screen flex flex-col bg-gray-50 text-gray-800">
    <!-- Navbar -->
    <LandingPageNavbar />

    <!-- Main content -->
    <main class="flex-1 p-6 overflow-y-auto pt-20">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import LandingPageNavbar from '@/components/LandingPage/LandingPageNavbar.vue'
</script>

<style scoped>
main {
  margin: 0 auto;
}
</style>
