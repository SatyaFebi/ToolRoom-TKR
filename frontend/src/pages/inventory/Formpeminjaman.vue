<template>
    <body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

  <main class="w-full max-w-2xl mt-">
    <!-- Card / Box -->
    <section class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
      <!-- Header -->
      <div class="px-6 py-5 md:px-8 md:py-6 border-b border-gray-100">
        <h1 class="text-lg md:text-xl font-semibold text-gray-800">Data Peminjaman Alat</h1>
      </div>

      <!-- Form body -->
      <form class="p-6 md:p-8" action="#" method="POST" novalidate>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Nama -->
          <label class="block">
            <span class="text-sm font-medium text-gray-700">Nama Peminjam</span>
            <input v-model="Nama"
              type="text"
              name="name"
              required
              placeholder="contoh: Ahmad"
              class="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm placeholder-gray-400
                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            />
          </label>

          <!-- kelas -->
          <label class="block">
            <span class="text-sm font-medium text-gray-700">Kelas</span>
            <select v-model="Kelas"
              name="role"
              class="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm
                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            >
              <option value="">Pilih kelas</option>
              <option>X TKR 1</option>
              <option>X TKR 2</option>
              <option>X TKR 3</option>
            </select>
          </label>


          <!-- barang yang di pinjam -->
            <label class="block">
            <span class="text-sm font-medium text-gray-700">Barang yang di pinjam</span>
            <input v-model="Barang"
              type="text"
              name="name"
              required
              placeholder="contoh: tang potong"
              class="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm placeholder-gray-400
                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            />
          </label>

          <!-- Kategori barang -->
          <label class="block">
            <span class="text-sm font-medium text-gray-700">kategori barang</span>
            <select v-model="Kategori"
              name="kategori"
              class="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm
                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            >
              <option value="">Kategori</option>
              <option>Alat berat</option>
              <option>Alat Ringan</option>  
            </select>
          </label>

          <!-- tanggal peminjaman -->
          <label class="block">
            <span class="text-sm font-medium text-gray-700">Tanggal meminjam</span>
            <input v-model="Tanggal"
              type="date"
              name="date"
              placeholder="+62 812 3456 7890"
              class="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm placeholder-gray-400
                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            />
          </label>

          <!-- masukan foto -->
             <label class="block">
            <span class="text-sm font-medium text-gray-700">Tanggal meminjam</span>
            <input
              type="file"
              accept="image/*"
              name="foto"
              placeholder="+62 812 3456 7890"
              class="mt-1 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm placeholder-gray-400
                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
             @change="handleFoto"/>
          </label>

          <div
  class="mt-1 flex items-center border border-gray-300 rounded-lg bg-white overflow-hidden focus-within:ring-2 focus-within:ring-blue-400 w-40"
>
  <!-- Tombol - -->
  <button
    type="button"
    @click="kurangi"
    class="flex items-center justify-center w-8 h-8 bg-gray-100 hover:text-black transition"
  >
    −
  </button>

  <!-- Input Angka -->
  <input
    type="number"
    v-model.number="Jumlah"
    min="0"
    class="w-full text-center outline-none text-gray-800 text-sm bg-transparent appearance-none"
  />

  <!-- Tombol + -->
  <button
    type="button"
    @click="tambah"
    class="flex items-center justify-center w-8 h-8 bg-gray-100 hover:text-black transition"
  >
    +
  </button>
</div>

        </div>
        
        
        <!-- Action buttons -->
        <div class="mt-6 flex items-center justify-between gap-3">
          <button
           @click="tambahBarang"
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm
                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
           >
            Tambah
          </button>
        </div>
      </form>
    </section>
  </main>

</body>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePeminjamanStore } from '@/stores/peminjamanStore'

const router = useRouter()
const store = usePeminjamanStore()

const Nama = ref('')
const Kelas = ref('')
const Barang = ref('')
const Kategori = ref('')
const Tanggal = ref('')
const foto = ref(null)
const Jumlah = ref('0')

function tambahBarang() {
  // Simpan data ke store global
  store.tambahBarang({
    nama: Nama.value,
    kelas: Kelas.value,
    barang: Barang.value,
    kategori: Kategori.value,
    tanggal: Tanggal.value,
    jumlah: Jumlah.value,
    foto: foto.value
  })

  // Reset form
  Nama.value = ''
  Kelas.value = ''
  Barang.value = ''
  Kategori.value = ''
  Tanggal.value = ''
  Jumlah.value = 0
  foto.value = null

  // Redirect ke halaman daftar barang
  router.push({ name: 'PeminjamanBarang' })
}


function handleFoto(e) {
  foto.value = e.target.files[0]
}


function tambah() {
  Jumlah.value++
}

function kurangi() {
  if (Jumlah.value > 0) Jumlah.value--
}
</script>