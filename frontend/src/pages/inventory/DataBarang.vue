<template>
  <div class="lg:col-span-6 card rounded-2xl bg-white p-9 mt-6">
    <h3 class="text-lg font-bold text-slate-900 mb-4">Tambah Barang</h3>

    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm text-slate-600 mb-1">Nama Barang</label>
        <input
          v-model="form.nama"
          required
          type="text"
          class="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
          placeholder="Contoh: Laptop"
        />
      </div>

      <div>
        <label class="block text-sm text-slate-600 mb-1">Jenis Barang</label>
        <input
          v-model="form.jenis"
          required
          type="text"
          class="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
          placeholder="Contoh: Elektronik"
        />
      </div>

      <div>
        <label class="block text-sm text-slate-600 mb-1">Kode Barang</label>
        <input
          v-model="form.kode"
          required
          type="text"
          class="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
          placeholder="Contoh: BRG-001"
        />
      </div>

      <div class="sm:col-span-2 flex justify-end">
        <button
          type="submit"
          class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
          Simpan
        </button>
      </div>
    </form>

    <div class="mt-8">
      <h4 class="font-semibold text-slate-900 mb-2">Daftar Barang</h4>
      <div class="overflow-auto rounded-xl border border-slate-300">
        <table class="min-w-full text-sm">
          <thead class="bg-blue-50 text-blue-700">
            <tr>
              <th class="px-4 py-2 border">No</th>
              <th class="px-4 py-2 border">Nama Barang</th>
              <th class="px-4 py-2 border">Jenis Barang</th>
              <th class="px-4 py-2 border">Kode</th>
              <th class="px-4 py-2 border">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="index" class="border">
              <td class="text-center px-4 py-2">{{ index + 1 }}</td>
              <td class="px-4 py-2">{{ item.nama }}</td>
              <td class="px-4 py-2">{{ item.jenis }}</td>
              <td class="text-center px-4 py-2">{{ item.kode }}</td>
              <td class="text-center px-4 py-2">
                <button
                  @click="deleteItem(index)"
                  class="px-3 py-1 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="items.length === 0">
              <td colspan="5" class="text-center text-slate-500 py-3">Belum ada data</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <transition name="fade">
      <div
        v-if="showToast"
        class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
        {{ toastMessage }}
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({ nama: '', jenis: '', kode: '' })
const items = ref([])
const showToast = ref(false)
const toastMessage = ref('')

const handleSubmit = () => {
  items.value.push({ ...form.value })
  toastMessage.value = 'Barang berhasil ditambahkan!'
  showToast.value = true
  setTimeout(() => (showToast.value = false), 2000)
  form.value = { nama: '', jenis: '', kode: '' }
}

const deleteItem = (index) => {
  items.value.splice(index, 1)
}
</script>

<style>
.fade-enter-active,
.fade-leave-active { transition: opacity 0.5s; }
.fade-enter-from,
.fade-leave-to { opacity: 0; }
</style>
