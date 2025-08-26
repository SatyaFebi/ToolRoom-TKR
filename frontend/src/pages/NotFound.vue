<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
    <h1 class="text-4xl font-bold mb-4">404</h1>
    <p class="mb-6">Halaman tidak ditemukan.</p>
    <p>Redirecting ke halaman sebelumnya dalam <span>{{ countdown }}</span> detik...</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const countdown = ref(4)
const router = useRouter()
const route = useRoute()

onMounted(() => {
  const interval = setInterval(() => {
    countdown.value -= 1
    if (countdown.value <= 0) {
      clearInterval(interval)
      // redirect ke halaman sebelumnya atau Home jika tidak ada
      if (route?.query?.from) {
        router.push(route.query.from)
      } else {
        router.push({ name: 'Home' })
      }
    }
  }, 1000)
})
</script>
