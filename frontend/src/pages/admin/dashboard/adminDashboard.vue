<template>
    <div style="justify-content: space-between;">
        <h1 class="text-xl">Admin Dashboard</h1>
        <form @submit.prevent="handleLogout">
            <button class="border rounded-lg py-1 px-4 bg-red-600 text-white font-medium cursor-pointer">Logout</button>
        </form>
    </div>

    <DataTable :value="userData" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
        <Column field="name" header="Name" style="width: 25%">
            
        </Column>
        <Column field="email" header="Email" style="width: 25%">
            
        </Column>
        <!-- <Column field="customers.company" header="Company" style="width: 25%">
            3
        </Column>
        <Column field="customers.position" header="Position" style="width: 25%">
            4
        </Column> -->
    </DataTable>
</template>

<script setup>
import { ref } from 'vue'
// import router from '@/router'
import useAuth  from '@/composables/useAuth'
import fetchUser from '@/composables/useAuth'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import router from '@/router';

const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)
const userData = ref([])

// const logout = async () => {            
//     errorMessage.value = ''         
//     successMessage.value = ''
//     isLoading.value = true

//     try {
//         const response = await apiRequest(isLoading, 'post', 'admin/logout')
        
//         const token = localStorage.getItem('authToken')
//         if (token) {
//             localStorage.removeItem('authToken')
//             console.log('Redirecting to /admin/login')
//             router.push('/admin/login')
//         }
//         // console.log(response.data)
//         console.log(response)

//     } catch (err) {
//         console.log('Terjadi kesalahan ketike logout: ', err)
//     } finally {
//         isLoading.value = false
//     }
// }              

// const getUserData = async () => {
//     isLoading.value = true
//     userData.value = []

//     try {
//         const response = await apiRequest(isLoading, 'get', 'admin/getUserData');
//         userData.value = response.data.data
//         console.log(response)

//     } catch (err) {
//         console.log('Terjadi error ketika mengambil data user: ', err)
//     } finally {
//         isLoading.value = false
//     }
// }

const { logout } = useAuth()

const handleLogout = async () => {
    successMessage.value = ''
    errorMessage.value = ''
    isLoading.value = true
    try {
        await logout()
        
        const token = localStorage.getItem('authToken')
        const expired = localStorage.getItem('expired_authToken')

        if (token && expired) {
            localStorage.removeItem('authToken')
            localStorage.removeItem('expired_authToken')
            successMessage.value = 'Logout sukses! Mengalihkan...'
            router.push('/admin/login')
        }

    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Logout gagal. Silakan coba lagi.'
    } finally {
        isLoading.value = false
    }
}

</script>