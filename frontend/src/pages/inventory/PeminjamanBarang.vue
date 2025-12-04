<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-7xl">
      <!-- Card Container -->
      <div class="rounded-xl bg-white shadow-lg ring-1 ring-slate-900/5">
        <!-- Header Section -->
        <div class="border-b border-slate-200 px-4 py-6 sm:px-6 lg:px-8">
          <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
              <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Daftar Peminjaman Barang</h1>
            </div>
            <button
              @click="tambahBarang"
              class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2. 5 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:from-blue-700 hover:to-blue-800 hover:shadow-lg active:scale-95 sm:px-6"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
              <span>Pinjam Barang</span>
            </button>
          </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50">
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Nama Barang</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Jenis</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Kode</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Lokasi</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Tgl Pinjam</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Tgl Kembali</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Status</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Ket</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-700 sm:px-6">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
              <tr
                v-for="(item, index) in daftarBarang"
                :key="item.id"
                class="transition-colors duration-150 hover:bg-blue-50"
              >
                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900 sm:px-6">{{ index + 1 }}</td>
                <td class="px-4 py-3 text-sm text-slate-700 sm:px-6">
                  <span class="font-medium">{{ item.barang?.nama_barang || '-' }}</span>
                </td>
                <td class="px-4 py-3 text-sm text-slate-600 sm:px-6">{{ item.barang?.jenis_barang || '-' }}</td>
                <td class="px-4 py-3 text-center text-sm text-slate-600 sm:px-6">
                  <code class="rounded bg-slate-100 px-2 py-1 font-mono text-xs">{{ item.barang?.kode_barang || '-' }}</code>
                </td>
                <td class="px-4 py-3 text-center text-sm text-slate-600 sm:px-6">{{ item.barang?.lokasi_penyimpanan || '-' }}</td>
                <td class="px-4 py-3 text-center text-sm text-slate-600 sm:px-6">{{ formatTanggal(item.tanggal_peminjaman) }}</td>
                <td class="px-4 py-3 text-center text-sm text-slate-600 sm:px-6">{{ item.tanggal_pengembalian ? formatTanggal(item.tanggal_pengembalian) : '-' }}</td>
                <td class="px-4 py-3 text-center text-sm sm:px-6">
                  <span
                    :class="[
                      'inline-block rounded-full px-3 py-1 text-xs font-semibold',
                      item.status === 'dipinjam'
                        ? 'bg-amber-100 text-amber-800'
                        : 'bg-green-100 text-green-800'
                    ]"
                  >
                    {{ item. status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center text-sm text-slate-600 sm:px-6">{{ item.keterangan || '-' }}</td>
                <td class="px-4 py-3 text-center text-sm font-medium sm:px-6">
                  <button
                    v-if="item.status === 'dipinjam'"
                    @click="showReturnScanner = true"
                    class="inline-flex items-center gap-1 rounded-md bg-green-50 px-3 py-1. 5 text-xs font-semibold text-green-700 transition-all duration-150 hover:bg-green-100 active:scale-95"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                    </svg>
                    Kembalikan
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Empty State -->
          <div v-if="daftarBarang. length === 0" class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
          </div>
        </div>

        <!-- Footer Section -->
        <div class="border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6 lg:px-8">
          <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-slate-600">
                Total peminjaman: 
                <span class="font-bold text-slate-900">{{ daftarBarang. length }}</span>
              </p>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500">
              <span class="inline-block h-3 w-3 rounded-full bg-amber-400"></span>
              <span>Sedang Dipinjam</span>
              <span class="ml-4 inline-block h-3 w-3 rounded-full bg-green-400"></span>
              <span>Dikembalikan</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scanner Popup -->
    <div
      v-if="showScanner"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
    >
      <FormPeminjaman
        @berhasilPinjam="handleBerhasilPinjam"
        @close="showScanner = false"
      />
    </div>

    <!-- Return Scanner Popup -->
    <div
      v-if="showReturnScanner"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
    >
      <FormPengembalian
        @berhasilKembali="handleBerhasilKembali"
        @close="showReturnScanner = false"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import FormPeminjaman from "./Formpeminjaman.vue";
import FormPengembalian from "./FormPengembalian.vue"; // 🔹 import form pengembalian

// state popup
const showScanner = ref(false);        // popup peminjaman
const showReturnScanner = ref(false);  // popup pengembalian

// data tabel
const daftarBarang = ref([]);

// format tanggal
const formatTanggal = (tanggal) => {
  return new Date(tanggal).toLocaleString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit"
  });
};

// fetch data peminjaman
const fetchPeminjaman = async () => {
  const token = localStorage.getItem("authToken");
  const res = await fetch("http://localhost:8000/api/inventory/Pinjam", {
    headers: { 'Authorization': `Bearer ${token}` }
  });

  if (!res.ok) {
    const text = await res.text();
    console.error("Gagal fetch peminjaman:", res.status, text);
    return;
  }

  const data = await res.json();
  console.log(" Data peminjaman:", data.data);

  if (data.success && Array.isArray(data.data)) {
    daftarBarang.value = data.data;
  } else {
    daftarBarang.value = [];
  }
};

onMounted(fetchPeminjaman);

// buka popup peminjaman
const tambahBarang = () => {
  showScanner.value = true;
};

// setelah berhasil pinjam
const handleBerhasilPinjam = async () => {
  await fetchPeminjaman();
  showScanner.value = false;
};

// setelah berhasil kembalikan
const handleBerhasilKembali = async () => {
  await fetchPeminjaman();
  showReturnScanner.value = false;
};
</script>