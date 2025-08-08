<template>
    <div style="justify-content: space-between;">
        <h1 class="text-xl">Admin Dashboard</h1>
        <form @submit.prevent="logout">
            <button class="border rounded-lg py-1 px-4 bg-red-600 text-white font-medium">Logout</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import router from '@/router'
import { apiRequest } from '@/api/apiRequest'

const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)

const logout = async () => {            
    errorMessage.value = ''         
    successMessage.value = ''
    isLoading.value = true

    try {
        const response = await apiRequest(isLoading, 'post', 'admin/logout')
        
        const token = localStorage.getItem('authToken')
        if (token) {
            localStorage.removeItem('authToken')
            console.log('Redirecting to /admin/login')
            router.push('/admin/login')
        }
        console.log(response.data)

    } catch (err) {
        console.log('Terjadi kesalahan ketike logout: ', err)
    } finally {
        isLoading.value = false
    }
}                                                                                                

</script>