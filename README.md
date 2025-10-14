# PitStop 🚗🛠️

PitStop adalah aplikasi web untuk manajemen servis kendaraan dan inventaris suku cadang.  
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
   git clone https://github.com/SatyaFebi/PitStop.git
   cd PitStop
2. Setup backend
   ```bash
   cd backend  
   composer install  
   cp .env.example .env  
   # edit .env sesuai environment (DB, mail, dsb)  
   php artisan key:generate  
   php artisan migrate --seed  
   php artisan serve  
