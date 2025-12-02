<template>
  <div class="bg-gray-100 min-h-screen flex items-start justify-center p-6">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-md p-6 relative">
      <!-- Header dan Tombol -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
        <h1 class="text-xl font-semibold text-gray-800 mb-3 md:mb-0">Daftar Peminjaman Barang</h1>
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
              <th class="px-4 py-3 text-left font-medium text-gray-600 uppercase tracking-wider">Jenis Barang</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Kode Barang</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Lokasi</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Tanggal Pinjam</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Tanggal Kembali</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Status</th>
              <th class="px-4 py-3 text-center font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            <tr v-for="(item, index) in daftarBarang" :key="item.id" class="hover:bg-gray-50">
              <td class="px-4 py-3">{{ index + 1 }}</td>
              <td class="px-4 py-3">{{ item.barang?.nama_barang || '-' }}</td>
              <td class="px-4 py-3">{{ item.barang?.jenis_barang || '-' }}</td>
              <td class="px-4 py-3 text-center">{{ item.barang?.kode_barang || '-' }}</td>
              <td class="px-4 py-3 text-center">{{ item.barang?.lokasi_penyimpanan || '-' }}</td>
              <td class="px-4 py-3 text-center">{{ formatTanggal(item.tanggal_peminjaman) }}</td>
              <td class="px-4 py-3 text-center">{{ item.tanggal_pengembalian ? formatTanggal(item.tanggal_pengembalian) : '-' }}</td>
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
        <p>Total peminjaman: <span class="font-semibold text-gray-700">{{ daftarBarang.length }}</span></p>
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

const formatTanggal = (tanggal) => {
  return new Date(tanggal).toLocaleString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit"
  });
};

const fetchPeminjaman = async () => {
  const token = localStorage.getItem("authToken");
  const res = await fetch("http://localhost:8000/api/inventory/Pinjam", {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });

  if (!res.ok) {
    const text = await res.text();
    console.error("Gagal fetch peminjaman:", res.status, text);
    return;
  }

  const data = await res.json();
  console.log("Data peminjaman:", data.data); // cek isi JSON

  if (data.success && Array.isArray(data.data)) {
    daftarBarang.value = data.data;
  } else {
    daftarBarang.value = [];
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
  const token = localStorage.getItem("authToken");
  const res = await fetch(`http://localhost:8000/api/inventory/Pengembalian/${id}`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });

  if (!res.ok) {
    const text = await res.text();
    console.error("Gagal pengembalian:", res.status, text);
    return;
  }

  await fetchPeminjaman();
};
</script>