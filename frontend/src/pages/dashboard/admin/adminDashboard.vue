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
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700 cursor-pointer transition-all duration-200"
      >
        ➕ Tambah Servis
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700 cursor-pointer transition-all duration-200"
      >
        👀 Lihat Daftar Pekerjaan
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700 cursor-pointer transition-all duration-200"
      >
        📁 Lihat Laporan
      </button>
    </section>
  </main>

  <!-- MODAL -->
  <transition name="modal-fade">
    <div
      v-if="isTambahServisOpen"
      class="fixed inset-0 flex justify-center items-center backdrop-blur-md z-50"
    >
      <transition name="modal-scale">
        <div
          class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative"
          v-if="isTambahServisOpen"
        >
          <button
            @click="closeTambahServis"
            class="absolute top-3 right-5 text-gray-500 hover:text-gray-800 text-4xl cursor-pointer transition"
          >
            &times;
          </button>

          <h2 class="text-2xl font-bold mb-7 text-center text-blue-600">Tambah Data Servis</h2>

          <!-- Step Indicator -->
          <div class="flex justify-center gap-3 mb-6">
            <div
              v-for="(s, index) in steps"
              :key="index"
              class="px-3 py-1 rounded-full text-sm font-semibold cursor-not-allowed transition-all"
              :class="
                currentStep === index ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'
              "
            >
              {{ s }}
            </div>
          </div>

          <!-- Step 1: Pelanggan -->
          <form v-if="currentStep === 0" @submit.prevent="goToNextStep" class="space-y-4 relative">
            <div>
              <input
                type="search"
                class="w-full p-2 rounded-lg bg-gray-100 mt-3 focus:ring-2 focus:ring-blue-400 outline-none transition"
                placeholder="Cari Pelanggan (Nama / No. Telepon)"
                v-model="searchCustomer"
                @focus="showCustomerList = true"
              />

              <!-- Search Results -->
              <transition name="fade">
                <ul
                  v-if="showCustomerList && filteredCustomer.length"
                  class="border rounded-lg divide-y mt-2 absolute bg-white w-full max-h-48 overflow-y-auto shadow-md z-10"
                >
                  <li
                    v-for="cust in filteredCustomer"
                    :key="cust.id"
                    class="p-3 hover:bg-blue-50 cursor-pointer transition"
                    @mousedown="selectCustomer(cust)"
                  >
                    <p class="font-semibold text-gray-700">
                      {{ highlightMatch(cust.name) }}
                    </p>
                    <p class="text-sm text-gray-500">{{ cust.no_telp }}</p>
                  </li>
                </ul>
              </transition>

              <div v-if="selectedCustomer" class="flex justify-between mt-3 items-center">
                <p class="text-sm text-green-600">
                  ✅ Pelanggan dipilih: <strong>{{ selectedCustomer.name }}</strong>
                </p>
                <button
                  class="text-sm text-red-600 font-semibold cursor-pointer hover:underline"
                  @click="removeSelectedCustomer"
                >
                  Hapus
                </button>
              </div>

              <button
                type="button"
                @click="showNewCustomerForm = !showNewCustomerForm"
                class="w-full rounded-xl border p-2 mt-6 bg-gray-500 text-white font-semibold hover:bg-gray-600 transition cursor-pointer"
              >
                {{ showNewCustomerForm ? 'Tutup Form Tambah Pelanggan' : 'Tambah Pelanggan Baru' }}
              </button>

              <!-- Form Pelanggan Baru -->
              <transition name="slide-down">
                <div v-if="showNewCustomerForm" class="mt-4 space-y-3">
                  <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama</label>
                    <input
                      v-model="newCustomer.name"
                      type="text"
                      class="border w-full rounded-lg p-2"
                      placeholder="Nama pelanggan baru"
                    />
                  </div>
                  <div>
                    <label class="block font-semibold text-gray-700 mb-1">No Telepon</label>
                    <input
                      v-model="newCustomer.no_telp"
                      type="number"
                      class="border w-full rounded-lg p-2"
                      placeholder="08xxxxxxxxx"
                    />
                  </div>
                  <button
                    type="button"
                    class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg w-full hover:bg-blue-700 transition cursor-pointer"
                    @click="addNewCustomer"
                  >
                    Simpan Pelanggan
                  </button>
                </div>
              </transition>
            </div>

            <button
              type="submit"
              class="w-full mt-4 bg-blue-600 text-white rounded-lg py-2 hover:bg-blue-700 cursor-pointer font-semibold transition"
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
              <button
                type="button"
                @click="currentStep--"
                class="text-gray-600 underline cursor-pointer"
              >
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
              <button
                type="button"
                @click="currentStep--"
                class="text-gray-600 underline cursor-pointer"
              >
                ← Kembali
              </button>
              <button
                type="submit"
                class="bg-blue-600 text-white rounded-lg py-2 px-4 hover:bg-blue-700 cursor-pointer"
              >
                Simpan Servis
              </button>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Swal from 'sweetalert2'
