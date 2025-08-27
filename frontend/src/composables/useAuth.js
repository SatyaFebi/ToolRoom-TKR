import { useAuthStore } from '../stores/auth'
import { computed } from 'vue'

export default function useAuth() {
  const auth = useAuthStore()

  const user = computed(() => auth.user)
  const isLoggedIn = computed(() => !!auth.token)

  const login = async (email, password) => {
    await auth.login(email, password)
  }

  const logout = async () => {
    await auth.logout()
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
