import { useAuthStore } from '../stores/auth'
import { computed } from 'vue'
import Swal from 'sweetalert2'

export default function useAuth() {
  const auth = useAuthStore()

  const user = computed(() => auth.user)
  const isLoggedIn = computed(() => !!auth.token)

  const login = async (email, password) => {
    try {
      await auth.login(email, password)
      // await auth.getMe()
      Swal.fire({
        icon: 'success',
        title: 'Login sukses!',
        showConfirmButton: false,
        timer: 2000
      })
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Login failed',
        text: err.response?.data?.message || 'Terjadi kesalahan'
      })
      throw err
    }
  }

  const updateProfile = async (data) => {
    try {
      await auth.update(data)
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Profil berhasil diperbarui!'
      })
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal mengupdate data.',
        text: err.response?.data?.message || 'Tolong cek kredensial dan coba lagi'
      })
      throw err
    }
  }

  const getUserData = async () => {
    await auth.getUserData()
  }

  return {
    user,
    isLoggedIn,
    login,
    updateProfile,
    logout: auth.logout,
    getMe: auth.getMe,
    getUserData
  }
}
