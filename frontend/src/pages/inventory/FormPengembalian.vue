<template>
  <div class="bg-white p-6 rounded-lg shadow-md w-[420px]">
    <h2 class="text-lg font-semibold mb-4">Form Pengembalian</h2>

    <!-- Scanner QR -->
    <QrScanner
      v-if="! form.kode_barang"
      @success="handleScanSuccess"
      @close="handleClose"
    />

    <!-- Form setelah scan sukses -->
    <form v-else @submit.prevent="submitPengembalian">
      <div class="mb-3">
        <label class="block text-sm font-medium">Nama Barang</label>
        <input v-model="form.nama_barang" disabled class="w-full border rounded px-2 py-1 bg-gray-100" />
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Kode Barang</label>
        <input v-model="form. kode_barang" disabled class="w-full border rounded px-2 py-1 bg-gray-100" />
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium">Keterangan (opsional)</label>
        <textarea 
          v-model="form. keterangan" 
          placeholder="Contoh: kondisi baik, ada lecet di sudut" 
          class="w-full border rounded px-2 py-1"
        ></textarea>
      </div>

      <div class="flex justify-between gap-2 mt-4">
        <button 
          type="button" 
          @click="handleClose" 
          :disabled="isLoading"
          class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
        >
          Batal
        </button>
        <button 
          type="submit" 
          :disabled="isLoading"
          class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
        >
          {{ isLoading ? 'Memproses...' : 'Kembalikan' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import QrScanner from "./QrScanner.vue";

const form = ref({
  nama_barang: "",
  kode_barang: "",
  keterangan: ""
});

const isLoading = ref(false);
const emit = defineEmits(["berhasilKembali", "close"]);

const resetForm = () => {
  form.value = { nama_barang: "", kode_barang: "", keterangan: "" };
};

const handleClose = () => {
  resetForm();
  emit("close");
};

const handleScanSuccess = async (payload) => {
  console.log("load QR:", payload);

  const text = String(payload);
  const match = text.match(/Kode Barang:\s*([^\s]+)/i);

  if (! match) {
    console.error("Format QR tidak valid:", payload);
    alert("Format QR tidak valid, tidak ditemukan kode barang.");
    return;
  }

  const kodeBarang = match[1]. trim();
  console.log("Kode Barang hasil parsing:", kodeBarang);

  const token = localStorage.getItem("authToken");
  const res = await fetch(`http://localhost:8000/api/inventory/barang/${kodeBarang}`, {
    headers: { Authorization: `Bearer ${token}` }
  });

  if (! res.ok) {
    const text = await res.text();
    console.error("Barang tidak ditemukan:", res. status, text);
    alert("QR tidak valid atau barang tidak ditemukan.");
    return;
  }

  const data = await res.json();
  console.log("Data barang:", data);

  form.value. nama_barang = data.data.nama_barang;
  form.value.kode_barang = data.data.kode_barang;
};

const submitPengembalian = async () => {
  isLoading.value = true;
  
  try {
    const token = localStorage.getItem("authToken");
    const res = await fetch(`http://localhost:8000/api/inventory/Pengembalian/by-kode`, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        kode_barang: form.value.kode_barang,
        keterangan: form.value.keterangan || null
      })
    });

    if (!res. ok) {
      const errorData = await res.json();
      console.error("Gagal pengembalian:", res.status, errorData);
      alert(`${errorData.message || "Gagal memproses pengembalian"}`);
      return;
    }

    const result = await res.json();
    if (result.success) {
      alert("Pengembalian berhasil!");
      emit("berhasilKembali", result.data);
      resetForm();
      handleClose();
    }
  } catch (error) {
    console.error("Network error:", error);
    alert(" Error jaringan. Coba lagi!");
  } finally {
    isLoading.value = false;
  }
};
</script>