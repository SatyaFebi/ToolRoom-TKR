import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './style.css'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

const app = createApp(App)

app.use(createPinia())
    .use(router)
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
    .mount('#app')