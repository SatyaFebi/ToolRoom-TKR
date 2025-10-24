# ToolRoom TKR 🚗🛠️

ToolRoom TKR adalah aplikasi web untuk manajemen servis kendaraan dan inventaris suku cadang.  
Frontend-nya menggunakan **Vue.js**, backend-nya menggunakan **Laravel (PHP)**.

---

## 🎯 Fitur Utama

- CRUD servis kendaraan (booking, status, histori)  
- Manajemen inventaris suku cadang (stok, pemasukan, pengeluaran)  
- Dashboard statistik / laporan  
- Otorisasi & autentikasi (role teknisi, admin, customer)  
- Notifikasi / alert stok rendah (opsional)  
- Integrasi praktis antara frontend & backend  

---

## 📁 Struktur Proyek

/
├── backend/ # Laravel API & server-side logic
│ ├── app/
│ ├── routes/
│ ├── database/
│ ├── config/
│ └── …
├── frontend/ # Vue.js client
│ ├── src/
│ ├── public/
│ ├── components/
│ └── …
├── .gitignore
├── README.md
└── LICENSE

## 🛠️ Persiapan Lingkungan / Setup

### Prasyarat

- PHP >= 8.x  
- Composer  
- Node.js & npm / yarn  
- Database (MySQL, PostgreSQL, SQLite, dsb)  
- (Opsional) Redis, Queue, dsb

### Langkah Instalasi

1. Clone repo  
   ```bash
   git clone https://github.com/SatyaFebi/ToolRoom TKR.git
   cd ToolRoom TKR
2. Setup backend
   ```bash
   cd backend  
   composer install  
   cp .env.example .env  
   # edit .env sesuai environment (DB, dsb)  
   php artisan key:generate  
   php artisan migrate --seed  
   php artisan serve  
3. Setup frontend
   ```bash
   cd ../frontend  
   npm install   
   cp .env.example .env  
   # ubah base API URL jika perlu  
   npm run dev
4. Akses aplikasi
   Frontend: http://localhost:3000 (atau port lain)
   Backend API: http://localhost:8000/api (atau port Laravel)

## 🔐 Autentikasi & Otorisasi

### Menggunakan JWT atau Sanctum (tergantung implementasi)

Role umum: Admin, Guru, Kepala Kompetensi, Murid

Akses ke modul inventaris & laporan dibatasi hanya untuk admin, guru, dan kepala kompetensi

## 💡 Catatan / Tips Pengembangan

- Pastikan cors / proxy sudah dikonfigurasi agar frontend bisa memanggil API tanpa error CORS

- Gunakan axios / fetch di Vue untuk komunikasi dengan backend

- Validasi input di sisi backend & frontend

- Untuk laporan besar, optimalkan query & pagination

- Tambahkan unit test / feature test di Laravel

- (Opsional) gunakan Vuex / Pinia / state management agar manajemen state lebih rapi

## 📦 Deploy & Produksi

- Sesuaikan .env

- Build frontend: npm run build

- Siapkan server PHP + webserver (Nginx / Apache) untuk Laravel

- Pastikan direktori storage & bootstrap/cache punya izin write

- Gunakan scheduler / queue jika ada tugas background

- Gunakan SSL / HTTPS
