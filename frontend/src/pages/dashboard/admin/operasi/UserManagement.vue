<template>
   <div class="p-6">
      <DataTable :value="users" paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]" tableStyle="min-width: 50rem" responsiveLayout="scroll">
         <Column field="name" header="Name" style="width: 25%"></Column>
         <Column field="email" header="Email" style="width: 25%"></Column>

         <Column field="role" header="Role" style="width: 25%" class="capitalize">
            <template #body="slotProps">
               {{ getRoleName(slotProps.data.role_id) }}
            </template>
         </Column>

         <Column header="Action" style="width: 20%;">
            <template #body="slotProps">
               <button 
                  class="bg-blue-500 text-white px-4 py-1 rounded mr-2 hover:bg-blue-600"
                  @click="getUserWhenEdit(slotProps.data)"
               >
                  Edit
               </button>
               <button 
                  class="bg-red-500 text-white px-4 py-1 rounded mr-2 hover:bg-red-600"
                  @click="deleteUsers(slotProps.data)"
               >
                  Hapus
               </button>
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
            <button @click="showEditModal = false" class="px-3 py-1 bg-gray-400 text-white rounded mr-2">Batal</button>
            <button @click="editUsers" class="px-3 py-1 bg-blue-500 text-white rounded cursor-pointer">Simpan</button>
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
      Swal.fire('Error', 'Gagal mengedit user', 'error')
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