<!-- src/pages/LandingPage.vue -->
<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-white">
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-16">
      <!-- Header Section -->
      <div class="text-center mb-12 animate-fade-in">
        <div class="inline-block mb-6">
          <div class="bg-[#12385f] p-4 rounded-2xl shadow-lg">
            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
          Cek Status Servis Kendaraan
        </h1>
        
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">
          Masukkan nomor polisi untuk melihat status servis
        </p>
      </div>

      <!-- Search Section -->
      <div class="max-w-2xl mx-auto mb-12">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
              <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
              </div>
              <input
                v-model="noPolisi"
                @keyup.enter="checkVehicle"
                type="text"
                placeholder="Contoh: AB 1234 CD"
                class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:border-[#12385f] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#12385f]/30 transition-all duration-200 text-gray-800 placeholder-gray-400 font-medium"
              />
            </div>
            
            <button
              @click="checkVehicle"
              :disabled="loading"
              class="px-6 py-3 bg-[#12385f] hover:bg-[#0d2844] disabled:bg-gray-400 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap"
            >
              <svg v-if="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ loading ? 'Mencari...' : 'Cari' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center">
        <div class="inline-block bg-white rounded-2xl p-8 shadow-lg">
          <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-[#12385f]/30 border-t-[#12385f] rounded-full animate-spin"></div>
            <p class="text-gray-700 font-medium">Mencari data...</p>
          </div>
        </div>
      </div>

      <!-- Result Card -->
      <div
        v-if="result && !loading"
        class="max-w-2xl mx-auto animate-slide-up"
      >
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
          <!-- Header Card -->
          <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
            <div class="bg-[#12385f] p-3 rounded-xl">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">Data Kendaraan</h2>
            </div>
          </div>

          <!-- Vehicle Info Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-[#12385f]/10 rounded-xl p-4 border border-[#12385f]/20">
              <p class="text-gray-600 text-sm mb-1">Nama Pelanggan</p>
              <p class="text-gray-800 text-xl font-bold">{{ result.nama_pelanggan }}</p>
            </div>

            <div class="bg-[#12385f]/10 rounded-xl p-4 border border-[#12385f]/20">
              <p class="text-gray-600 text-sm mb-1">Nomor Polisi</p>
              <p class="text-gray-800 text-xl font-bold">{{ result.no_polisi }}</p>
            </div>
            
            <div class="bg-[#12385f]/10 rounded-xl p-4 border border-[#12385f]/20">
              <p class="text-gray-600 text-sm mb-1">Merek / Model</p>
              <p class="text-gray-800 text-xl font-bold">{{ result.merek }} / {{ result.model || '-' }}</p>
            </div>
            
            <div class="bg-[#12385f]/10 rounded-xl p-4 border border-[#12385f]/20">
              <p class="text-gray-600 text-sm mb-1">Tahun Produksi</p>
              <p class="text-gray-800 text-xl font-bold">{{ result.tahun_produksi || '-' }}</p>
            </div>
          </div>

          <!-- Status Service -->
          <div class="bg-gradient-to-br from-[#12385f] to-[#0d2844] rounded-xl p-6 text-white mb-6">
            <div class="flex items-center gap-3 mb-3">
              <div class="bg-white/20 p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div>
                <p class="text-blue-50 text-sm">Status Servis Terakhir</p>
                <p class="text-2xl font-bold">{{ result.status_service }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-white/20">
              <div>
                <p class="text-blue-50 text-xs mb-1">Tanggal Masuk</p>
                <p class="font-semibold">{{ result.tanggal_masuk }}</p>
              </div>
              <div>
                <p class="text-blue-50 text-xs mb-1">Tanggal Selesai</p>
                <p class="font-semibold">{{ result.tanggal_selesai || 'Belum selesai' }}</p>
              </div>
            </div>
          </div>

          <!-- Riwayat Service -->
          <div v-if="result.riwayat_service && result.riwayat_service.length">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#12385f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat Servis 
                <span class="text-sm font-normal text-gray-600">({{ result.jumlah_service }}x)</span>
              </h3>
              <button
                v-if="!showAll && result.jumlah_service > 3"
                @click="loadAllHistory"
                class="text-[#12385f] hover:text-[#0d2844] font-semibold text-sm flex items-center gap-1 transition-colors"
              >
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
            </div>

            <div class="space-y-3">
              <div 
                v-for="(item, index) in result.riwayat_service" 
                :key="index"
                class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-[#12385f]/30 hover:bg-[#12385f]/5 transition-all duration-200"
              >
                <div class="flex justify-between items-start mb-2">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                      <span class="inline-block w-2 h-2 rounded-full bg-[#12385f]"></span>
                      <p class="font-bold text-gray-800">{{ item.status_service }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <span>{{ item.tanggal_masuk }}</span>
                      <span class="text-gray-400">→</span>
                      <span>{{ item.tanggal_selesai || 'Belum selesai' }}</span>
                    </div>
                  </div>
                  <span class="text-xs text-gray-400 font-mono bg-gray-200 px-2 py-1 rounded">{{ item.id }}</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Error State -->
      <div v-if="error && !loading" class="max-w-2xl mx-auto animate-slide-up">
        <div class="bg-red-50 rounded-2xl p-6 border border-red-200">
          <div class="flex items-center gap-4">
            <div class="bg-red-500 p-3 rounded-xl flex-shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <p class="text-red-700 font-semibold">{{ error }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const noPolisi = ref('')
const result = ref(null)
const error = ref('')
const loading = ref(false)
const showAll = ref(false)

const checkVehicle = async (show_all = false) => {
  if (!noPolisi.value.trim()) {
    error.value = 'Masukkan nomor polisi terlebih dahulu.'
    result.value = null
    return
  }

  loading.value = true
  error.value = ''
  result.value = null
  showAll.value = show_all

  try {
    const res = await fetch(
      `${import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'}/service/checkVehicle/${encodeURIComponent(noPolisi.value.trim())}?show_all=${show_all}`
    )
    const data = await res.json()

    if (data.success) {
      result.value = data.data
    } else {
      error.value = data.message || 'Kendaraan tidak ditemukan.'
    }
  } finally {
    loading.value = false
  }
}

const loadAllHistory = () => checkVehicle(true)
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out;
}

.animate-slide-up {
  animation: slide-up 0.5s ease-out;
}
</style>