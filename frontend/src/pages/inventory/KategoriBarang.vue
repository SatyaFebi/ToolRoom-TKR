<template>
  <div class="lg:col-span-6 card rounded-2xl bg-white p-9 mt-6">
    <h3 class="text-lg font-bold text-slate-900">Tambah Kategori Barang</h3>

    <form
      id="formBarang"
      class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-6"
      @submit.prevent="handleSubmit"
    >
      <!-- Nama Barang -->
      <div>
        <label class="block text-sm text-slate-600 mb-1">Kategori Barang</label>
        <input
          v-model="form.nama"
          required
          name="nama"
          type="text"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30"
          placeholder="Contoh: Alat berat"
        />
      </div>

      <!-- Kode Barang -->
      <div>
        <label class="block text-sm text-slate-600 mb-1">Kode Barang</label>
        <input
          v-model="form.kode"
          required
          name="kode"
          type="text"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30"
          placeholder="Contoh: BRG-001"
        />
      </div>

      <!-- Tombol Simpan -->
      <div class="sm:col-span-2 flex items-center justify-end gap-2">
        <button
          type="submit"
          class="px-4 py-2 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition-colors"
        >
          Simpan
        </button>
      </div>
    </form>

    <!-- Daftar Barang -->
     <div class="mt-6">
  <h4 class="font-semibold text-slate-900">Daftar Barang</h4>

  <div class="mt-3 overflow-auto rounded-xl border border-slate-300">
    <table class="min-w-full text-sm table-auto border-collapse">
      <!-- Header -->
      <thead class="bg-[var(--primary)]/10 text-[var(--primary)]">
        <tr>
          <th class="text-center px-4 py-2 border-r-2 border-slate-300">No</th>
          <th class="text-center px-4 py-2 border-r-2 border-slate-300">Kategori Barang</th>
          <th class="text-center px-4 py-2 border-r-2 border-slate-300">Kode Barang</th>
          <th class="text-center px-4 py-2">Aksi</th>
        </tr>
      </thead>

      <!-- Body -->
      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="index"
          class="border-b-2 border-slate-300"
        >
          <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
            {{ index + 1 }}
          </td>
          <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
            {{ item.nama }}
          </td>
          <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
            {{ item.kode }}
          </td>
          <td class="text-center px-4 py-2 text-slate-700">
            <!-- <button
              @click="editItem(index)"
              class="text-blue-600 hover:underline mr-3"
            >
              Edit
            </button> -->
            <button
              @click="deleteItem(index)"
              class="px-3 py-1 rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors"
            >
              Hapus
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  nama: '',
  kode: '',
  jenis: ''
})

const items = ref([])
const showToast = ref(false)
const toastMessage = ref('')



const deleteItem = (index) => {
  items.value.splice(index, 1)
}


const handleSubmit = () => {
  if (!form.value.nama || !form.value.kode) return

  items.value.push({
    nama: form.value.nama,
    kode: form.value.kode,
    jenis: form.value.jenis || '—'
  })

  toastMessage.value = 'Barang berhasil ditambahkan!'
  showToast.value = true

  setTimeout(() => {
    showToast.value = false
  }, 3000)

  form.value = { nama: '', kode: '', jenis: '' }
}
</script>