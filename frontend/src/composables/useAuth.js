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
  
  const logout = async () => {
    try {
      await auth.logout()
      Swal.fire({
        icon: 'success',
        title: 'Logout sukses!',
        showConfirmButton: false,
        timer: 2000
      })
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Logout failed',
        text: err.response?.data?.message || 'Terjadi kesalahan'
      })
      throw err
    }
  }

  const fetchUser = async () => {
    await auth.fetchUser()
  }

  return {
    user,
    isLoggedIn,
    login,
    logout,
    fetchUser
  }
}
