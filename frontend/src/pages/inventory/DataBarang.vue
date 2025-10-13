<template>
  <div class="lg:col-span-6 card rounded-2xl bg-white p-9 mt-6">
    <h3 class="text-lg font-bold text-slate-900">Tambah Barang</h3>
    <form id="formBarang" class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4" @submit.prevent="handleSubmit">
      <div>
        <label class="block text-sm text-slate-600 mb-1">Nama Barang</label>
        <input
          v-model="form.nama"
          required
          name="nama"
          type="text"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30"
          placeholder="Contoh: Laptop"
        />
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Jenis Barang</label>
        <input
          v-model="form.jenis"
          required
          name="jenis"
          type="text"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30"
          placeholder="Contoh: Elektronik"
        />
      </div>
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
      <div class="sm:col-span-3 flex items-center justify-end gap-2">
        <button
          type="submit"
           class="px-4 py-2 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition-colors"
        >
          Simpan
        </button>
      </div>
    </form>


     <div class="mt-6">
  <h4 class="font-semibold text-slate-900">Daftar Barang</h4>

  <div class="mt-3 overflow-auto rounded-xl border border-slate-300">
    <table class="min-w-full text-sm table-auto border-collapse">
      <!-- Header -->
      <thead class="bg-[var(--primary)]/10 text-[var(--primary)]">
        <tr>
          <th class="text-center px-4 py-2 border-r-2 border-slate-300">No</th>
          <th class="text-center px-4 py-2 border-r-2 border-slate-300">Jenis Barang</th>
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
            <button
              @click="editItem(index)"
              class="px-2 py-1 rounded-md mr-2 bg-yellow-600 text-white hover:bg-red-700 transition-colors"
            >
              Edit
            </button>
            <button
              @click="deleteItem(index)"
              class="px-2 py-1 rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors"
            >
              Hapus
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>


    <!-- Toast -->
    <div
      v-if="showToast"
      class="fixed bottom-4 right-4 z-50 rounded-xl bg-slate-900 text-white px-4 py-3 shadow-lg flex items-center gap-2"
    >
      <svg viewBox="0 0 24 24" class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
      <span class="text-sm">{{ toastMessage }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AddItem',


  
  data() {
    return {
      form: {
        nama: '',
        jenis: '',
        kode: ''
      },
      items: [],
      showToast: false,
      toastMessage: '',
      toastTimer: null,
      isEditing: false,   
      editIndex: null
    };
  },
  methods: {
     handleSubmit() {
    if (this.form.nama && this.form.jenis && this.form.kode) {
      if (this.isEditing) {
        // Update item yang sedang diedit
        this.items.splice(this.editIndex, 1, { ...this.form });
        this.showToastMessage('Barang diperbarui');
      } else {
        // Tambah item baru
        this.items.push({ ...this.form });
        this.showToastMessage('Barang tersimpan');
      }
      this.resetForm();
    }
  },

  editItem(index) {
    this.form = { ...this.items[index] };
    this.isEditing = true;
    this.editIndex = index;
  },

  deleteItem(index) {
    this.items.splice(index, 1);
    this.showToastMessage('Barang dihapus');
    // Kalau sedang mengedit item yang dihapus, reset form
    if (this.isEditing && this.editIndex === index) {
      this.resetForm();
    }
  },

  resetForm() {
    this.form = { nama: '', jenis: '', kode: '' };
    this.isEditing = false;
    this.editIndex = null;
  },

  showToastMessage(message) {
    this.toastMessage = message;
    this.showToast = true;
    if (this.toastTimer) clearTimeout(this.toastTimer);
    this.toastTimer = setTimeout(() => {
      this.showToast = false;
    }, 2200);
  }
}

};
</script>

<style scoped>
:root {
  --primary: #3d6195;
  --primary-dark: #2f4d78;
  --primary-darker: #243c5d;
  --primary-soft: #edf2fb;
}

.card {
  box-shadow: 0 8px 24px rgba(61, 97, 149, 0.08), 0 2px 6px rgba(61, 97, 149, 0.05);
}
</style>