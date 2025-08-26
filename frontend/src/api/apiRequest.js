import axios from 'axios'
import router from '@/router'
import { ref } from 'vue'
import Swal from 'sweetalert2'

const api = axios.create({
    baseURL: import.meta.env.VITE_APP_API_BASE_URL,
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
    },
})

// Interceptors untuk Request
api.interceptors.request.use(
    async (config) => {
        let token = localStorage.getItem('authToken');
        let expiry = parseInt(localStorage.getItem('expired_authToken')) || 0;
        let currentTime = Math.floor(Date.now() / 1000); // Waktu sekarang dalam detik

        if (token && expiry && currentTime >= parseInt(expiry)) {
            localStorage.removeItem('authToken')
            localStorage.removeItem('expired_authToken')
            return Promise.reject({  response: { status: 401 } })
        }

        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config;
    }
)

// Interceptors untuk Response
api.interceptors.response.use(
    (response) => response, 
    async (error) => {
        // const originalRequest = error.config;


        // HANDLE 401 GLOBAL
        // if (error.response && error.response.status === 401) {
        //     console.warn('Unauthorized! Redirecting to login...')
        //     await handleLogout();
        //     return Promise.reject(error)
        // }

        return Promise.reject(error)
    }
)

const handleLogout = async () => {
    localStorage.removeItem('authToken')
    localStorage.removeItem('expired_authToken') 

    delete api.defaults.headers.common['Authorization'];

    router.push('/admin/login')
    Swal.fire({
        icon: "warning",
        title: "Sesi berakhir",
        text: "Silakan login kembali"
    })
}

const apiLoadings = ref(false)
const apiRequest = async (loadingRef = null, method, url, data = null, isFormData = false, showNotification = true) => {
    if (loadingRef) loadingRef.value = true
    apiLoadings.value = true

    const config = { method, url };

    if (data) {
        const isGet = method.toLowerCase() === 'get'

        if (isGet) {
            config.params = data
        } else if (isFormData && data instanceof FormData) {
            // Jika sudah FormData, langsung gunakan
            config.data = data
            config.headers = {
                "Content-Type": "multipart/form-data"
            }
        } else if (isFormData || containsFile(data)) {
            const formData = new FormData();

            Object.entries(data).forEach(([key, value]) => {
                if (value === null || value === undefined) return;

                if (Array.isArray(value)) {
                    value.forEach((item, index) => {
                        if (item instanceof File) {
                            formData.append(`${key}[${index}]`, item)
                        } else if (typeof item === 'object') {
                            Object.entries(item).forEach(([subKey, subVal]) => {
                                if (subVal !== null && subVal !== undefined) {
                                    formData.append(`${key}[${index}][${subKey}]`, subVal);
                                }
                            });
                        } else {
                            formData.append(`${key}[${index}]`, item);
                        }
                    });
                } else if (value instanceof File) {
                    formData.append(key, value);
                } else if (typeof value === 'object') {
                    formData.append(key, JSON.stringify(value))
                } else {
                    formData.append(key, value)
                }
            });

            config.data = formData
            config.headers = {
                'Content-Type': 'multipart/form-data'
            }
        } else {
            config.data = data
        }
    }

    try {
        const response = await api(config)
        if (showNotification && method.toLowerCase() !== 'get') {
            const resData = response.data
            if (resData.success) {
                sweetSuccess(resData?.title, resData?.message, resData?.id)
            } else {
                sweetError(null, resData?.message)
            }
        }

        return response
    } catch (error) {
        console.error(error)

        if (!error.response) {
            sweetError(error.name || 'Oopss...', error.message || 'Terjadi kesalahan pada server. Coba lagi', 'Unknown')
        } else if (showNotification) {
            const errData = error.response.data
            sweetError(
                errData?.title || 'Oops...',
                errData?.message || Object.values(errData).join(', '),
                errData?.error_id || 'Unknown'
            )
        }

        throw error;
    }
}

const sweetSuccess = (title, pesan, successId = null) => {
    Swal.fire({
        icon: "success",
        title: title ?? 'Yeyy Sukses!',
        html: pesan ?? 'Requestmu sedang diproses',
        confirmButtonColor: '#5cb85c',
        confirmButtonText: '<i class="fa fa-check me-2"></i>OKE',
        footer: successId ? `<strong>ID:</strong> ${successId}` : undefined,
        customClass: {
            popup: 'swal2-custom-zindex'
        }
    });
}

const sweetError = (title, pesan, errorId = "") => {
    Swal.fire({
        icon: "error",
        title: title,
        html: pesan ?? 'Gagal memproses requestmu',
        confirmButtonColor: '#d9534f',
        confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
        footer: errorId ? `<strong>ID:</strong> ${errorId}` : undefined,
        customClass: {
            popup: 'swal2-custom-zindex'
        }
    });
}

const containsFile = (obj) => {
    if (obj instanceof File) return true;
    if (Array.isArray(obj)) return obj.some(containsFile);
    if (typeof obj === 'object' && obj !== null) {
        return Object.values(obj).some(containsFile)
    }
    return false;
}

export  {
    api, 
    apiRequest,
    apiLoadings
}