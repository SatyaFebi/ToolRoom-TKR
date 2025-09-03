import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './style.css'
import api from '@/plugins/api'
import { useAuthStore } from '@/stores/auth'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faChevronRight, faMinus, faPlus, faUser, faHome, faGears, faBoxArchive } from '@fortawesome/free-solid-svg-icons'

library.add(faChevronRight, faMinus, faPlus, faUser, faHome, faGears, faBoxArchive)

const app = createApp(App)

app.use(createPinia())
    .use(router)
    .component('font-awesome-icon', FontAwesomeIcon)
    .use(PrimeVue, {
      theme: {
         preset: Aura,
         options: {
            prefix: 'p',
            darkModeSelector: '#ffffff',
            cssLayer: false
         }
      },
      locale: {
         accept: 'Terima',
         reject: 'Tolak',
         choose: 'Pilih',
         upload: 'Unggah',
         cancel: 'Batal',
         dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
         dayNamesShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
         dayNamesMin: ['Mi', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
         monthNames: [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
         ],
         monthNamesShort: [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
         ],
         today: 'Hari ini',
         clear: 'Bersihkan',
         weekHeader: 'Mgg',
         firstDayOfWeek: 1, // Senin sebagai hari pertama
         dateFormat: 'dd/mm/yy',
         weak: 'Lemah',
         medium: 'Sedang',
         strong: 'Kuat',
         passwordPrompt: 'Masukkan kata sandi',
         emptyMessage: 'Tidak ada data tersedia',
         emptyFilterMessage: 'Tidak ditemukan hasil'
      },
      ripple: true
   })
// restore token from localStorage dan set ke api + store
const token = localStorage.getItem('authToken')
if (token) {
  api.defaults.headers.common['Authorization'] = `Bearer ${token}`
  const auth = useAuthStore() // Pinia sudah terpasang, aman untuk memanggil store
  auth.setToken(token)
  // opsional: sinkronkan user dari server
  auth.getMe().catch(() => { /* ignore jika gagal */ })
}
app.mount('#app')