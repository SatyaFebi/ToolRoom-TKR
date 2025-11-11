<!-- src/pages/LandingPage.vue -->

<template>
  <div class="overflow-x-hidden w-full">
    <!-- HERO SECTION - ABOUT COMPANY (INDEPENDENT) -->
<section class="relative w-full overflow-hidden bg-gradient-to-br from-blue-50 via-white to-blue-100/40">
  <!-- Background Circles -->
  <div
    class="absolute top-0 right-0 w-64 h-64 md:w-96 md:h-96 bg-gradient-to-br from-[#12385f]/10 to-transparent rounded-full blur-3xl translate-x-1/3 -translate-y-1/3"
  ></div>
  <div
    class="absolute bottom-0 left-0 w-52 h-52 md:w-72 md:h-72 bg-gradient-to-tr from-blue-100/50 to-transparent rounded-full blur-3xl -translate-x-1/3 translate-y-1/3"
  ></div>

  <!-- Full Width Grid -->
  <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 min-h-[90vh] w-full">

    <!-- Left Side -->
    <div class="flex flex-col justify-center px-6 md:px-10 lg:px-14 xl:px-16 py-12 lg:py-16">

      <!-- Logo -->
      <div class="mb-10 md:mb-12">
        <div class="relative group inline-block">
          <img
            src="/assets/img/smk.png"
            alt="SMK"
            class="w-24 h-24 md:w-28 md:h-28 object-contain transform transition-transform group-hover:scale-110 duration-300"
          />
        </div>
      </div>

      <!-- Title -->
      <h2 class="text-4xl md:text-6xl lg:text-7xl font-black leading-tight mb-6">
        <span
          class="bg-gradient-to-r from-[#12385f] via-[#1a4d7f] to-[#12385f] bg-clip-text text-transparent"
        >
          Bengkel TKR
        </span>
      </h2>

      <!-- Divider -->
      <div class="flex gap-2 mb-10">
        <div class="h-1 w-20 bg-[#12385f]/80 rounded-full"></div>
        <div class="h-1 w-12 bg-[#12385f]/50 rounded-full"></div>
        <div class="h-1 w-8 bg-[#12385f]/30 rounded-full"></div>
      </div>

      <!-- Description -->
      <p class="text-gray-700 text-base md:text-lg leading-relaxed mb-8 max-w-xl lg:max-w-2xl">
        <span class="font-semibold text-[#12385f]">Bengkel TKR</span> adalah
        bengkel profesional yang berfokus pada
        <span class="font-semibold text-[#12385f]"
          >layanan servis berkualitas tinggi</span
        >
        untuk berbagai jenis kendaraan. Kami menjunjung tinggi
        <span class="font-semibold text-[#12385f]">transparansi</span> dan
        <span class="font-semibold text-[#12385f]">kecepatan</span> dalam
        setiap layanan, dilengkapi dengan sistem
        <span class="font-semibold text-[#12385f]"
          >pengecekan status servis real-time</span
        >
        untuk kenyamanan pelanggan.
      </p>

      <!-- Vehicle Brands -->
      <div class="border-t border-gray-200 pt-10">
        <p class="text-gray-600 font-medium mb-6">
          Melayani Semua Merk Kendaraan
        </p>
        <div class="flex flex-wrap gap-6 opacity-80 hover:opacity-100 transition-opacity duration-300">
          <img src="/assets/img/honda.png" alt="Honda" class="w-14 md:w-16 object-contain hover:scale-110 transition-transform" />
          <img src="/assets/img/suzuki.png" alt="Suzuki" class="w-14 md:w-16 object-contain hover:scale-110 transition-transform" />
          <img src="/assets/img/toyota.png" alt="Toyota" class="w-14 md:w-16 object-contain hover:scale-110 transition-transform" />
          <img src="/assets/img/mitsubishi.png" alt="Mitsubishi" class="w-14 md:w-16 object-contain hover:scale-110 transition-transform" />
          <img src="/assets/img/daihatsu.png" alt="Daihatsu" class="w-14 md:w-16 object-contain hover:scale-110 transition-transform" />
          <img src="/assets/img/auto.png" alt="Yamaha" class="w-14 md:w-16 object-contain hover:scale-110 transition-transform" />
        </div>
      </div>

      <!-- Bottom Dots -->
      <div class="flex gap-2 mt-10">
        <div class="w-2 h-2 rounded-full bg-[#12385f] animate-pulse"></div>
        <div class="w-2 h-2 rounded-full bg-[#12385f]/70 animate-pulse delay-200"></div>
        <div class="w-2 h-2 rounded-full bg-[#12385f]/40 animate-pulse delay-400"></div>
      </div>
    </div>

