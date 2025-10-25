<template>
  <LandingPageNavbar />

  <main class="flex-1 p-6 overflow-y-auto pt-20">
    <router-view></router-view>
  </main>
</template>

<script setup>
import LandingPageNavbar from '@/components/LandingPage/LandingPageNavbar.vue';
</script>
