<template>
  <div class="p-6">
    <div class="flex justify-between mb-4">
      <h2 class="text-xl font-bold">Daftar Service</h2>
      <button
        @click="refreshCache"
        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg cursor-pointer"
      >
        🔄 Refresh Data
      </button>
    </div>

    <p class="text-sm text-gray-500 mb-2" v-if="lastFetched">
      Terakhir diperbarui:
      {{ new Date(lastFetched).toLocaleDateString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }}
    </p>

    <DataTable
      :value="serviceList"
      :loading="loading"
      paginator
      :rows="5"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      tableStyle="min-width: 50rem"
      stripedRows
    >
      <Column
         field="vehicle_id"
         header="Nomor Kendaraan"

      >
         <template #body="slotProps">
            {{ slotProps.data.vehicle?.no_polisi || '-' }}
         </template>
      </Column>

      <Column field="keluhan_pelanggan" header="Keluhan Pelanggan"></Column>

      <Column
         field="taksiran_biaya"
         header="Taksiran Biaya"
      >
         <template #body="slotProps">
            {{ formatBiaya(slotProps.data, 'taksiran_biaya') }}
         </template>
      </Column>

      <Column
         field="tanggal_masuk"
         header="Tanggal Masuk"
      >
         <template #body="slotProps">
            {{ formatTanggal(slotProps.data, 'tanggal_masuk') }}
         </template>
      </Column>

      <Column
         field="tanggal_selesai"
         header="Tanggal Selesai"
      >
         <template #body="slotProps">
            {{ formatTanggal(slotProps.data, 'tanggal_selesai') }}
         </template>
      </Column>

      <Column field="status" header="Status" class="capitalize font-bold"></Column>
      <Column
         field="total_biaya_akhir"
         header="Total Biaya Akhir"
      >
         <template #body="slotProps">
            {{ formatBiaya(slotProps.data, 'total_biaya_akhir') }}
         </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import useServiceList from '@/composables/useServiceList'
import Swal from 'sweetalert2'

const { serviceList, loading, error, fetchServiceList, refreshCache, lastFetched } = useServiceList()


const formatBiaya = (rowData, field) => {
   const value = rowData[field]
   if (typeof value === 'string' && value.includes(',')) {
      return value
   }
   const num = Number(value)
   if (isNaN(num)) return value

   return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0
   }).format(num).replace(",00", "")
}

const formatTanggal = (rowData, field) => {
   const val = rowData[field]
   if (!val) return "-"
   const date = new Date(val)
   const day = String(date.getDate()).padStart(2, "0")
   const month = String(date.getMonth() + 1).padStart(2, "0")
   const year = date.getFullYear()
   return `${day}-${month}-${year}`
}

onMounted(() => {
   fetchServiceList()
   if (error.value) {
      Swal.fire({ icon: 'error', title: 'Error', text:error.value })
   }
})
</script>
