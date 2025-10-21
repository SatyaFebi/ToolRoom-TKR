<template>
    <body class="bg-gray-100 min-h-screen flex items-start justify-center p-6">

  <div class="w-full max-w-5xl bg-white rounded-2xl shadow-md p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
      <h1 class="text-xl font-semibold text-gray-800 mb-3 md:mb-0">Daftar Barang</h1>
      <button
        @click="tambahBarang"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Barang
      </button>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">No</th>
            <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Nama Peminjam</th>
            <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Kelas</th>
            <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Barang yang di pinjam</th>
            <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Kategori Barang</th>
            <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Tanggal Meminjam</th>
            <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Jumlah Barang</th>
            <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Bukti Peminjaman</th>
            <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Keterangan</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          <!-- Baris contoh -->
          <tr v-for="(item, index) in daftarBarang" :key="index" class="hover:bg-gray-50">
            <td class="px-4 py-3 text-gray-700">{{ index + 1 }}</td>
            <td class="px-4 py-3 text-gray-700">{{ item.nama }}</td>
            <td class="px-4 py-3 text-gray-700">{{ item.kelas }}</td>
            <td class="px-4 py-3 text-gray-700">{{ item.barang }}</td>
            <td class="px-4 py-3 text-gray-700">{{ item.kategori}}</td>
            <td class="px-4 py-3 text-gray-700">{{ item.tanggal }}</td>
            <td class="px-4 py-3 text-gray-700">{{ item.jumlah }}</td>
            <td class="px-4 py-3 text-gray-700">
            <!-- <td class="px-4 py-3 text-gray-700">{{ item.foto }}</td> -->
              <img :src="getFotoUrl(item.foto)" alt="Bukti Peminjaman" class="h-16 w-auto object-cover rounded-md" v-if="item.foto">
            </td>
            <td class="px-4 py-3 text-center">
              <button class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
              <button class="text-red-600 hover:text-red-800 font-medium ml-3">Hapus</button>
            </td>
          </tr>

         
        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div class="mt-4 text-sm text-gray-500">
      <p>Total barang: <span class="font-semibold text-gray-700">2</span></p>
    </div>
  </div>

</body> 
</template>

<script setup>
import router from '@/router';
import { usePeminjamanStore } from '@/stores/peminjamanStore';

const store = usePeminjamanStore()
const daftarBarang = store.daftarBarang

const getFotoUrl = (file) => {
  return file ? URL.createObjectURL(file) : '';
};

const tambahBarang = () => {
  router.push({ name: 'Formpeminjaman' });
};
</script>