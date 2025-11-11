import axios from 'axios'
import router from '@/router'

const api = axios.create({
  baseURL: import.meta.env.VITE_APP_API_BASE_URL,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
})

// 🔹 Interceptor Request
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

// 🔹 Interceptor Response
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config

    // Jika token expired / unauthorized
    if (error.response && error.response.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      console.warn('⚠️ Token invalid atau expired. Logout otomatis...')
      await handleLogout()
      return Promise.reject(error)
    }

    return Promise.reject(error)
  }
)

// 🔹 Logout Handler
const handleLogout = async () => {
  try {
    localStorage.removeItem('auth_token')
    delete api.defaults.headers.common['Authorization']
    await router.push({ name: 'Login' })
  } catch (err) {
    console.error('Logout gagal:', err)
  }
}

export default api
