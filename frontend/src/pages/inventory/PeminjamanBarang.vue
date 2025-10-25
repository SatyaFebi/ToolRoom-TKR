<template>
  <div class="bg-gray-100 min-h-screen flex flex-col items-center p-6">
    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-lg p-6">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Daftar Barang</h1>
        <button
          @click="tambahBarang"
          class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium shadow-md transition"
        >
          Tambah Barang
        </button>
      </div>

      <!-- Tabel -->
      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-blue-50 text-blue-700 uppercase text-xs font-semibold tracking-wider">
            <tr>
              <th class="px-4 py-3 text-left">No</th>
              <th class="px-4 py-3 text-left">Nama</th>
              <th class="px-4 py-3 text-left">Kelas</th>
              <th class="px-4 py-3 text-left">Barang</th>
              <th class="px-4 py-3 text-left">Kategori</th>
              <th class="px-4 py-3 text-center">Tanggal</th>
              <th class="px-4 py-3 text-center">Jumlah</th>
              <th class="px-4 py-3 text-center">Bukti</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="(item, index) in daftarBarang" :key="index" class="hover:bg-gray-50 transition">
              <td class="px-4 py-3">{{ index + 1 }}</td>
              <td class="px-4 py-3">{{ item.nama }}</td>
              <td class="px-4 py-3">{{ item.kelas }}</td>
              <td class="px-4 py-3">{{ item.barang }}</td>
              <td class="px-4 py-3">{{ item.kategori }}</td>
              <td class="px-4 py-3 text-center">{{ item.tanggal }}</td>
              <td class="px-4 py-3 text-center">{{ item.jumlah }}</td>
              <td class="px-4 py-3 text-center">
                <img v-if="item.foto" :src="getFotoUrl(item.foto)" alt="Bukti" class="h-16 w-16 object-cover rounded-md border border-gray-200">
                <span v-else class="text-gray-400 text-xs">Tidak ada</span>
              </td>
              <td class="px-4 py-3 text-center flex justify-center gap-2">
                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs">Edit</button>
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs">Hapus</button>
              </td>
            </tr>
            <tr v-if="daftarBarang.length === 0">
              <td colspan="9" class="text-center py-4 text-gray-400">Belum ada data</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
      <div class="mt-6 text-sm text-gray-600 flex justify-end">
        <p>Total barang: <span class="font-semibold text-gray-800">{{ daftarBarang.length }}</span></p>
      </div>
    </div>

    <!-- Toast -->
    <transition name="fade">
      <div v-if="showToast" class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
        {{ toastMessage }}
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import router from '@/router';
import { usePeminjamanStore } from '@/stores/peminjamanStore';

const store = usePeminjamanStore()
const daftarBarang = store.daftarBarang // pastikan ini reactive

const showToast = ref(false)
const toastMessage = ref('')

const getFotoUrl = (file) => file ? URL.createObjectURL(file) : '';

const tambahBarang = () => {
  router.push({ name: 'Formpeminjaman' });
};
</script>

<style>
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
