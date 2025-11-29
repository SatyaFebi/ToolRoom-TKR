<template>
  <div class="p-6">
    <div class="flex justify-between mb-4">
      <h2 class="text-2xl font-bold">Daftar Service</h2>
    </div>

    <p class="text-sm text-gray-500 mb-2" v-if="lastFetched">
      Terakhir diperbarui:
      {{
        new Date(lastFetched).toLocaleDateString('id-ID', {
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
        })
      }}
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
        <Column field="vehicles.no_polisi" header="Nomor Kendaraan" :style="{ minWidth: '150px' }">
          <template #body="slotProps">
            {{ slotProps.data.vehicles?.no_polisi || '-' }}
          </template>
        </Column>

        <Column
          field="keluhan_pelanggan"
          header="Keluhan Pelanggan"
          :style="{ minWidth: '200px' }"
        ></Column>

        <Column field="taksiran_biaya" header="Taksiran Biaya" :style="{ minWidth: '150px' }">
          <template #body="slotProps">
            {{ formatBiaya(slotProps.data, 'taksiran_biaya') }}
          </template>
        </Column>

        <Column field="tanggal_masuk" header="Tanggal Masuk" :style="{ minWidth: '130px' }">
          <template #body="slotProps">
            {{ formatTanggal(slotProps.data, 'tanggal_masuk') }}
          </template>
        </Column>

        <Column field="tanggal_selesai" header="Tanggal Selesai" :style="{ minWidth: '130px' }">
          <template #body="slotProps">
            {{ formatTanggal(slotProps.data, 'tanggal_selesai') }}
          </template>
        </Column>

        <Column field="status" header="Status" :style="{ minWidth: '120px' }">
          <template #body="slotProps">
            <span
              class="capitalize font-bold border py-1 px-1 rounded-lg text-xs"
              :class="getStatusClass(slotProps.data?.status)"
            >
              {{ slotProps.data?.status }}
            </span>
          </template>
        </Column>

        <Column field="total_biaya_akhir" header="Total Biaya Akhir" :style="{ minWidth: '150px' }">
          <template #body="slotProps">
            {{ formatBiaya(slotProps.data, 'total_biaya_akhir') }}
          </template>
        </Column>
        <Column header="Action">
          <template #body="slotProps">
            <div class="flex gap-2">
              <button
                @click="openEditModal(slotProps.data)"
                type="button"
                class="border rounded-lg py-1 px-5 bg-green-600 text-white font-semibold cursor-pointer hover:bg-green-500 duration-200"
              >
                Edit
              </button>
              <button
                @click="deleteData(slotProps.data)"
                type="button"
                class="border rounded-lg py-1 px-5 bg-red-600 text-white font-semibold cursor-pointer hover:bg-red-500 duration-200"
              >
                Hapus
              </button>
            </div>
          </template>
        </Column>
      </DataTable>

      <!-- Edit Modal -->
      <div
        v-if="editButtonToggle"
        class="fixed inset-0 z-50 bg-opacity-40 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto"
      >
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg relative m-auto max-h-[90vh] flex flex-col">
          <!-- Close button -->
          <button
            @click="editButtonToggle = false"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 transition cursor-pointer"
          >
            ✕
          </button>

          <!-- Title -->
          <h2 class="text-xl font-bold mb-5">Edit Data Service</h2>

          <!-- Form -->
          <div class="space-y-4 overflow-y-auto flex-grow">
            <!-- Nomor Kendaraan -->
            <div>
              <label class="block font-semibold mb-1">Nomor Kendaraan</label>
              <input
                v-model="localData.vehicles.no_polisi"
                type="text"
                class="w-full border p-2 rounded-lg"
              />
            </div>

            <!-- Keluhan -->
            <div>
              <label class="block font-semibold mb-1">Keluhan Pelanggan</label>
              <textarea
                v-model="localData.keluhan_pelanggan"
                class="w-full border p-2 rounded-lg resize-none"
                rows="3"
              ></textarea>
            </div>

            <!-- Taksiran Biaya -->
            <div>
              <label class="block font-semibold mb-1">Taksiran Biaya</label>
              <input
                v-model="localData.taksiran_biaya"
                type="number"
                class="w-full border p-2 rounded-lg"
                min="0"
              />
            </div>

            <!-- Tanggal Masuk -->
            <div>
              <label class="block font-semibold mb-1">Tanggal Masuk</label>
              <input
                v-model="localData.tanggal_masuk"
                type="date"
                class="w-full border p-2 rounded-lg"
              />
            </div>

            <!-- Tanggal Selesai -->
            <div>
              <label class="block font-semibold mb-1">Tanggal Selesai</label>
              <input
                v-model="localData.tanggal_selesai"
                type="date"
                class="w-full border p-2 rounded-lg"
              />
            </div>

            <!-- Status -->
            <div>
              <label class="block font-semibold mb-1">Status</label>
              <select v-model="localData.status" class="w-full border p-2 rounded-lg">
                <option value="menunggu">Menunggu</option>
                <option value="dikerjakan">Dikerjakan</option>
                <option value="selesai">Selesai</option>
              </select>
            </div>

            <!-- Total Biaya Akhir -->
            <div>
              <label class="block font-semibold mb-1">Total Biaya Akhir</label>
              <input
                v-model="localData.total_biaya_akhir"
                type="number"
                min="0"
                class="w-full border p-2 rounded-lg"
              />
            </div>
          </div>

          <!-- ACTION BUTTONS -->
          <div class="flex justify-end gap-3 p-6 pt-3 border-t border-gray-300">
            <button
              @click="editButtonToggle = false"
              class="px-4 py-2 rounded-lg border font-semibold hover:bg-gray-100 transition cursor-pointer"
            >
              Batal
            </button>

            <button
              @click="submitEdit"
              class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-500 transition cursor-pointer"
            >
              Simpan
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import useServiceList from '@/composables/useServiceList'
import Swal from 'sweetalert2'

