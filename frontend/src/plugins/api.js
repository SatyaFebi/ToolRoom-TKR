import axios from 'axios'
import router from '@/router'
// import { useAuthStore } from '@/stores/auth'

const api = axios.create({
    baseURL: import.meta.env.VITE_APP_API_BASE_URL
})

api.interceptors.request.use(config => {
    // const auth = useAuthStore()
    const token = localStorage.getItem('authToken')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config;
})

api.interceptors.response.use(response => response,
    async error => {
        const originalRequest = error.config

        if (error.response && error.response.status === 401) {
            originalRequest._retry = true
            console.warn("Unauthorized! Redirecting to login...");
            await handleLogout();
            return Promise.reject(error);
            // try {
            //     const token = localStorage.getItem('authToken')
                // const res = await axios.post(`${import.meta.env.VITE_APP_API_BASE_URL.replace(/\/$/, '')}refresh`, {}, {
                //     headers: { Authorization: `Bearer ${token}` }
                // })

            //     const newToken = res.token
            //     if (newToken) {
            //         localStorage.setItem('authToken', newToken)
            //         api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
            //         return api.request(originalRequest)
            //     } else {
            //         localStorage.removeItem('authToken')
            //         return Promise.reject(error)
            //     }
            // } catch (err) {
            //     localStorage.removeItem('authToken')
            //     return Promise.reject(err)
            // }
        }

        return Promise.reject(error)
    }
)

const handleLogout = async() => {
  localStorage.removeItem('authToken')

  delete api.defaults.headers.common["Authorization"]

  router.push({ name: "Login" });
}

export default api
