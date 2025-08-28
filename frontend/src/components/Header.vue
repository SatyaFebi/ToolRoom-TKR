<template>
  <header class="w-full bg-gray-800 text-white p-4 flex justify-end">
    <button
      @click="logoutHandler"
      class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded"
    >
      Logout
    </button>
  </header>
</template>

<script setup>
import { useRouter } from 'vue-router'
import useAuth from '@/composables/useAuth'

const router = useRouter()
const { logout } = useAuth()

const logoutHandler = async () => {
  await logout()
  router.replace({ name: 'Login' })
}
</script>
