<template>
  <!-- Popup overlay -->
  <div class="fixed inset-0 backdrop-blur-md flex justify-center items-center z-50">
    <div class="bg-white p-5 rounded-lg w-full max-w-md shadow-lg">
      <h2 class="text-lg font-semibold mb-4 text-center">Scan QR Barang</h2>

      <!-- Kamera Scanner -->
      <div id="reader" class="w-full h-[300px] mb-4 rounded overflow-hidden bg-black"></div>

      <!-- Tombol tutup -->
      <div class="mt-4 flex gap-2">
        <button
          @click="$emit('close')"
          class="flex-1 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors duration-200"
        >
          Tutup
        </button>
      </div>
    </div>
  </div>
</template>

