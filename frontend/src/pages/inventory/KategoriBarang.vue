<template>
  <div class="lg:col-span-6 card rounded-2xl bg-white p-9 mt-6">
    <h3 class="text-lg font-bold text-slate-900">Tambah Kategori Barang</h3>

    <!-- Toast/Alert -->
    <div
      v-if="showToast"
      class="mt-4 p-4 rounded-xl"
      :class="toastType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
    >
      {{ toastMessage }}
    </div>

    <form
      id="formBarang"
      class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-6"
      @submit.prevent="handleSubmit"
    >
      <!-- Nama Barang -->
      <div>
        <label class="block text-sm text-slate-600 mb-1">Nama Kategori Barang</label>
        <input
          v-model="form.nama"
          required
          name="nama"
          type="text"
          :disabled="isLoading"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30 disabled:opacity-50"
          placeholder="Contoh: Alat berat"
        />
      </div>

      <!-- Kode Barang -->
      <div>
        <label class="block text-sm text-slate-600 mb-1">Kode Kategori (3 karakter)</label>
        <input
          v-model="form.kode"
          required
          name="kode"
          type="text"
          maxlength="3"
          :disabled="isLoading"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30 disabled:opacity-50"
          placeholder="Contoh: ALB"
        />
      </div>

      <!-- Tombol Simpan -->
      <div class="sm:col-span-2 flex items-center justify-end gap-2">
        <button
          type="submit"
          :disabled="isLoading"
          class="px-4 py-2 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition-colors disabled:opacity-50"
        >
          {{ isLoading ? 'Menyimpan...' : 'Simpan' }}
        </button>
      </div>
    </form>

    <!-- Daftar Barang -->
    <div class="mt-6">
      <h4 class="font-semibold text-slate-900">Daftar Kategori Barang</h4>

      <!-- Loading State -->
      <div v-if="isLoadingData" class="mt-3 text-center py-8 text-slate-500">
        Memuat data...
      </div>

      <!-- Table -->
      <div v-else class="mt-3 overflow-auto rounded-xl border border-slate-300">
        <table class="min-w-full text-sm table-auto border-collapse">
          <!-- Header -->
          <thead class="bg-[var(--primary)]/10 text-[var(--primary)]">
            <tr>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">No</th>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">Kategori Barang</th>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">Kode Kategori</th>
              <th class="text-center px-4 py-2">Aksi</th>
            </tr>
          </thead>

          <!-- Body -->
          <tbody>
            <tr
              v-if="items.length === 0"
              class="border-b-2 border-slate-300"
            >
              <td colspan="4" class="text-center px-4 py-4 text-slate-500">
                Belum ada data kategori barang
              </td>
            </tr>
            <tr
              v-for="(item, index) in items"
              :key="item.id"
              class="border-b-2 border-slate-300"
            >
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ index + 1 }}
              </td>
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ item.nama_kategori_barang }}
              </td>
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ item.kode_kategori }}
              </td>
              <td class="text-center px-4 py-2 text-slate-700">
                <button
                  @click="deleteItem(index)"
                  :disabled="isDeletingId === item.id"
                  class="px-3 py-1 rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors disabled:opacity-50"
                >
                  {{ isDeletingId === item.id ? 'Menghapus...' : 'Hapus' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'KategoriBarang',

  data() {
    return {
      form: {
        nama: '',
        kode: ''
      },
      items: [],
      showToast: false,
      toastMessage: '',
      toastType: 'success',
      toastTimer: null,
      isLoading: false,
      currentPage: 1,
      itemsPerPage: 10
    };
  },

  computed: {
    paginatedItems() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.items.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.items.length / this.itemsPerPage);
    }
  },

  mounted() {
    this.loadKategoriBarang();
  },

  methods: {
    // ========== LOAD DATA ==========
    async loadKategoriBarang() {
      this.isLoading = true;
      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          this.showToastMessage('Anda belum login!', 'error');
          return;
        }

        const response = await axios.get('http://localhost:8000/api/inventory/KategoriBarang', {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json'
          }
        });

        this.items = response.data.data || response.data;
      } catch (error) {
        console.error('Error loading data:', error);
        this.showToastMessage('Gagal memuat data: ' + (error.response?.data?.message || error.message), 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // ========== SUBMIT FORM ==========
    async handleSubmit() {
      if (this.form.nama && this.form.kode) {
        await this.tambahKategori();
      } else {
        this.showToastMessage('Semua field harus diisi!', 'error');
      }
    },

    // ========== TAMBAH KATEGORI ==========
    async tambahKategori() {
      this.isLoading = true;
      try {
        const token = localStorage.getItem('authToken');
        const data = {
          nama_kategori_barang: this.form.nama,
          kode_kategori: this.form.kode.toUpperCase()
        };

        const response = await axios.post(
          'http://localhost:8000/api/inventory/TambahKategoriBarang',
          data,
          {
            headers: {
              Authorization: `Bearer ${token}`,
              'Content-Type': 'application/json',
              Accept: 'application/json'
            }
          }
        );

        this.showToastMessage(response.data.message || 'Kategori berhasil ditambahkan!', 'success');
        this.resetForm();
        await this.loadKategoriBarang();
      } catch (error) {
        console.error('Error adding kategori:', error);
        const errors = error.response?.data?.errors || {};
        let errorMsg = 'Validasi gagal: ';
        if (errors.kode_kategori) {
          errorMsg += errors.kode_kategori[0];
        } else if (errors.nama_kategori_barang) {
          errorMsg += errors.nama_kategori_barang[0];
        } else {
          errorMsg += error.response?.data?.message || 'Gagal menambahkan kategori';
        }
        this.showToastMessage(errorMsg, 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // ========== HAPUS KATEGORI ==========
    async deleteItem(index) {
      if (!confirm('Yakin ingin menghapus kategori ini?')) return;

      this.isLoading = true;
      try {
        const token = localStorage.getItem('authToken');
        const item = this.items[index];

        await axios.post(
          `http://localhost:8000/api/inventory/HapusKategoriBarang/${item.id}`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: 'application/json'
            }
          }
        );

        this.showToastMessage('Kategori berhasil dihapus!', 'success');
        await this.loadKategoriBarang();
      } catch (error) {
        console.error('Error deleting kategori:', error);
        this.showToastMessage('Gagal menghapus kategori: ' + (error.response?.data?.message || error.message), 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // ========== RESET FORM ==========
    resetForm() {
      this.form = { nama: '', kode: '' };
    },

    // ========== TOAST ==========
    showToastMessage(message, type = 'success') {
      this.toastMessage = message;
      this.toastType = type;
      this.showToast = true;

      if (this.toastTimer) clearTimeout(this.toastTimer);

      this.toastTimer = setTimeout(() => {
        this.showToast = false;
      }, 2200);
    }
  }
};
</script>