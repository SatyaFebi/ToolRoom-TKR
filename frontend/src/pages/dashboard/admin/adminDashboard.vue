<template>
  <main class="p-6 flex-1 bg-gray-100">
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
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700"
      >
        ➕ Tambah Servis
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700"
      >
        👀 Lihat Daftar Pekerjaan
      </button>
      <button
        class="bg-blue-600 text-white rounded-xl p-6 text-lg font-bold shadow hover:bg-blue-700"
      >
        📁 Lihat Laporan
      </button>
    </section>
  </main>
</template>

<script setup>

</script>
