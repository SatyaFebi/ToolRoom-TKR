<template>
   <div class="p-6">
      <div class="flex justify-end mb-4">
         <button 
            class="bg-green-600 py-2 px-5 rounded-lg text-white font-semibold hover:bg-green-700 transition"
            @click="showRegisterModal = true"
         >
            Tambah User
         </button>
      </div>

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

      <!-- Register Modal -->
      <Transition name="fade-scale">
         <Dialog 
            v-model:visible="showRegisterModal" 
            modal 
            header="Tambah User Baru" 
            :style="{ width: '30rem' }"
         >
            <div class="flex flex-col gap-4">
               <div>
                  <label class="block mb-1">Name</label>
                  <input v-model="form.name" class="w-full border p-2 rounded" />
               </div>
               <div>
                  <label class="block mb-1">Email</label>
                  <input v-model="form.email" class="w-full border p-2 rounded" />
               </div>
               <div>
                  <label class="block mb-1">Role</label>
                  <select v-model="form.role_id" class="w-full border p-2 rounded">
                     <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                  </select>
               </div>
               <div>
                  <label class="block mb-1">Password</label>
                  <input v-model="form.password" type="password" autocomplete="new-password" class="w-full border p-2 rounded" />
               </div>
            </div>
            <template #footer>
               <button 
                  @click="showRegisterModal = false" 
                  class="px-5 py-2 bg-gray-400 text-white rounded mr-2 cursor-pointer"
               >
                  Batal
               </button>
               <button 
                  @click="doRegister" 
                  class="px-5 py-2 bg-green-600 text-white rounded cursor-pointer hover:bg-green-700 transition"
               >
                  Simpan
               </button>
            </template>
         </Dialog>
      </Transition>

      <!-- Edit Modal -->
      <Dialog 
         v-model:visible="showEditModal" 
         modal 
         header="Edit User" 
         :style="{ width: '30rem' }"
      >
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
               <label class="block mb-1">Password <span class="text-red-600">*</span>
                  <span class="text-sm">kosongkan jika tidak ingin diisi</span>
               </label>
               <input 
                  v-model="editForm.password" 
                  type="password" 
                  class="w-full border p-2 rounded" 
                  placeholder="Kosongkan jika tidak ingin diisi" 
               />
            </div>
         </div>
         <template #footer>
            <button 
               @click="showEditModal = false" 
               class="px-5 py-2 bg-gray-400 text-white rounded mr-2 cursor-pointer"
            >
               Batal
            </button>
            <button 
               @click="editUsers" 
               class="px-5 py-2 bg-blue-600 text-white rounded cursor-pointer hover:bg-blue-700 transition"
            >
               Simpan
            </button>
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

const form = ref({ name: '', email: '', role_id: null, password: '' })
const editForm = ref({ id: null, name: '', email: '', role_id: null, password: '' })

const showEditModal = ref(false)
const showRegisterModal = ref(false)

const { getUserData, getUserRole, editUser, deleteUser, register } = useAuth()

const doRegister = async () => {
   try {
      await register(form.value)
      Swal.fire('Sukses', 'User berhasil ditambahkan!', 'success')
      showRegisterModal.value = false
      form.value = { name: '', email: '', role_id: null, password: '' } // reset
      fetchUser()
   } catch (e) {
      console.error(e)
      Swal.fire('Error', 'Gagal menambahkan user!', 'error')
   }
}

const getRoleName = (roleId) => {
   const role = roles.value.find(r => r.id === roleId)
   return role ? role.name : 'Tidak diketahui'
}

const fetchUser = async () => {
   try {
      users.value = await getUserData()
   } catch (e) {
      Swal.fire('Error', `Gagal mendapatkan data user: ${e}`, 'error')
   }
}

const fetchRole = async () => {
   try {
      roles.value = await getUserRole()
   } catch (e) {
      Swal.fire('Error', `Gagal mendapatkan data role: ${e}`, 'error')
   }
}

const getUserWhenEdit = (user) => {
   editForm.value = { ...user }
   showEditModal.value = true
}

const editUsers = async () => {
   try {
      const payload = { ...editForm.value }
      if (!payload.password) delete payload.password
      await editUser(editForm.value.id, payload)
      Swal.fire('Sukses', 'User berhasil diedit!', 'success')
      showEditModal.value = false
      fetchUser()
   } catch (err) {
      console.error(err)
      Swal.fire('Error', 'Gagal mengedit data!', 'error')
   }
}

const deleteUsers = (user) => {
   Swal.fire({
      title: 'Yakin hapus?',
      text: `User ${user.name} akan dihapus permanen`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
   }).then(async (result) => {
      if (result.isConfirmed) {
         try {
            await deleteUser(user.id)
            Swal.fire('Dihapus!', 'User berhasil dihapus', 'success')
            fetchUser()
         } catch (err) {
            Swal.fire('Error', 'Gagal menghapus user!', 'error')
            throw err
         }
      }
   })
}

onMounted(() => {
   fetchUser()
   fetchRole()
})
</script>

<style scoped>
/* Animasi modal masuk/keluar */
.fade-scale-enter-active, .fade-scale-leave-active {
  transition: all 0.3s ease;
}
.fade-scale-enter-from {
  opacity: 0;
  transform: scale(0.9);
}
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
</style>
