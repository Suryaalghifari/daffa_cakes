# 🍰 Daffa Cakes - Sistem Manajemen Toko Kue (PHP Native)

**Daffa Cakes** adalah aplikasi manajemen toko berbasis **PHP Native (tanpa framework)** yang dibangun untuk mengelola proses penjualan kue dengan efisien dan modern. Sistem ini mendukung **multi-role user** dengan fitur transaksi langsung dan online.

## 👥 Role Pengguna

- 👑 **Owner**  
  Melihat laporan harian/bulanan, kelola user (admin & kasir), kelola tampilan website.
  
- 🧑‍💼 **Admin**  
  Mengelola produk dan kategori produk.

- 💼 **Kasir**  
  Melakukan transaksi penjualan secara **offline langsung di toko** maupun **online dari website pelanggan** dan memvalidasi pembayaran.

- 🧑‍🍳 **Pelanggan**  
  Melakukan pemesanan/checkout kue, mengunggah bukti pembayaran, dan memantau status transaksi.

## 🔧 Fitur Utama

- 💻 **Login multi-role** (Owner, Admin, Kasir, Pelanggan)
- 📦 CRUD Produk & Kategori
- 🧾 **Transaksi Offline & Online**  
  - Offline: Kasir langsung input transaksi di dashboard.  
  - Online: Pelanggan checkout, kasir memverifikasi & memproses.
- 📥 Upload Bukti Pembayaran (Transfer/QRIS)
- 🧮 Validasi Pembayaran oleh Kasir
- 📈 Dashboard Statistik Interaktif (pendapatan, grafik penjualan)
- 📑 Laporan Harian & Bulanan (bisa dicetak)
- 🌐 Halaman Landing Page Dinamis (Hero, Tentang Kami, Kontak)
- 📷 Upload dan ganti foto profil untuk semua role
- 🎨 UI berbasis SB Admin & Bootstrap + Font Poppins
- 🔔 Notifikasi interaktif dengan SweetAlert 2

## 🚀 Teknologi yang Digunakan

- **PHP Native** (tanpa framework)
- **MySQL**
- **JavaScript & AJAX**
- **Bootstrap 4**
- **SB Admin 2 Template**
- **SweetAlert 2**

## 📂 Struktur Folder

/daffa_cakes/
├── config/ # File koneksi database & base URL
├── views/
│ ├── auth/ # Login & register semua user
│ ├── owner/ # Dashboard & fitur Owner
│ ├── admin/ # Manajemen produk & kategori
│ ├── kasir/ # Transaksi, validasi, laporan kasir
│ ├── pelanggan/ # Checkout, riwayat pesanan, edit profil
│ ├── halamanweb/ # Landing page publik
│ └── website/ # Kelola konten website
├── assets/ # Gambar produk, profile, dll
└── database/ # (Opsional) Struktur SQL database


## 🔐 Demo Login

| Role     | Username | Password    |
|----------|----------|-------------|
| Owner    | `owner`  | `owner123`  |
| Admin    | `Genjor` | `admin123`  |
| Kasir    | `siwarto`| `kasir123`  |

🔗 Akses Pelanggan:  
[http://localhost/daffa_cakes/views/halamanweb/index.php](http://localhost/daffa_cakes/views/halamanweb/index.php)
