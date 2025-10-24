<template>
  <div class="p-6">
    <div class="flex justify-between mb-4">
      <h2 class="text-2xl font-bold">Daftar Service</h2>
    </div>

    <p class="text-sm text-gray-500 mb-2" v-if="lastFetched">
      Terakhir diperbarui:
      {{ new Date(lastFetched).toLocaleDateString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }}
    </p>

    <div class="datatable-container overflow-x-auto">
      <DataTable
        :value="serviceList"
        :loading="loading"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20, 50]"
        tableStyle="min-width: 100%"
        stripedRows
        class="w-full"
        scrollDirection="both"
        :scrollable="true"
        scrollHeight="flex"
      >
        <Column
          field="vehicle_id"
          header="Nomor Kendaraan"
          :style="{ minWidth: '150px' }"
        >
          <template #body="slotProps">
              {{ slotProps.data.vehicle?.no_polisi || '-' }}
          </template>
        </Column>

        <Column
          field="keluhan_pelanggan"
          header="Keluhan Pelanggan"
          :style="{ minWidth: '200px' }"
        ></Column>

        <Column
          field="taksiran_biaya"
          header="Taksiran Biaya"
          :style="{ minWidth: '150px' }"
        >
          <template #body="slotProps">
              {{ formatBiaya(slotProps.data, 'taksiran_biaya') }}
          </template>
        </Column>

        <Column
          field="tanggal_masuk"
          header="Tanggal Masuk"
          :style="{ minWidth: '130px' }"
        >
          <template #body="slotProps">
              {{ formatTanggal(slotProps.data, 'tanggal_masuk') }}
          </template>
        </Column>

        <Column
          field="tanggal_selesai"
          header="Tanggal Selesai"
          :style="{ minWidth: '130px' }"
        >
          <template #body="slotProps">
              {{ formatTanggal(slotProps.data, 'tanggal_selesai') }}
          </template>
        </Column>

        <Column
          field="status"
          header="Status"
          :style="{ minWidth: '120px' }"
        >
          <template #body="slotProps">
            <span class="capitalize font-bold">{{ slotProps.data.status }}</span>
          </template>
        </Column>

        <Column
          field="total_biaya_akhir"
          header="Total Biaya Akhir"
          :style="{ minWidth: '150px' }"
        >
          <template #body="slotProps">
              {{ formatBiaya(slotProps.data, 'total_biaya_akhir') }}
          </template>
        </Column>
        <Column
          header="Action"
        >
        <template #body="slotProps">
          <div class="flex gap-2">
            <button @click="editData(slotProps.data)" type="button" class="border rounded-lg py-1 px-5 bg-green-600 text-white font-semibold cursor-pointer hover:bg-green-500 duration-200">Edit</button>
            <button @click="deleteData(slotProps.data)" type="button" class="border rounded-lg py-1 px-5 bg-red-600 text-white font-semibold cursor-pointer hover:bg-red-500 duration-200">Hapus</button>
          </div>
        </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import useServiceList from '@/composables/useServiceList'
import Swal from 'sweetalert2'

const { serviceList, loading, error, fetchServiceList, lastFetched } = useServiceList()

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

onMounted(async () => {
   await fetchServiceList()
   if (error.value) {
      Swal.fire({ icon: 'error', title: 'Error', text: error.value })
   }
})
</script>

<style scoped>
.datatable-container {
  width: 100%;
}

:deep(.p-datatable-wrapper) {
  overflow-x: auto;
}

:deep(.p-datatable-table) {
  width: 100%;
  table-layout: auto;
}
</style>
