<template>
  <main class="p-6 bg-gray-100 min-h-screen">
    <!-- Statistik Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white shadow-lg rounded-xl p-6 text-center hover:shadow-xl transition">
        <h2 class="text-3xl font-bold text-blue-600">12</h2>
        <p class="text-gray-600 mt-1">Servis Hari Ini</p>
      </div>
      <div class="bg-white shadow-lg rounded-xl p-6 text-center hover:shadow-xl transition">
        <h2 class="text-3xl font-bold text-yellow-600">5</h2>
        <p class="text-gray-600 mt-1">Sparepart Menipis</p>
      </div>
      <div class="bg-white shadow-lg rounded-xl p-6 text-center hover:shadow-xl transition">
        <h2 class="text-3xl font-bold text-green-600">3</h2>
        <p class="text-gray-600 mt-1">Laporan Baru</p>
      </div>
    </section>

    <!-- Quick Actions -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <button
        @click="toggleTambahServis"
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700 transition transform hover:-translate-y-1"
      >
        ➕ Tambah Servis
      </button>
      <button
        class="bg-gray-50 text-gray-800 rounded-xl p-6 text-lg font-bold shadow hover:bg-gray-100 transition transform hover:-translate-y-1"
      >
        👀 Lihat Daftar Pekerjaan
      </button>
      <button
        class="bg-gray-50 text-gray-800 rounded-xl p-6 text-lg font-bold shadow hover:bg-gray-100 transition transform hover:-translate-y-1"
      >
        📁 Lihat Laporan
      </button>
    </section>

    <!-- MODAL -->
    <transition name="modal-fade">
      <div
        v-if="isTambahServisOpen"
        class="fixed inset-0 flex justify-center items-center backdrop-blur-sm bg-black/30 z-50"
      >
        <transition name="modal-scale">
          <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative"
            v-if="isTambahServisOpen"
          >
            <button
              @click="closeTambahServis"
              class="absolute top-3 right-5 text-gray-400 hover:text-gray-700 text-3xl font-bold transition"
            >
              &times;
            </button>

            <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Tambah Data Servis</h2>

            <!-- Step Indicator -->
            <div class="flex justify-center gap-3 mb-6">
              <div
                v-for="(s, index) in steps"
                :key="index"
                class="px-3 py-1 rounded-full text-sm font-semibold transition-all"
                :class="currentStep === index ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'"
              >
                {{ s }}
              </div>
            </div>

            <!-- Step Forms -->
            <!-- Step 1: Pelanggan -->
            <form v-if="currentStep === 0" @submit.prevent="goToNextStep" class="space-y-4 relative">
              <input
                type="search"
                placeholder="Cari Pelanggan (Nama / No. Telepon)"
                v-model="searchCustomer"
                @focus="showCustomerList = true"
                class="w-full p-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-gray-50"
              />
              <transition name="fade">
                <ul
                  v-if="showCustomerList && filteredCustomer.length"
                  class="border rounded-lg divide-y mt-2 absolute bg-white w-full max-h-48 overflow-y-auto shadow-lg z-10"
                >
                  <li
                    v-for="cust in filteredCustomer"
                    :key="cust.id"
                    class="p-3 hover:bg-blue-50 cursor-pointer transition"
                    @mousedown="selectCustomer(cust)"
                  >
                    <p class="font-semibold text-gray-700">{{ cust.name }}</p>
                    <p class="text-sm text-gray-500">{{ cust.no_telp }}</p>
                  </li>
                </ul>
              </transition>

              <button
                type="button"
                @click="showNewCustomerForm = !showNewCustomerForm"
                :disabled="selectedCustomer"
                class="w-full mt-4 rounded-xl bg-gray-500 text-white py-2 font-semibold hover:bg-gray-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ showNewCustomerForm ? 'Tutup Form Tambah Pelanggan' : 'Tambah Pelanggan Baru' }}
              </button>

              <!-- Form Pelanggan Baru -->
              <transition name="slide-down">
                <div v-if="showNewCustomerForm" class="mt-4 space-y-3">
                  <input v-model="newCustomer.name" type="text" placeholder="Nama pelanggan baru" class="w-full border rounded-lg p-2" />
                  <input v-model="newCustomer.no_telp" type="text" placeholder="08xxxxxxxx" class="w-full border rounded-lg p-2" />
                  <input v-model="newCustomer.alamat" type="text" placeholder="Alamat" class="w-full border rounded-lg p-2" />
                  <button type="button" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition" @click="addNewCustomer">
                    Simpan Pelanggan
                  </button>
                </div>
              </transition>

              <button
                type="submit"
                class="w-full mt-4 bg-blue-600 text-white rounded-lg py-2 hover:bg-blue-700 transition"
                :disabled="!selectedCustomer"
              >
                Lanjut
              </button>
            </form>

            <!-- Step 2 & 3 bisa dipertahankan sama logika, hanya ditingkatkan styling input dan tombol -->
          </div>
        </transition>
      </div>
    </transition>
  </main>
</template>

<script setup>
import { ref, computed } from 'vue'
import Swal from 'sweetalert2'

const isTambahServisOpen = ref(false)
const currentStep = ref(0)
const steps = ['Pelanggan', 'Kendaraan', 'Servis']

const searchCustomer = ref('')
const showCustomerList = ref(false)
const selectedCustomer = ref(null)
const showNewCustomerForm = ref(false)
const customers = ref([
  { id: 1, name: 'Ahmad', no_telp: '081234567890' },
  { id: 2, name: 'Budi', no_telp: '081298765432' }
])
const newCustomer = ref({ name: '', no_telp: '', alamat: '' })

const filteredCustomer = computed(() => {
  if (!searchCustomer.value) return []
  return customers.value.filter(
    (c) =>
      c.name.toLowerCase().includes(searchCustomer.value.toLowerCase()) ||
      c.no_telp.includes(searchCustomer.value)
  )
})

function selectCustomer(c) {
  selectedCustomer.value = c
  searchCustomer.value = c.name
  showCustomerList.value = false
}

function addNewCustomer() {
  if (!newCustomer.value.name || !newCustomer.value.no_telp || !newCustomer.value.alamat) {
    Swal.fire('Gagal', 'Lengkapi data terlebih dahulu', 'error')
    return
  }
  customers.value.push({ id: Date.now(), ...newCustomer.value })
  selectedCustomer.value = newCustomer.value
  showNewCustomerForm.value = false
  newCustomer.value = { name: '', no_telp: '', alamat: '' }
}

function toggleTambahServis() {
  isTambahServisOpen.value = true
}
function closeTambahServis() {
  isTambahServisOpen.value = false
  currentStep.value = 0
  selectedCustomer.value = null
  searchCustomer.value = ''
  showNewCustomerForm.value = false
}
function goToNextStep() {
  currentStep.value++
}
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
  transform: scale(0.95);
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
