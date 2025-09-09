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
        timer: 2000,
      })
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Login failed',
        text: err.response?.data?.message || 'Terjadi kesalahan',
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

  const editUser = async (id, payload) => {
  try {
    return await auth.editUser(id, payload)
  } catch (err) {
    let msg = 'Tolong cek data input.'
    if (err.response?.status === 422 && err.response.data.errors) {
      const errors = err.response.data.errors
      // bikin list HTML supaya gampang dibaca
      msg = '<ul style="text-align:left; padding-left: 1rem;">' +
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


  const deleteUser = async (id) => {
    try {
      return await auth.deleteUser(id)
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal mengedit data.',
        text: err.response?.data?.message || 'Tidak dapat menghapus user, mohon coba lagi',
      })
      throw err
    }
  }

  const getUserData = async () => {
    return await auth.getUserData()
  }

  const getUserRole = async () => {
    return await auth.getUserRole()
  }

  return {
    user,
    isLoggedIn,
    login,
    updateProfile,
    editUser,
    deleteUser,
    logout: auth.logout,
    getMe: auth.getMe,
    getUserData,
    getUserRole,
  }
}
