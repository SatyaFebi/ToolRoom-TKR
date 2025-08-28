<template>
    <div style="justify-content: space-between;">
        <h1 class="text-xl">Admin Dashboard</h1>
        <form @submit.prevent="handleLogout">
            <button class="border rounded-lg py-1 px-4 bg-red-600 text-white font-medium cursor-pointer">Logout</button>
        </form>
    </div>

    
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
        router.push('/admin/login')
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Logout gagal. Silakan coba lagi.'
    } finally {
        isLoading.value = false
    }
}

const fetchData = async () => {
    try {
        const { data } = await getUserData()
        userData.value = data.data
        console.log(userData)
    } catch (err) {
        errorMessage.value = 'Gagal mendapatkan data user.', err
    }
}

onMounted(() => {
    fetchData()
})
</script>