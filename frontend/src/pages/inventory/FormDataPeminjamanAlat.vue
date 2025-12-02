<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-md p-6">
      <h2 class="text-lg font-semibold mb-6 text-center text-gray-800">
        Data Peminjaman Alat
      </h2>

      <form @submit.prevent="pinjamBarang" class="space-y-4">
        <!-- Nama Barang -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
          <input
            v-model="form.nama_barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700"
            readonly
          />
        </div>

        <!-- Kategori Barang -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Barang</label>
          <input
            v-model="form.jenis_barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700"
            readonly
          />
        </div>

        <!-- Kode Barang -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
          <input
            v-model="form.kode_barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700"
            readonly
          />
        </div>

        <!-- Tombol Pinjam -->
        <button
          type="submit"
          class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium"
        >
          Pinjam Barang
        </button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "FormDataPeminjamanAlat",
  data() {
    return {
      form: {
        nama_barang: "",
        jenis_barang: "",
        kode_barang: ""
      }
    };
  },
  created() {
    // Ambil query params dari router (hasil redirect dari scanner)
    const q = this.$route.query;
    this.form.nama_barang = q.nama_barang || "";
    this.form.jenis_barang = q.jenis_barang || "";
    this.form.kode_barang = q.kode_barang || "";
  },
  methods: {
    async pinjamBarang() {
      const token = localStorage.getItem('authToken');
      try {
        const res = await fetch("http://localhost:8000/api/inventory/Peminjaman", {
          method: "POST",
          headers:{
            "Content-Type": "application/json",
            "Authorization": `Bearer ${token}`
          },
          body: JSON.stringify({
            kode_barang: this.form.kode_barang,
            tanggal_peminjaman: new Date().toISOString().slice(0, 10)
          }) 
        });

        const data = await res.json();
        if (!res.ok || !data.success) {
          throw new Error(data.message || "Gagal meminjam barang");
        }

        alert("Barang berhasil dipinjam!");
        // Redirect balik ke daftar barang
        this.$router.push({ name: "PeminjamanBarang" });
      } catch (err) {
        alert(err.message);
        console.error("Pinjam error:", err.message);
      }
    }
  }
};
</script>