const { serviceList, loading, error, fetchServiceList, lastFetched, updateService } = useServiceList()
const editButtonToggle = ref(false)

const localData = ref({
  id: null,
  vehicles: {
    no_polisi: '',
  },
  keluhan_pelanggan: '',
  taksiran_biaya: 0,
  tanggal_masuk: '',
  tanggal_selesai: '',
  status: 'menunggu',
  total_biaya_akhir: 0,
})

const openEditModal = (row) => {
  localData.value.id = row.id

  localData.value.vehicles.no_polisi = row.vehicles?.no_polisi || ''
  localData.value.keluhan_pelanggan = row.keluhan_pelanggan || ''
  localData.value.taksiran_biaya = row.taksiran_biaya || ''
  localData.value.tanggal_masuk = row.tanggal_masuk || ''
  localData.value.tanggal_selesai = row.tanggal_selesai || ''
  localData.value.status = row.status || ''
  localData.value.total_biaya_akhir = row.total_biaya_akhir || ''

  editButtonToggle.value = true
}

const getStatusClass = (status) => {
  switch (status) {
    case 'menunggu':
      return 'bg-yellow-400 font-bold text-white border-yellow-500'
    case 'dikerjakan':
      return 'bg-blue-500 font-bold text-white border-blue-500'
    case 'selesai':
      return 'bg-green-500 font-bold text-white border-green-500'
  }
}

const formatBiaya = (rowData, field) => {
  const value = rowData[field]
  if (typeof value === 'string' && value.includes(',')) {
    return value
  }
  const num = Number(value)
  if (isNaN(num)) return value

  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  })
    .format(num)
    .replace(',00', '')
}

const formatTanggal = (rowData, field) => {
  const val = rowData[field]
  if (!val) return '-'
  const date = new Date(val)
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const year = date.getFullYear()
  return `${day}-${month}-${year}`
}

const submitEdit = async () => {
  try {
    const id = localData.value.id
    const payload = { ...localData.value }
    console.log('ID:', id)
    console.log('Data yang dikirim : ', payload)

    await updateService(id, payload)

    editButtonToggle.value = false
    await fetchServiceList()
  } catch (error) {
    Swal.fire('Error', error, 'error')
  }
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
