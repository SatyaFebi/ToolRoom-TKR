<template>
  <main class="p-6 flex-1 bg-gray-100 backdrop-blur-md">
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
        @click="toggleTambahServis"
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700 cursor-pointer"
      >
        ➕ Tambah Servis
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700 cursor-pointer"
      >
        👀 Lihat Daftar Pekerjaan
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700 cursor-pointer"
      >
        📁 Lihat Laporan
      </button>
    </section>
  </main>
  <template v-if="isTambahServisOpen">
    <div
      class="fixed inset-0 flex justify-center items-center backdrop-blur-sm z-50"
      @click.self="closeTambahServis"
    >
      <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative animate-fadeIn">
        <button
          @click="closeTambahServis"
          class="absolute top-3 right-5 text-gray-500 hover:text-gray-800 text-4xl cursor-pointer"
        >
          &times;
        </button>

        <h2 class="text-2xl font-bold mb-7 text-center text-blue-600">Tambah Data Servis</h2>

        <!-- Step Indicator -->
        <div class="flex justify-center gap-3 mb-6">
          <div
            v-for="(s, index) in steps"
            :key="index"
            class="px-3 py-1 rounded-full text-sm font-semibold"
            :class="currentStep === index ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'"
          >
            {{ s }}
          </div>
        </div>

        <!-- Step 1: Pelanggan -->
        <form v-if="currentStep === 0" @submit.prevent="goToNextStep" class="space-y-4">
          <div>
            <label class="block text-gray-700 font-semibold mb-1">Cari Pelanggan</label>
            <input
              type="text"
              v-model="searchCustomer"
              class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300"
              placeholder="Nama / No HP"
              @focus="showCustomerList = true"
            />
          </div>

          <ul v-if="showCustomerList && filteredCustomer.length" class="border rounded-lg divide-y mt-2">
            <li
              v-for="cust in filteredCustomer"
              :key="cust.id"
              class="p-2 hover:bg-blue-100 cursor-pointer"
              @mousedown="selectCustomer(cust)"
            >
              {{ cust.nama }} - {{ cust.no_hp }}
            </li>
          </ul>

          <p v-if="selectedCustomer" class="text-sm text-green-600">
            ✅ Pelanggan dipilih: <strong>{{ selectedCustomer.nama }}</strong>
          </p>

          <button
            type="button"
            class="text-blue-600 underline text-sm cursor-pointer"
            @click="showAddCustomer = true"
          >
            + Tambah Pelanggan Baru
          </button>

          <button
            type="submit"
            class="w-full mt-4 bg-blue-600 text-white rounded-lg py-2 hover:bg-blue-700 cursor-pointer"
            :disabled="!selectedCustomer"
          >
            Lanjut
          </button>
        </form>

        <!-- Step 2: Kendaraan -->
        <form v-if="currentStep === 1" @submit.prevent="goToNextStep" class="space-y-4">
          <div v-if="customerVehicles.length > 0 && !showNewVehicleForm">
            <label class="block text-gray-700 font-semibold mb-2" for="">Pilih Kendaraan</label>
            <div class="space-y-2">
                <div
                  v-for="v in customerVehicles"
                  :key="v.id"
                  class="border rounded-lg p-3 hover:bg-blue-50 cursor-pointer transition"
                  :class="selectedVehicle?.id === v.id ? 'bg-blue-100 border-blue-500' : ''"
                  @click="selectedVehicle = v"
                >
                  <p class="font-semibold">{{ v.merek }} {{ v.model }} {{ v.tahun }}</p>
                  <p class="text-sm text-gray-600">{{ v.no_polisi }}</p>
                </div>
            </div>
            <button
              type="button"
              class="text-blue-600 underline text-sm mt-3 cursor-pointer"
              @click="showNewVehicleForm = true"
            >
              + Kendaraan lain yang belum terdaftar
            </button>
          </div>

          <div v-if="customerVehicles.length === 0 || showNewVehicleForm" class="space-y-3">
            <h3 class="font-semibold text-gray-700">Tambah Kendaraan Baru</h3>
            <div>
              <label class="block text-gray-700 font-semibold mb-1">Merek</label>
              <input
                v-model="vehicle.merek"
                type="text"
                class="w-full border rounded-lg p-2"
                required
              />
            </div>
            <div>
              <label class="block text-gray-700 font-semibold mb-1">Model</label>
              <input
                v-model="vehicle.model"
                type="text"
                class="w-full border rounded-lg p-2"
                required
              />
            </div>
            <div class="flex gap-4">
              <div class="flex-1">
                <label class="block text-gray-700 font-semibold mb-1">Tahun</label>
                <input
                  v-model="vehicle.tahun"
                  type="number"
                  class="w-full border rounded-lg p-2"
                  required
                />
              </div>
              <div class="flex-1">
                <label class="block text-gray-700 font-semibold mb-1">No Polisi</label>
                <input
                  v-model="vehicle.no_polisi"
                  type="text"
                  class="w-full border rounded-lg p-2"
                  required
                />
              </div>
            </div>

            <button
              v-if="customerVehicles.length > 0"
              type="button"
              class="rounded-lg text-gray-600 underline text-sm cursor-pointer"
              @click="showNewVehicleForm = false"
            >
              ← Kembali ke pilihan kendaraan
            </button>
          </div>

          <div class="flex justify-between mt-4">
            <button type="button" @click="currentStep--" class="text-gray-600 underline cursor-pointer">
              ← Kembali
            </button>
            <button
              type="submit"
              class="bg-blue-600 text-white rounded-lg py-2 px-4 hover:bg-blue-700 cursor-pointer"
              :disabled="!selectedVehicle && !vehicle.merek"
            >
              Lanjut
            </button>
          </div>
        </form>

        <!-- Step 3: Servis -->
        <form v-if="currentStep === 2" @submit.prevent="submitServis" class="space-y-4">
          <div>
            <label class="block text-gray-700 font-semibold mb-1">Keluhan Pelanggan</label>
            <textarea
              v-model="servis.keluhan_pelanggan"
              class="w-full border rounded-lg p-2"
              required
            ></textarea>
          </div>

          <div class="flex gap-4">
            <div class="flex-1">
              <label class="block text-gray-700 font-semibold mb-1">Estimasi</label>
              <input v-model="servis.estimasi" type="text" class="w-full border rounded-lg p-2" />
            </div>
            <div class="flex-1">
              <label class="block text-gray-700 font-semibold mb-1">Taksiran Biaya</label>
              <input
                v-model="servis.taksiran_biaya"
                type="number"
                step="0.01"
                class="w-full border rounded-lg p-2"
              />
            </div>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-1">Tanggal Masuk</label>
            <input
              v-model="servis.tanggal_masuk"
              type="date"
              class="w-full border rounded-lg p-2"
            />
          </div>

          <div class="flex justify-between mt-4">
            <button type="button" @click="currentStep--" class="text-gray-600 underline">
              ← Kembali
            </button>
            <button
              type="submit"
              class="bg-blue-600 text-white rounded-lg py-2 px-4 hover:bg-blue-700"
            >
              Simpan Servis
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
</template>

