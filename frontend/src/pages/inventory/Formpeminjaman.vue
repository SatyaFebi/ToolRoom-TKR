<template>
  <div class="overlay">
    <div class="modal">
      <h2 class="text-lg font-semibold mb-4 text-center">Scan QR Barang</h2>

      <!-- Kamera Scanner -->
      <div id="reader" class="mb-4"></div>

      <!-- Tombol tutup -->
      <div class="mt-4 flex gap-2">
        <button
          @click="$emit('close')"
          class="flex-1 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
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
  name: "FormPeminjaman",
  data() {
    return {
      scanner: null
    };
  },
  mounted() {
    console.log("Mulai Scanner");
    this.scanner = new Html5Qrcode("reader");

    this.scanner.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: 250 },
      (decodedText) => this.onScanSuccess(decodedText),
      (errorMessage) => this.onScanError(errorMessage)
    ).then(() => {
      console.log("Scanner berhasil dimulai");
    }).catch((err) => {
      console.error("Gagal start scanner:", err);
    });
  },
  beforeUnmount() {
    if (this.scanner) {
      this.scanner.stop().catch(() => {});
    }
  },
  methods: {
    async onScanSuccess(decodedText) {
  console.log("QR terbaca:", decodedText);

  // Ambil kode_barang dari string
  const match = decodedText.match(/Kode Barang:\s*(\S+)/);
  if (!match) {
    console.error("Format QR tidak valid");
    return;
  }

  const kode_barang = match[1];
  console.log("Kode Barang:", kode_barang);

  const token = localStorage.getItem('authToken');
  try {
    const res = await fetch(`http://localhost:8000/api/inventory/barang/${kode_barang}`, {
        headers:{
            'Authorization': `Bearer ${token}`
        }
    });
    
    console.log("📥 Response mentah:", res);
    const result = await res.json();
    console.log("Hasil fetch:", result);

    if (!res.ok || !result.success) {
      throw new Error(result.message || "Barang tidak ditemukan");
    }

    const barang = result.data;
    console.log("Redirect ke form ", barang);

    await this.scanner.stop();

    this.$router.push({
      name: "FormDataPeminjamanAlat",
      query: {
        nama_barang: barang.nama_barang,
        jenis_barang: barang.jenis_barang,
        kode_barang: barang.kode_barang
      }
    });

    this.$emit("close");
  } catch (err) {
    console.error("Scan error:", err.message);
  }
}
  }
};
</script>

<style scoped>
.overlay {
  position: fixed;
  inset: 0;
  backdrop-filter: blur(6px);
  background-color: rgba(0, 0, 0, 0.2);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50;
}
.modal {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>