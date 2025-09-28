<template>
  <main class="p-6 flex-1 bg-gray-100">
    <!-- Header dalam dashboard -->
    <!-- <header class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
      <div class="flex items-center gap-3">
        <span class="text-gray-700">Halo, Satya 👋</span>
        <img
          src="https://ui-avatars.com/api/?name=Satya"
          alt="avatar"
          class="w-10 h-10 rounded-full"
        />
      </div>
    </header> -->

    <!-- Statistik Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white shadow rounded-lg p-6 text-center hover:shadow-md">
        <h2 class="text-3xl font-bold text-blue-600">12</h2>
        <p class="text-gray-600">Servis Hari Ini</p>
      </div>
      <div class="bg-white shadow rounded-lg p-6 text-center hover:shadow-md">
        <h2 class="text-3xl font-bold text-yellow-600">5</h2>
        <p class="text-gray-600">Sparepart Menipis</p>
      </div>
      <div class="bg-white shadow rounded-lg p-6 text-center hover:shadow-md">
        <h2 class="text-3xl font-bold text-green-600">3</h2>
        <p class="text-gray-600">Laporan Baru</p>
      </div>
    </section>

    <!-- Quick Actions -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700"
      >
        ➕ Tambah Servis
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700"
      >
        👀 Lihat Daftar Pekerjaan
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700"
      >
        📁 Lihat Laporan
      </button>
    </section>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import router from '@/router'
import useAuth  from '@/composables/useAuth'

const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)
const userData = ref([])

const { logout, getUserData } = useAuth()

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

const fetchData = async () => {
    try {
        const { data } = await getUserData()
        userData.value = data.data ?? data
        console.log(userData)
    } catch (err) {
        errorMessage.value = 'Gagal mendapatkan data user.', err
    }
}

onMounted(() => {
    fetchData()
})
</script>