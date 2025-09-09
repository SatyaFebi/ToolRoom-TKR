<template>
   <div class="p-6">
      <DataTable 
         :value="users" 
         paginator 
         :rows="10" 
         :rowsPerPageOptions="[10, 15, 20, 50]" 
         responsiveLayout="stack"
         breakpoint="960px"
         class="shadow-md rounded-lg overflow-hidden border border-gray-200 min-w-[600px]"
      >
         <Column field="name" header="Name"></Column>
         <Column field="email" header="Email"></Column>

         <Column field="role" header="Role" class="capitalize">
            <template #body="slotProps">
               <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-sm">
                  {{ getRoleName(slotProps.data.role_id) }}
               </span>
            </template>
         </Column>

         <Column header="Action">
            <template #body="slotProps">
               <div class="flex gap-2 flex-wrap">
                  <button 
                     class="flex gap-1 px-5 py-1 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition"
                     @click="getUserWhenEdit(slotProps.data)"
                  >
                     <span>Edit</span>
                  </button>
                  <button 
                     class="flex items-center gap-1 px-3 py-1 rounded-lg bg-red-500 text-white hover:bg-red-600 transition"
                     @click="deleteUsers(slotProps.data)"
                  >
                     <span>Hapus</span>
                  </button>
               </div>
            </template>
         </Column>
      </DataTable>

      <Dialog v-model:visible="showEditModal" modal header="Edit User" :style="{ width: '30rem' }">
         <div class="flex flex-col gap-4">
            <div>
               <label class="block mb-1">Name</label>
               <input v-model="editForm.name" class="w-full border p-2 rounded" />
            </div>
            <div>
               <label class="block mb-1">Email</label>
               <input v-model="editForm.email" class="w-full border p-2 rounded" />
            </div>
            
            <div>
               <label class="block mb-1">Role</label>
               <select v-model="editForm.role_id" class="w-full border p-2 rounded">
               <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
               </select>
            </div>
            <div>
               <label class="block mb-1">Password <span class="text-red-600">*</span><span class="text-sm">kosongkan jika tidak ingin diisi</span></label>
               <input v-model="editForm.password" type="password" class="w-full border p-2 rounded" placeholder="Kosongkan jika tidak ingin diisi" />
            </div>
         </div>
         <template #footer>
            <button @click="showEditModal = false" class="px-5 py-2 bg-gray-400 text-white rounded mr-2 cursor-pointer">Batal</button>
            <button @click="editUsers" class="px-5 py-2 bg-blue-600 text-white rounded cursor-pointer">Simpan</button>
         </template>
      </Dialog>

   </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import useAuth from '@/composables/useAuth';
import Swal from 'sweetalert2'
import Dialog from 'primevue/dialog';

const users = ref([])
const roles = ref([])
const error = ref(null)

const showEditModal = ref(false)
const editForm = ref({ id: null, name: '', email: '', role_id: null, password: '' })

const { getUserData, getUserRole, editUser, deleteUser } = useAuth()

const getRoleName = (roleId) => {
   const role = roles.value.find(r => r.id === roleId)
   return role ? role.name : 'Tidak diketahui'
}

const fetchUser = ( async () => {
   users.value = []
   error.value = null
   try {
      const data = await getUserData()
      users.value = data
   } catch (e) {
      Swal.fire({
         icon: 'error',
         title: 'Error',
         text: `Gagal mendapatkan data user:  ${e}`
      })
   }
})

const fetchRole = ( async () => {
   roles.value = []
   try {
      const data = await getUserRole()
      roles.value = data
   } catch (e) {
      Swal.fire({
         icon: 'error',
         title: 'Error',
         text: `Gagal mendapatkan data role:  ${e}`
      })
   }
})

const getUserWhenEdit = (user) => {
   editForm.value = { ...user }
   showEditModal.value = true
}

const editUsers = async () => {
   try {
      const payload = { ...editForm.value }
      if (!payload.password) {
         delete payload.password
      }

      await editUser(editForm.value.id, payload)
      Swal.fire('Sukses', 'User berhasil diedit!', 'success')
      showEditModal.value = false
      fetchUser()
   } catch (err) {
      console.error(err)
      Swal.fire({
         icon: 'error',
         title: 'Gagal mengedit data',
         text: err.response?.data?.message || 'Tolong cek kredensial dan coba lagi',
         zIndex: 2500  // lebih tinggi dari PrimeVue Dialog
      })

   }
}

const deleteUsers = (user) => {
   Swal.fire({
      title: 'Yakin hapus?',
      text: `User ${user.name} akan dihapus permanen`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      confirmButtonColor: '#3085d6',
      cancelButtonText: 'Batal',
      cancelButtonColor: '#d33'
   }).then(async (result) => {
      if (result.isConfirmed) {
         try {
            await deleteUser(user.id)
            Swal.fire('Dihapus!', 'User berhasil dihapus', 'success')
            fetchUser()
         } catch (err) {
            console.error(err)
            Swal.fire('Error', 'Gagal menghapus user!', 'error')
         }
      }
   })
}

onMounted(() => {
   fetchUser(),
   fetchRole()
})
</script>

<style scoped>
/* Mengubah breakpoint agar style aktif hanya di bawah 640px */
@media screen and (max-width: 639px) {
  /* Sembunyikan header tabel asli */
  .p-datatable .p-datatable-thead {
    display: none;
  }

  /* Ubah tampilan body, baris, dan sel tabel */
  .p-datatable .p-datatable-tbody,
  .p-datatable .p-datatable-tbody tr {
    display: block;
  }
  
  .p-datatable .p-datatable-tbody tr {
    border-top: 1px solid #dee2e6;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-radius: 8px;
    overflow: hidden;
  }

  .p-datatable .p-datatable-tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-align: right;
    border: none;
    width: 100% !important;
    padding: 0.75rem 1rem;
  }
  
  .p-datatable .p-datatable-tbody tr td:first-child {
      border-top: none;
  }

  .p-datatable .p-datatable-tbody td::before {
    content: attr(data-label);
    font-weight: bold;
    text-align: left;
    margin-right: 1rem;
  }
  
  .p-datatable .p-datatable-tbody td[data-label="Action"] > div {
    justify-content: flex-end;
  }

  .swal2-container {
      z-index: 3000 !important;
   }
}
</style>