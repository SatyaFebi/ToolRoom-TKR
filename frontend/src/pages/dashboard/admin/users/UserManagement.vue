<template>
    <DataTable :value="userData" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
        <Column field="name" header="Name" style="width: 25%" />
        <Column field="email" header="Email" style="width: 25%" />
    </DataTable>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import useAuth from '@/composables/useAuth'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const errorMessage = ref('')
const userData = ref([])

const { getUserData } = useAuth()

const fetchData = async () => {
    try {
        const { data } = await getUserData()
        userData.value = data.data
        console.log(userData)
    } catch (err) {
        errorMessage.value = 'Gagal mendapatkan data user.', err
    }
}

onMounted(fetchData)
</script>
