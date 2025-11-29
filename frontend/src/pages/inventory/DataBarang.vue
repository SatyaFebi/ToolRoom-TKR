<template>
  <div class="lg:col-span-6 card rounded-2xl bg-white p-9 mt-6">
    <h3 class="text-lg font-bold text-slate-900">Tambah Barang</h3>
    <div
      v-if="showToast"
      class="mt-4 p-4 rounded-xl"
      :class="toastType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
    >
      <span class="text-sm">{{ toastMessage }}</span>
    </div>
    
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
        <label class="block text-sm text-slate-600 mb-1">Kategori Barang</label>
        <select
          v-model="selectedKategori"
          @change="updateKodeFromKategori"
          :disabled="isLoading"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30 disabled:opacity-50"
        >
          <option disabled value="">Pilih Kategori Barang</option>
          <option v-for="item in itemsKategori" :key="item.id" :value="item">
            {{ item.nama_kategori_barang }}
          </option>
        </select>
      </div>
      
      <div>
        <label class="block text-sm text-slate-600 mb-1">Kode Barang</label>
        <input
          v-model="form.kode"
          :readonly="true"
          required
          name="kode"
          type="text"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30"
          placeholder="Contoh: BRG-001"
        />
      </div>
      
      <div>
        <label class="block text-sm text-slate-600 mb-1">Status Barang</label>
        <select
          v-model="form.status"
          required
          name="status"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30"
        >
          <option value="tersedia">tersedia</option>
          <option value="dipinjam">dipinjam</option>
        </select>
      </div>
      
      <div>
        <label class="block text-sm text-slate-600 mb-1">Lokasi Penyimpanan</label>
        <select
          v-model="form.lokasi"
          required
          name="lokasi"
          class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/30"
        >
          <option value="ruang toolman">Ruang Toolman</option>
          <option value="ruang ttep">Ruang TTEP</option>
          <option value="ruang bengkel tefa">Ruang Bengkel TEFA</option>
        </select>
      </div>
      
      <div class="sm:col-span-3 flex items-center justify-end gap-2">
        <button
          type="submit"
          class="px-6 py-2 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition-colors"
        >
          Simpan
        </button>
      </div>
    </form>

    <div class="mt-6">
      <h4 class="font-semibold text-slate-900">Daftar Barang</h4>

      <!-- button download excel -->
      <div class="relative w-full flex justify-end mt-2">
        <button 
          id="dowloadBtn" 
          class="w-full md:w-auto bg-gradient-to-r from-green-400 to-green-600 hover:from-green-700 hover:to-green-700 text-white px-4 py-2 rounded-xl font-semibold transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-2 text-sm"
        >
          Download file excel
        </button>

        <div id="downloadMenu" class="hidden absolute top-full right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
          <div class="py-2">
            <!-- download excel biasa -->
            <div class="download-option px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors duration-150 flex items-center gap-3" data-type="excel">
              <div>
                <div class="font-semibold text-sm">Download file excel biasa</div>
              </div>
            </div>

            <!-- download excel yang ada QR nya -->
            <div class="download-option px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors duration-150 flex items-center gap-3" data-type="excel-qr">
              <div>
                <div class="font-semibold text-sm">Download file excel dengan QR Code</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-3 overflow-auto rounded-xl border border-slate-300">
        <table class="min-w-full text-sm table-auto border-collapse">
          <!-- Header -->
          <thead class="bg-[var(--primary)]/10 text-[var(--primary)]">
            <tr>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">No</th>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">Nama barang</th>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">Jenis Barang</th>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">Kode Barang</th>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">Status</th>
              <th class="text-center px-4 py-2 border-r-2 border-slate-300">Lokasi</th>
              <th class="text-center px-4 py-2">Aksi</th>
            </tr>
          </thead>

          <!-- Body -->
          <tbody>
            <tr
              v-for="(item, index) in paginatedItems"
              :key="item.id"
              class="border-b-2 border-slate-300"
            >
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
              </td>
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ item.nama_barang }}
              </td>
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ item.jenis_barang }}
              </td>
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ item.kode_barang }}
              </td>
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ item.status_barang }}
              </td>
              <td class="text-center px-4 py-2 border-r-2 border-slate-200 text-slate-700">
                {{ item.lokasi_penyimpanan }}
              </td>
              <td class="text-center px-4 py-2 text-slate-700">
                <button
                  @click="editItem(item)"
                  class="px-2 py-1 rounded-md mr-2 bg-yellow-600 text-white hover:bg-yellow-700 transition-colors"
                >
                  Edit
                </button>
                <button
                  @click="deleteItem(item.id)"
                  class="px-2 py-1 rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors"
                >
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="mt-4 flex justify-center gap-2">
        <button
          @click="currentPage--"
          :disabled="currentPage === 1"
          class="px-3 py-1 rounded bg-slate-200 hover:bg-slate-300 disabled:opacity-50"
        >
          ←
        </button>

        <span class="px-3 py-1 text-slate-700">Halaman {{ currentPage }} dari {{ totalPages }}</span>

        <button
          @click="currentPage++"
          :disabled="currentPage === totalPages"
          class="px-3 py-1 rounded bg-slate-200 hover:bg-slate-300 disabled:opacity-50"
        >
          →
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AddItem',

  data() {
    return {
      form: {
        nama: '',
        jenis: '',
        kode: '',
        status: '',
        lokasi: ''
      },
      itemsBarang: [],
      itemsKategori: [],
      selectedKategori: null,

      showToast: false,
      toastMessage: '',
      toastType: 'success',
      toastTimer: null,

      isEditing: false,
      editId: null,

      isLoading: false,
      currentPage: 1,
      itemsPerPage: 10
    };
  },

  computed: {
    paginatedItems() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.itemsBarang.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.itemsBarang.length / this.itemsPerPage);
    }
  },

  mounted() {
    this.loadDataBarang();
    this.loadKategoriBarang();
    this.setupDownloadMenu();
  },

  methods: {
    //export excel
    async exportExcel(withQR) {
      try {
        const token = localStorage.getItem('authToken');
        const response = await axios.get(
          `http://localhost:8000/api/inventory/export?with_qr=${withQR}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            },
            responseType: 'blob'
          }
        );

        const blob = new Blob([response.data], {
          type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = withQR ? 'DataBarang_dengan_QR.xlsx' : 'DataBarang.xlsx';
        link.click();

        this.showToastMessage('File berhasil diunduh!', 'success');
      } catch (error) {
        console.error('Gagal export:', error);
        this.showToastMessage('Gagal mengunduh file', 'error');
      }
    },

    // dropdown load menu
    setupDownloadMenu() {
      this.$nextTick(() => {
        const downloadBtn = document.getElementById('dowloadBtn');
        const downloadMenu = document.getElementById('downloadMenu');

        if (!downloadBtn || !downloadMenu) return;

        downloadBtn.addEventListener('click', () => {
          downloadMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
          if (!downloadBtn.contains(e.target) && !downloadMenu.contains(e.target)) {
            downloadMenu.classList.add('hidden');
          }
        });

        document.querySelectorAll('.download-option').forEach((option) => {
          option.addEventListener('click', () => {
            const type = option.getAttribute('data-type');
            downloadMenu.classList.add('hidden');

            if (type === 'excel') {
              this.exportExcel(false);
            } else if (type === 'excel-qr') {
              this.exportExcel(true);
            }
          });
        });
      });
    },

    // kategori barang
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

        this.itemsKategori = response.data.data || response.data;
      } catch (error) {
        console.error('Error loading kategori:', error);
        this.showToastMessage('Gagal memuat kategori: ' + (error.response?.data?.message || error.message), 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // kode barang
    updateKodeFromKategori() {
      if (this.selectedKategori) {
        this.form.kode = this.selectedKategori.kode_kategori;
        this.form.jenis = this.selectedKategori.nama_kategori_barang;
      } else {
        this.form.kode = '';
      }
    },

    // data barang
    async loadDataBarang() {
      this.isLoading = true;
      try {
        const token = localStorage.getItem('authToken');
        if (!token) {
          this.showToastMessage('Anda belum login!', 'error');
          return;
        }

        const response = await axios.get('http://localhost:8000/api/inventory/DataBarang', {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json'
          }
        });

        this.itemsBarang = response.data.data || response.data;
      } catch (error) {
        console.error('Error loading data:', error);
        this.showToastMessage('Gagal memuat data: ' + (error.response?.data?.message || error.message), 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // tombol submit
    async handleSubmit() {
      if (this.form.nama && this.form.jenis && this.form.kode) {
        if (this.isEditing) {
          await this.updateBarang();
        } else {
          await this.tambahBarang();
        }
      } else {
        this.showToastMessage('Semua field harus diisi!', 'error');
      }
    },

    // tamabh data barang
    async tambahBarang() {
      this.isLoading = true;
      try {
        const token = localStorage.getItem('authToken');

        const data = {
          nama_barang: this.form.nama,
          jenis_barang: this.form.jenis,
          kode_barang: this.form.kode,
          kategori_barang_id: this.selectedKategori?.id || null,
          status_barang: this.form.status,
          lokasi_penyimpanan: this.form.lokasi
        };

        await axios.post(
          'http://localhost:8000/api/inventory/TambahDataBarang',
          data,
          {
            headers: {
              Authorization: `Bearer ${token}`,
              'Content-Type': 'application/json',
              Accept: 'application/json'
            }
          }
        );

        this.showToastMessage('Barang berhasil ditambahkan!', 'success');
        this.resetForm();
        await this.loadDataBarang();
      } catch (error) {
        console.error('Error adding data:', error);
        this.showToastMessage('Gagal menambahkan data: ' + (error.response?.data?.message || error.message), 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // update barang
    async updateBarang() {
      this.isLoading = true;
      try {
        const token = localStorage.getItem('authToken');

        const data = {
          nama_barang: this.form.nama,
          jenis_barang: this.form.jenis,
          kode_barang: this.form.kode,
          kategori_barang_id: this.selectedKategori?.id || null,
          status_barang: this.form.status,
          lokasi_penyimpanan: this.form.lokasi
        };

        await axios.post(
          `http://localhost:8000/api/inventory/EditDataBarang/${this.editId}`,
          data,
          {
            headers: {
              Authorization: `Bearer ${token}`,
              'Content-Type': 'application/json',
              Accept: 'application/json'
            }
          }
        );

        this.showToastMessage('Barang berhasil diperbarui!', 'success');
        this.resetForm();
        await this.loadDataBarang();
      } catch (error) {
        console.error('Error updating data:', error);
        this.showToastMessage('Gagal memperbarui data: ' + (error.response?.data?.message || error.message), 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // edit data 
    editItem(item) {
      this.form = {
        nama: item.nama_barang,
        jenis: item.jenis_barang,
        kode: item.kode_barang,
        status: item.status_barang,
        lokasi: item.lokasi_penyimpanan
      };
      
      // Set kategori yang sesuai
      this.selectedKategori = this.itemsKategori.find(
        k => k.id === item.kategori_barang_id
      ) || null;
      
      this.isEditing = true;
      this.editId = item.id;
    },

    // hapus data
    async deleteItem(id) {
      if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;

      this.isLoading = true;
      try {
        const token = localStorage.getItem('authToken');

        await axios.post(
          `http://localhost:8000/api/inventory/HapusDataBarang/${id}`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: 'application/json'
            }
          }
        );

        this.showToastMessage('Barang berhasil dihapus!', 'success');

        if (this.isEditing && this.editId === id) {
          this.resetForm();
        }

        await this.loadDataBarang();
      } catch (error) {
        console.error('Error deleting data:', error);
        this.showToastMessage('Gagal menghapus data: ' + (error.response?.data?.message || error.message), 'error');
      } finally {
        this.isLoading = false;
      }
    },

    // reset form
    resetForm() {
      this.form = { nama: '', jenis: '', kode: '', status: '', lokasi: '' };
      this.selectedKategori = null;
      this.isEditing = false;
      this.editId = null;
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