<script setup>
import { ref, computed } from 'vue'

const isTambahServisOpen = ref(false)
const currentStep = ref(0)

const steps = ['Pelanggan', 'Kendaraan', 'Servis']

const searchCustomer = ref('')
const showCustomerList = ref(false)
const selectedCustomer = ref(null)
const showAddCustomer = ref(false)

const customers = ref([
  { id: 1, nama: 'Budi', no_hp: '08123456789' },
  { id: 2, nama: 'Sinta', no_hp: '08991234567' },
  { id: 3, nama: 'Ahmad', no_hp: '08567891234' },
])

const filteredCustomer = computed(() => {
  if (!searchCustomer.value) return []
  return customers.value.filter(
    (c) =>
      c.nama.toLowerCase().includes(searchCustomer.value.toLowerCase()) ||
      c.no_hp.includes(searchCustomer.value),
  )
})

const selectCustomer = (c) => {
  selectedCustomer.value = c
  searchCustomer.value = c.nama
  showCustomerList.value = false

  customerVehicles.value = [
    { id: 1, merek: 'Honda', model: 'Beat', tahun: 2020, no_polisi: 'B 1234 XYZ' },
    { id: 2, merek: 'Yamaha', model: 'Mio', tahun: 2019, no_polisi: 'B 5678 ABC' },
  ]
}

const customerVehicles = ref([])
const selectedVehicle = ref(null)
const showNewVehicleForm = ref(false)

const vehicle = ref({
  merek: '',
  model: '',
  tahun: '',
  no_polisi: '',
})

const servis = ref({
  keluhan_pelanggan: '',
  estimasi: '',
  taksiran_biaya: '',
  tanggal_masuk: new Date().toISOString().substring(0, 10),
})

const goToNextStep = () => {
  currentStep.value++
}

const submitServis = () => {
  const finalVehicle = selectedVehicle.value || vehicle.value

  const dataToSubmit = {
    customer_id: selectCustomer.value.id,
    vehicle: finalVehicle,
    keluhan_pelanggan: servis.value.keluhan_pelanggan,
    estimasi: servis.value.estimasi,
    taksiran_biaya: servis.value.taksiran_biaya,
    tanggal_masuk: servis.value.tanggal_masuk,
    status: 'menunggu'
  }

  alert('Servis berhasil ditambahkan!')
  closeTambahServis()
}

const toggleTambahServis = () => {
  isTambahServisOpen.value = true
}

const closeTambahServis = () => {
  isTambahServisOpen.value = false
  currentStep.value = 0
  searchCustomer.value = 0
  selectedCustomer.value = null
  selectedVehicle.value = null
  showNewVehicleForm.value = false
  showCustomerList.value = false
  customerVehicles.value = []
  vehicle.value = { merek: '', model: '', tahun: '', no_polisi: '' }
  servis.value = {
    keluhan_pelanggan: '',
    estimasi: '',
    taksiran_biaya: '',
    tanggal_masuk: new Date().toISOString().substring(0, 10)
  }
}
</script>

<style>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3ss ease-out;
}
</style>
