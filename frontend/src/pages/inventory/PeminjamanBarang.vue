<template>
  <div class="bg-gray-100 min-h-screen flex items-start justify-center p-6">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-md p-6 relative">
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
          Pinjam Barang
        </button>
      </div>

      <!-- Tabel -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">No</th>
              <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Nama Barang</th>
              <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Kategori</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Kode Barang</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Tanggal Pinjam</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Tanggal Kembali</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Status</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            <tr v-for="(item, index) in daftarBarang" :key="item.id" class="hover:bg-gray-50">
              <td class="px-4 py-3">{{ index + 1 }}</td>
              <td class="px-4 py-3">{{ item.barang.nama_barang }}</td>
              <td class="px-4 py-3">{{ item.barang.kategori }}</td>
              <td class="px-4 py-3 text-center">{{ item.barang.kode_barang }}</td>
              <td class="px-4 py-3 text-center">{{ item.tanggal_peminjaman }}</td>
              <td class="px-4 py-3 text-center">{{ item.tanggal_pengembalian || '-' }}</td>
              <td class="px-4 py-3 text-center">{{ item.status }}</td>
              <td class="px-4 py-3 text-center">
                <button
                  v-if="item.status === 'dipinjam'"
                  @click="kembalikanBarang(item.id)"
                  class="text-green-600 hover:text-green-800 font-medium"
                >
                  Kembalikan
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Footer -->
      <div class="mt-4 text-sm text-gray-500">
        <p>Total barang: <span class="font-semibold text-gray-700">{{ daftarBarang.length }}</span></p>
      </div>

      <!-- Scanner popup -->
      <div v-if="showScanner" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
        <FormPeminjaman
          @berhasilPinjam="handleBerhasilPinjam"
          @close="showScanner = false"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import FormPeminjaman from "./Formpeminjaman.vue";

const showScanner = ref(false);
const daftarBarang = ref([]);

const fetchPeminjaman = async () => {
  const res = await fetch("/api/Pinjam");
  const data = await res.json();
  if (data.success) {
    daftarBarang.value = data.data;
  }
};

onMounted(fetchPeminjaman);

const tambahBarang = () => {
  showScanner.value = true;
};

const handleBerhasilPinjam = async () => {
  await fetchPeminjaman();
  showScanner.value = false;
};

const kembalikanBarang = async (id) => {
  await fetch(`/api/Pengembalian/${id}`, { method: "POST" });
  await fetchPeminjaman();
};
</script>