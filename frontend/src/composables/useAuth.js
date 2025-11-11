import { useAuthStore } from '../stores/auth'
import { computed } from 'vue'
import Swal from 'sweetalert2'
import router from '@/router' // ✅ tambahkan ini biar bisa push setelah login

export default function useAuth() {
  const auth = useAuthStore()

  const user = computed(() => auth.user)
  const isLoggedIn = computed(() => !!auth.token)

  // ===================== REGISTER =====================
  const register = async (payload) => {
    try {
      const data = await auth.register(payload)
      Swal.fire({
        icon: 'success',
        title: 'Sukses menambah user baru',
        showConfirmButton: false,
        timer: 2000,
      })
      return data
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal menambah user baru',
        text: err.response?.data?.message || 'Terjadi kesalahan',
      })
      throw err
    }
  }

  // ===================== LOGIN =====================
  const login = async (email, password) => {
    try {
      const response = await auth.login(email, password)

      // ✅ pastikan token tersimpan
      const token = response?.data?.token
      if (token) {
        localStorage.setItem('auth_token', token)
        auth.token = token
        console.log('Token disimpan:', localStorage.getItem('auth_token'))
      }

      Swal.fire({
        icon: 'success',
        title: 'Login sukses!',
        showConfirmButton: false,
        timer: 1500,
      })

      // ✅ tunggu SweetAlert selesai lalu arahkan ke dashboard
      setTimeout(() => {
        router.push('/dashboard/admin')
      }, 1500)

      return response
    } catch (err) {
      const errorMessage = err.response?.data?.message || 'Terjadi kesalahan'
      Swal.fire({
        icon: 'error',
        title: 'Login gagal',
        text: errorMessage,
      })
      throw err
    }
  }

  // ===================== UPDATE PROFILE =====================
  const updateProfile = async (data) => {
    try {
      await auth.update(data)
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Profil berhasil diperbarui!',
      })
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal mengupdate data.',
        text: err.response?.data?.message || 'Tolong cek kredensial dan coba lagi',
      })
      throw err
    }
  }

  // EDIT USER 
  const editUser = async (id, payload) => {
    try {
      return await auth.editUser(id, payload)
    } catch (err) {
      let msg = 'Tolong cek data input.'
      if (err.response?.status === 422 && err.response.data.errors) {
        const errors = err.response.data.errors
        msg =
          '<ul style="text-align:left; padding-left: 1rem;">' +
          Object.values(errors).flat().map(e => `<li>${e}</li>`).join('') +
          '</ul>'
      } else if (err.response?.data?.message) {
        msg = err.response.data.message
      }

      Swal.fire({
        icon: 'error',
        title: 'Validasi gagal',
        html: msg,
        showConfirmButton: true
      })
      throw err
    }
  }

  // ===================== DELETE USER =====================
  const deleteUser = async (id) => {
    try {
      return await auth.deleteUser(id)
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal menghapus data.',
        text: err.response?.data?.message || 'Tidak dapat menghapus user, mohon coba lagi',
      })
      throw err
    }
  }

  // ===================== LOGOUT =====================
  const logout = async () => {
    auth.logout()
    localStorage.removeItem('auth_token')
    Swal.fire({
      icon: 'success',
      title: 'Logout berhasil!',
      timer: 1500,
      showConfirmButton: false,
    })
    router.push({ name: 'Login' })
  }

  // ===================== GET DATA =====================
  const getUserData = async () => await auth.getUserData()
  const getUserRole = async () => await auth.getUserRole()

  return {
    user,
    isLoggedIn,
    register,
    login,
    updateProfile,
    editUser,
    deleteUser,
    logout,
    getMe: auth.getMe,
    getUserData,
    getUserRole,
  }
}
