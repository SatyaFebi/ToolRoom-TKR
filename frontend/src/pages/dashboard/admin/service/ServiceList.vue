<template>
  <div class="p-6">
    <DataTable
      :value="serviceList"
      :loading="loading"
      paginator
      :rows="5"
      :rowsPerPageOptions="[5, 10, 20, 50]"
      tableStyle="min-width: 50rem"
    >
      <Column field="vehicle_id" header="Nomor Kendaraan"></Column>
      <Column field="keluhan_pelanggan" header="Keluhan Pelanggan"></Column>
      <Column field="taksiran_biaya" header="Taksiran Biaya"></Column>
      <Column field="tanggal_masuk" header="Tanggal Masuk"></Column>
      <Column field="tanggal_selesai" header="Tanggal Selesai"></Column>
      <Column field="status" header="Status" class="capitalize"></Column>
      <Column field="total_biaya_akhir" header="Total Biaya Akhir"></Column>
    </DataTable>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import useServiceList from '@/composables/useServiceList'
import Swal from 'sweetalert2'

const { serviceList, loading, error, fetchServiceList } = useServiceList()

onMounted( async () => {
   await fetchServiceList()
   if (error.value) {
      Swal.fire({ icon: 'error', title: 'Error', text:error.value })
   }
})
</script>