import useServiceList from '@/composables/useServiceList'

const isTambahServisOpen = ref(false)
const currentStep = ref(0)
const steps = ['Pelanggan', 'Kendaraan', 'Servis']
const { addCustomer, getCustomer } = useServiceList()

const searchCustomer = ref('')
const showCustomerList = ref(false)
const selectedCustomer = ref(null)
const showNewCustomerForm = ref(false)

const newCustomer = ref({ name: '', no_telp: '' })

const servis = ref({
  keluhan_pelanggan: '',
  estimasi: '',
  taksiran_biaya: '',
  tanggal_masuk: new Date().toISOString().substring(0, 10),
})

const customers = ref([
  // { id: null, name: '', no_telp: '' },
])

const filteredCustomer = computed(() => {
  if (!searchCustomer.value) return []
  return customers.value.filter(
    (c) =>
      c.name.toLowerCase().includes(searchCustomer.value.toLowerCase()) ||
      c.no_telp.includes(searchCustomer.value),
  )
})

const highlightMatch = (text) => {
  const query = searchCustomer.value
  if (!query) return text
  const regex = new RegExp(`(${query})`, 'gi')
  return text.replace(regex, '$1')
}

const selectCustomer = (c) => {
  selectedCustomer.value = c
  searchCustomer.value = c.name
  showCustomerList.value = false
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

const removeSelectedCustomer = () => {
  selectedCustomer.value = null
  searchCustomer.value = ''
}

const getCustomerData = async () => {
  customers.value = []
  try {
    const res = await getCustomer()
    customers.value = Array.isArray(res.data) ? res.data : Array.isArray(res) ? res.data : []
  } catch (e) {
    Swal.fire('Gagal', 'Gagal mencari data pelanggan. Silakan hubungi admin', 'error')
    throw e
  }
}

const addNewCustomer = async () => {
  try {
    if (newCustomer.value.name && newCustomer.value.no_telp) {
      showNewCustomerForm.value = false
      await addCustomer(newCustomer.value.name, newCustomer.value.no_telp)
    } else {
      showNewCustomerForm.value = true
      Swal.fire('Gagal', 'Silakan lengkapi data dahulu!', 'error')
    }

  } catch (error) {
    console.error('Gagal menambah pelanggan baru :', error)
  }

}

const toggleTambahServis = () => (isTambahServisOpen.value = true)
const closeTambahServis = () => {
  isTambahServisOpen.value = false
  currentStep.value = 0
  searchCustomer.value = ''
  selectedCustomer.value = null
  selectedVehicle.value = null
  showNewVehicleForm.value = false
  showCustomerList.value = false
  customerVehicles.value = []

  vehicle.value = {
    merek: '',
    model: '',
    tahun: '',
    no_polisi: ''
  }

  servis.value = {
    keluhan_pelanggan: '',
    estimasi: '',
    taksiran_biaya: '',
    tanggal_masuk: new Date().toISOString().substring(0, 10),
  }
}
const goToNextStep = () => currentStep.value++

const submitServis = () => {
  Swal.fire('Berhasil', 'Servis disubmit', 'success')
}

onMounted(() => {
  getCustomerData()
})
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.4s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-scale-enter-active,
.modal-scale-leave-active {
  transition: all 0.3s ease;
}
.modal-scale-enter-from {
  transform: scale(0.9);
  opacity: 0;
}
.modal-scale-leave-to {
  transform: scale(0.95);
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
