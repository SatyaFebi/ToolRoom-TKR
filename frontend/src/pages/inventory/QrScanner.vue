<template>
  <!-- Overlay full screen dengan blur -->
  <div class="fixed inset-0 backdrop-blur-md flex justify-center items-center z-50">
    <!-- Kotak putih popup -->
    <div class="bg-white p-5 rounded-lg w-full max-w-md shadow-lg">
      <h2 class="text-lg font-semibold mb-4 text-center">Scan QR Barang</h2>

      <!-- Kamera Scanner -->
      <div id="reader" class="w-full h-[300px] mb-4 rounded overflow-hidden bg-white"></div>

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

<script>
import { Html5Qrcode } from "html5-qrcode";

export default {
  name: "QrScanner",
  data() {
    return {
      scanner: null,
      done: false
    };
  },
  mounted() {
    this.scanner = new Html5Qrcode("reader");

    this.scanner
      .start(
        { facingMode: "environment" },
        {fps: 30},
        (decodedText) => this.onScanSuccess(decodedText),
        (errorMessage) => {
          console.debug("Scan error:", errorMessage);
        }
      )
      .then(() => {
        console.log("Scanner berhasil dimulai");
      })
      .catch((err) => {
        console.error("Gagal start scanner:", err);
        alert("Tidak bisa mengakses kamera. Pastikan izin kamera aktif.");
      });
  },
  beforeUnmount() {
    if (this.scanner) {
      this.scanner.stop()
        .then(() => console.log("Scanner stopped"))
        .catch((err) => console.warn("Scanner sudah berhenti:", err));
    }
  },
  methods: {
    onScanSuccess(decodedText) {
      if (this.done) return;
      this.done = true;

      console.log("QR terbaca:", decodedText);

      // kirim hasil ke parent
      this.$emit("success", decodedText);

      // ❌ tidak perlu stop di sini, biar beforeUnmount yang handle
    }
  }
};
</script>