<!-- Right Side -->
<div class="relative w-full lg:self-start">
  <div class="relative w-full h-full min-h-[360px] lg:min-h-[420px]">


    <!-- Overlay Top Left -->
    <div class="absolute inset-0 bg-gradient-to-tl from-[#12385f]/5 to-transparent rounded-none"></div>

    <!-- Image -->
    <img
      src="/assets/img/bengkel.jpg"
      alt="Bengkel TKR Workshop"
      class="w-full h-full object-cover object-top rounded-none shadow-2xl hover:shadow-3xl transition-shadow duration-500"
    />

    <!-- Overlay Top -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#12385f]/20 via-transparent to-transparent pointer-events-none"></div>

  </div>
</div>


  </div>
</section>


    <!-- SEARCH SECTION - CEK STATUS SERVIS (SEPARATE) -->
    <div
      class="min-h-screen bg-gradient-to-br from-blue-50 via-slate-50 to-white overflow-x-hidden"
    >
      <div class="w-full px-4 py-12 md:py-16">

        <!-- HEADER -->
        <div class="text-center mb-12">
          <h1
            class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-3 md:mb-4 px-4"
          >
            Cek Status Servis Kendaraan
          </h1>
          <p
            class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto mb-8 md:mb-10 px-4"
          >
            Masukkan nomor polisi untuk melihat status servis
          </p>
        </div>

        <!-- Search Section -->
        <div class="max-w-2xl mx-auto mb-12 md:mb-16 px-2">
          <div class="bg-white rounded-2xl p-5 md:p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300">
            <div class="flex flex-col sm:flex-row gap-3 items-center">
              <div class="relative flex-1 w-full group">
                <span class="absolute left-4 inset-y-0 flex items-center text-gray-400 group-focus-within:text-[#12385f] transition-colors">
                  <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                  </svg>
                </span>

                <input
                  v-model="noPolisi"
                  @keyup.enter="checkVehicle"
                  type="text"
                  placeholder="Contoh: AB 1234 CD"
                  class="w-full pl-12 md:pl-14 pr-4 py-3 md:py-4 bg-gray-50 rounded-xl border-2 border-gray-200 
                         focus:border-[#12385f] focus:bg-white focus:ring-4 focus:ring-[#12385f]/10
                         transition-all duration-200 text-gray-800 placeholder-gray-400 font-medium text-base md:text-lg"
                />
              </div>

              <button
                @click="checkVehicle"
                :disabled="loading"
                class="w-full sm:w-auto px-8 py-3 md:py-4 min-w-[120px] bg-gradient-to-r from-[#12385f] to-[#0d2844] hover:from-[#0d2844] hover:to-[#12385f] disabled:from-gray-400 disabled:to-gray-500
                       text-white font-semibold rounded-xl shadow-lg hover:shadow-xl 
                       transform hover:scale-105 hover:-translate-y-0.5 active:scale-95 
                       transition-all duration-200 flex items-center justify-center gap-2"
              >
                <template v-if="!loading">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                  Cari
                </template>

                <template v-else>
                  <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 
                         3.042 1.135 5.824 3 7.938l3-2.647z"/>
                  </svg>
                  Mencari...
                </template>
              </button>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center px-4">
          <div class="inline-block bg-white rounded-2xl p-8 md:p-10 shadow-xl">
            <div class="flex flex-col items-center gap-4">
              <div class="relative">
                <div class="w-12 h-12 md:w-14 md:h-14 border-4 border-gray-200 rounded-full"></div>
                <div class="absolute inset-0 w-12 h-12 md:w-14 md:h-14 border-4 border-t-[#12385f] border-transparent rounded-full animate-spin"></div>
              </div>
              <p class="text-gray-700 font-semibold text-base md:text-lg">Mencari data...</p>
            </div>
          </div>
        </div>

        <!-- Result Card -->
        <div
          v-if="result && !loading"
          class="max-w-3xl mx-auto px-2"
        >
          <div class="bg-white rounded-2xl p-6 md:p-8 shadow-2xl border border-gray-100">

            <!-- Header Card -->
            <div class="flex items-center gap-3 md:gap-4 mb-6 md:mb-8 pb-5 md:pb-6 border-b-2 border-gray-100">
              <div class="relative group">
                <div class="absolute inset-0 bg-[#12385f]/20 rounded-xl blur-md group-hover:blur-lg transition-all"></div>
                <div class="relative bg-gradient-to-br from-[#12385f] to-[#0d2844] p-2.5 md:p-3 rounded-lg md:rounded-xl shadow-lg">
                  <svg class="w-6 h-6 md:w-7 md:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                Data Kendaraan
              </h2>
            </div>

            <!-- Vehicle Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 mb-6 md:mb-8">
              <div
                v-for="(field, label) in {
                  'Nama Pelanggan': result.nama_pelanggan,
                  'Nomor Polisi': result.no_polisi,
                  'Merek / Model': `${result.merek} / ${result.model || '-'}`,
                  'Tahun Produksi': result.tahun_produksi || '-'
                }"
                :key="label"
                class="bg-gradient-to-br from-[#12385f]/5 to-[#12385f]/10 rounded-xl p-4 md:p-5 border-2 border-[#12385f]/20 hover:border-[#12385f]/40 hover:shadow-md transition-all duration-300"
              >
                <p class="text-gray-600 text-xs md:text-sm font-semibold mb-1.5 md:mb-2 uppercase tracking-wide">{{ label }}</p>
                <p class="text-gray-900 text-lg md:text-xl font-bold break-words">{{ field }}</p>
              </div>
            </div>

            <!-- Status Service -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#12385f] to-[#0d2844] rounded-xl md:rounded-2xl p-5 md:p-6 text-white mb-6 md:mb-8 shadow-xl">
              <div class="absolute top-0 right-0 w-32 h-32 md:w-40 md:h-40 bg-white/5 rounded-full -mr-16 md:-mr-20 -mt-16 md:-mt-20"></div>
              <div class="absolute bottom-0 left-0 w-24 h-24 md:w-32 md:h-32 bg-white/5 rounded-full -ml-12 md:-ml-16 -mb-12 md:-mb-16"></div>
              
              <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                  <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-blue-100 text-xs md:text-sm font-semibold">Status Servis Terakhir</p>
                    <p class="text-xl md:text-2xl font-bold mt-1">{{ result.status_service }}</p>
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mt-5 md:mt-6 pt-5 md:pt-6 border-t border-white/20">
                  <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 md:p-4">
                    <p class="text-blue-100 text-xs font-semibold mb-1 uppercase tracking-wider">Tanggal Masuk</p>
                    <p class="font-bold text-base md:text-lg">{{ result.tanggal_masuk }}</p>
                  </div>
                  <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 md:p-4">
                    <p class="text-blue-100 text-xs font-semibold mb-1 uppercase tracking-wider">Tanggal Selesai</p>
                    <p class="font-bold text-base md:text-lg">{{ result.tanggal_selesai || 'Belum selesai' }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Riwayat Service -->
            <div v-if="result.riwayat_service && result.riwayat_service.length">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5 md:mb-6">
                <h3 class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2 md:gap-3">
                  <div class="bg-[#12385f]/10 p-1.5 md:p-2 rounded-lg">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#12385f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <span>Riwayat Servis</span>
                  <span class="text-xs md:text-sm font-semibold text-white bg-gradient-to-r from-[#12385f] to-[#0d2844] px-2.5 md:px-3 py-1 rounded-full">
                    {{ result.jumlah_service }}x
                  </span>
                </h3>

                <button
                  v-if="!showAll && result.jumlah_service > 3"
                  @click="loadAllHistory"
                  class="text-[#12385f] hover:text-[#0d2844] font-semibold text-sm flex items-center gap-1 bg-[#12385f]/10 px-3 md:px-4 py-2 rounded-lg hover:bg-[#12385f]/20 transition-all self-start sm:self-auto"
                >
                  Lihat Semua
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
              </div>

              <div class="space-y-3">
                <div
                  v-for="(item, index) in result.riwayat_service"
                  :key="index"
                  class="bg-gray-50 border-2 border-gray-200 rounded-xl p-4 md:p-5 
                         hover:border-[#12385f]/40 hover:bg-[#12385f]/5 hover:shadow-md
                         transition-all duration-300 group"
                >
                  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                    <div class="flex-1">
                      <div class="flex items-center gap-2 mb-2">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#12385f] group-hover:scale-125 transition-transform flex-shrink-0"></span>
                        <p class="font-bold text-gray-800 text-base md:text-lg">{{ item.status_service }}</p>
                      </div>
                      <div class="flex flex-wrap items-center gap-2 md:gap-3 text-xs md:text-sm text-gray-600 font-medium">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 
                               2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ item.tanggal_masuk }}</span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <span>{{ item.tanggal_selesai || 'Belum selesai' }}</span>
                      </div>
                    </div>

                    <span class="text-xs text-gray-500 font-mono font-semibold bg-gray-200 px-2.5 md:px-3 py-1 md:py-1.5 rounded-lg self-start sm:self-auto">
                      {{ item.id }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Error State -->
        <div v-if="error && !loading" class="max-w-2xl mx-auto px-2">
          <div class="bg-red-50 rounded-2xl p-5 md:p-6 border-2 border-red-200 shadow-xl">
            <div class="flex items-center gap-3 md:gap-4">
              <div class="bg-red-500 p-2.5 md:p-3 rounded-xl shadow-lg flex-shrink-0">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 
                       11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <p class="text-red-700 font-semibold text-base md:text-lg">{{ error }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
html,
body {
  overflow-x: hidden;
}
</style>


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