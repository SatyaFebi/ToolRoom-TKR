<template>
  <DataTable :value="customers" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
    <Column field="name" header="Name"></Column>
    <Column field="country.name" header="Country"></Column>
    <Column field="company" header="Company"></Column>
    <Column field="representative.name" header="Representative"></Column>
  </DataTable>

  <button type="button" @click="setCount" class="border px-5 py-1 bg-blue-600 text-white rounded-lg mt-5 cursor-pointer">Click here!</button>
  <p>{{ count }}</p>
</template>

<script setup>
import { ref } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const count = ref(0)

const setCount = () => {
  count.value--
}
</script>
