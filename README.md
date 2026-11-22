<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Jelajah Bengkulu
### Large Language Model-Based Knowledge Extraction from Local Community Narratives for Coastal Cultural Preservation

Jelajah Bengkulu merupakan aplikasi berbasis web yang dikembangkan untuk mendukung pelestarian pengetahuan budaya masyarakat pesisir Bengkulu. Aplikasi ini memanfaatkan teknologi Large Language Model (LLM) untuk membantu mengekstraksi informasi budaya dari narasi masyarakat lokal menjadi pengetahuan yang terstruktur dan dapat diakses secara digital.

Informasi yang diekstraksi mencakup tradisi, istilah budaya, praktik masyarakat, cerita lokal, lokasi, tokoh, waktu, dan makna budaya. Hasil ekstraksi akan melalui proses pemeriksaan dan validasi oleh Admin sebelum dipublikasikan kepada pengguna.

---

## Informasi Proyek

| Keterangan | Detail |
|---|---|
| Nama Aplikasi | Jelajah Bengkulu |
| Judul Capstone | Large Language Model-Based Knowledge Extraction from Local Community Narratives for Coastal Cultural Preservation |
| Perguruan Tinggi | Universitas Bengkulu |
| Fakultas | Fakultas Teknik |
| Program Studi | Teknik Informatika |
| Metode Pengembangan | Waterfall |
| Jenis Aplikasi | Aplikasi Berbasis Web |

## Tim Pengembang

**Tim Capstone Project – Teknik Informatika Universitas Bengkulu**

- **Aisyah Azzahrah** (G1A024033) – Project Manager
- **Abid Abdussalam** (G1A024073) – Lead Developer
- **Ikhsan Rihan Farli** (G1A024083) – System Analyst

**Dosen Pembimbing:**

Ir. Arie Vatresia, S.T., M.T.I., Ph.D., IPP

---

## Fitur Aplikasi

### 1. Fitur Pengguna (User)

- **Eksplorasi Pengetahuan Budaya:** Melihat berbagai informasi budaya Bengkulu yang telah dipublikasikan.
- **Pencarian Pengetahuan:** Mencari informasi budaya berdasarkan kata kunci.
- **Filter Kategori:** Menyaring pengetahuan berdasarkan kategori budaya.
- **Detail Pengetahuan:** Melihat informasi lengkap mengenai tradisi, istilah budaya, praktik, dan cerita masyarakat.
- **Registrasi dan Login:** Membuat akun dan masuk ke dalam sistem.
- **Testimoni:** Memberikan tanggapan atau testimoni terhadap informasi budaya.
- **Melihat Testimoni:** Membaca testimoni yang telah diperiksa dan disetujui oleh Admin.

### 2. Fitur Administrator (Admin)

- **Dashboard Admin:** Mengelola aktivitas dan informasi dalam sistem.
- **Manajemen Pengguna:** Mengelola akun pengguna yang terdaftar.
- **Manajemen Narasi:** Menambahkan, melihat, mengubah, dan mengelola data narasi masyarakat.
- **Ekstraksi Pengetahuan LLM:** Memproses narasi menggunakan Large Language Model.
- **Manajemen Hasil Ekstraksi:** Melihat dan memeriksa hasil informasi yang dihasilkan oleh LLM.
- **Manajemen Pengetahuan:** Mengelola informasi budaya yang telah diekstraksi.
- **Validasi Pengetahuan:** Memeriksa dan memvalidasi hasil ekstraksi sebelum dipublikasikan.
- **Manajemen Testimoni:** Memeriksa, menyetujui, dan mengelola testimoni pengguna.

### 3. Ekstraksi Pengetahuan Berbasis LLM

Sistem menggunakan Large Language Model untuk membantu mengubah narasi masyarakat yang belum terstruktur menjadi informasi budaya yang lebih terorganisasi.

Tahapan ekstraksi meliputi:

1. Pengumpulan narasi masyarakat lokal.
2. Pembersihan dan persiapan data narasi.
3. Pemrosesan narasi menggunakan LLM.
4. Identifikasi informasi dan entitas budaya.
5. Pembentukan hasil ekstraksi dalam format terstruktur.
6. Pemeriksaan dan validasi oleh Admin.
7. Publikasi pengetahuan budaya yang telah divalidasi.

**Catatan:** Hasil ekstraksi LLM tidak dianggap sebagai sumber kebenaran secara otomatis. Setiap informasi perlu diperiksa dan divalidasi oleh Admin sebelum dipublikasikan.

---

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| Laravel 13 | Framework pengembangan aplikasi web |
| PHP 8.3 | Bahasa pemrograman sisi server |
| MySQL | Sistem manajemen basis data |
| Ollama | Menjalankan model LLM secara lokal |
| Gemma 2B | Model bahasa untuk ekstraksi pengetahuan |
| Blade | Template tampilan Laravel |
| HTML, CSS, JavaScript | Pengembangan antarmuka aplikasi |
| Tailwind CSS | Pengaturan desain dan tampilan |
| Vite | Pengelolaan aset frontend |
| Laragon | Lingkungan pengembangan lokal |
| Visual Studio Code | Editor kode program |
| Git dan GitHub | Pengelolaan versi kode |
| Postman | Pengujian API |
| Figma | Perancangan antarmuka pengguna |

---

## Arsitektur Sistem

Sistem terdiri dari tiga lapisan utama:

### 1. Presentation Layer

Lapisan yang menyediakan antarmuka aplikasi bagi pengguna dan Admin.

### 2. Application Layer

Lapisan yang menangani autentikasi, pengelolaan narasi, integrasi LLM, ekstraksi pengetahuan, validasi, dan publikasi informasi budaya.

### 3. Data Layer

Lapisan yang menggunakan MySQL untuk menyimpan data pengguna, narasi, hasil ekstraksi, pengetahuan budaya, dan testimoni.

### Alur Ekstraksi Pengetahuan

```text
       Narasi Masyarakat Lokal
                 |
                 v
        Persiapan Data Narasi
                 |
                 v
          Ollama / Gemma 2B
                 |
                 v
       Informasi Terstruktur
                 |
                 v
         Hasil Ekstraksi
                 |
                 v
       Validasi oleh Admin
                 |
                 v
        Pengetahuan Budaya
                 |
                 v
       Publikasi ke Aplikasi
```

---

## Persyaratan Sistem

Sebelum melakukan instalasi, pastikan perangkat telah memiliki perangkat lunak berikut:

- Sistem Operasi Windows 10 atau Windows 11.
- PHP versi 8.3 atau lebih baru.
- Composer.
- MySQL.
- Node.js dan NPM.
- Git.
- Laragon.
- Ollama.
- Model Gemma 2B.

### Ekstensi PHP

Pastikan ekstensi PHP berikut telah diaktifkan:

- `pdo_mysql`
- `mbstring`
- `openssl`
- `curl`
- `fileinfo`
- `gd`

---

## Panduan Instalasi

### 1. Masuk ke Direktori Proyek

Buka PowerShell, kemudian masuk ke direktori Laragon:

```powershell
cd C:\laragon\www
```

Jika proyek sudah tersedia di komputer, langsung masuk ke direktori proyek:

```powershell
cd C:\laragon\www\knowledge-explorer-bengkulu
```

Jika ingin mengambil proyek dari GitHub:

```powershell
git clone https://github.com/Aisnya975/capstone_JelajahBengkulu_KelA9.git
```

Masuk ke folder hasil clone sesuai nama folder yang terbentuk.

### 2. Instalasi Dependensi Laravel

Jalankan perintah berikut untuk memasang dependensi PHP:

```powershell
composer install
```

Salin file konfigurasi lingkungan:

```powershell
Copy-Item .env.example .env
```

Buat application key:

```powershell
php artisan key:generate
```

### 3. Instalasi Dependensi Frontend

Pasang dependensi frontend:

```powershell
npm install
```

Bangun aset frontend:

```powershell
npm run build
```

### 4. Konfigurasi Database MySQL

1. Buka aplikasi Laragon.
2. Klik **Start All** untuk menjalankan layanan MySQL.
3. Buka phpMyAdmin atau aplikasi pengelola database lainnya.
4. Buat database baru dengan nama `jelajah_bengkulu`.

Selanjutnya, sesuaikan konfigurasi pada file `.env`:

```env
APP_NAME="Jelajah Bengkulu"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jelajah_bengkulu
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, dan password dengan konfigurasi MySQL yang digunakan.

### 5. Menjalankan Migrasi Database

Jalankan migrasi untuk membuat tabel database:

```powershell
php artisan migrate
```

Jika proyek menyediakan seeder:

```powershell
php artisan db:seed
```

Buat symbolic link untuk penyimpanan file publik:

```powershell
php artisan storage:link
```

**Perhatian:** Jangan menggunakan `php artisan migrate:fresh` atau `php artisan db:wipe` pada database yang sudah berisi data penting karena perintah tersebut dapat menghapus data.

### 6. Instalasi Ollama dan Model Gemma 2B

Unduh dan instal Ollama melalui situs resmi:

https://ollama.com/download

Setelah instalasi selesai, buka PowerShell dan unduh model:

```powershell
ollama pull gemma:2b
```

Periksa model yang telah terpasang:

```powershell
ollama list
```

Pastikan model `gemma:2b` tersedia.

Ollama menyediakan layanan API lokal yang secara default dapat diakses melalui:

```text
http://localhost:11434
```

Pastikan konfigurasi integrasi LLM pada aplikasi Laravel menggunakan alamat API dan nama model yang sesuai.

---

## Cara Menjalankan Aplikasi

### 1. Menjalankan Ollama

Pastikan layanan Ollama aktif.

Jika belum berjalan, buka terminal dan jalankan:

```powershell
ollama serve
```

Biarkan terminal tetap berjalan selama fitur ekstraksi pengetahuan digunakan.

### 2. Menjalankan Laravel

Buka terminal baru pada direktori proyek, kemudian jalankan:

```powershell
php artisan serve --port=8000
```

Buka browser dan akses aplikasi melalui:

```text
http://127.0.0.1:8000
```

Untuk mengakses halaman Admin, gunakan alamat sesuai konfigurasi route yang tersedia pada aplikasi.

---

## Akses Aplikasi Melalui Jaringan Wi-Fi Lokal

Aplikasi dapat diakses melalui perangkat lain yang terhubung ke jaringan Wi-Fi yang sama.

### Langkah 1. Periksa Alamat IP Laptop

Buka PowerShell:

```powershell
ipconfig
```

Cari bagian IPv4 Address pada adapter Wi-Fi yang sedang digunakan.

Contoh:

```text
192.168.1.12
```

### Langkah 2. Jalankan Laravel dengan Akses Jaringan

Jalankan perintah:

```powershell
php artisan serve --host=0.0.0.0 --port=8000
```

Jika muncul notifikasi Windows Defender Firewall, izinkan akses pada jaringan privat.

### Langkah 3. Akses Melalui Perangkat Lain

Buka browser pada HP atau laptop lain yang terhubung ke Wi-Fi yang sama.

Masukkan alamat:

```text
http://192.168.1.12:8000
```

Ganti alamat IP tersebut dengan IPv4 laptop yang menjalankan aplikasi.

**Catatan:** Akses jaringan lokal tidak otomatis membuka layanan Ollama untuk perangkat lain. Proses ekstraksi tetap dijalankan melalui komputer yang menjadi server Laravel.

---

## Akses Publik Sementara Menggunakan Cloudflare Tunnel

Untuk kebutuhan demonstrasi, aplikasi dapat diakses melalui internet menggunakan Cloudflare Tunnel tanpa harus melakukan deployment ke hosting permanen.

### Langkah 1. Instalasi Cloudflare Tunnel

Buka PowerShell dan jalankan:

```powershell
winget install --id Cloudflare.cloudflared
```

Setelah instalasi selesai, buka kembali terminal.

### Langkah 2. Jalankan Laravel

Pada terminal pertama, jalankan:

```powershell
php artisan serve --port=8000
```

Biarkan terminal tetap berjalan.

### Langkah 3. Jalankan Cloudflare Tunnel

Buka terminal kedua dan jalankan:

```powershell
cloudflared tunnel --url http://127.0.0.1:8000
```

Cloudflare akan menghasilkan alamat sementara dengan format:

```text
https://example-name.trycloudflare.com
```

Gunakan alamat tersebut untuk mengakses aplikasi melalui perangkat lain.

### Catatan Penting

- Terminal Laravel dan Cloudflare Tunnel harus tetap berjalan.
- URL sementara dapat berubah ketika tunnel dijalankan ulang.
- Jika laptop dimatikan atau masuk ke mode sleep, aplikasi tidak dapat diakses.
- Cloudflare Tunnel sementara ditujukan untuk demonstrasi dan pengujian.
- Hindari mengekspos data sensitif dan halaman administrator melalui akses publik yang tidak diamankan.

---

## Pemeliharaan dan Perintah Penting

### Membersihkan Cache Laravel

```powershell
php artisan optimize:clear
```

### Membangun Ulang Aset Frontend

```powershell
npm run build
```

### Melihat Daftar Route

```powershell
php artisan route:list
```

### Memeriksa Informasi Aplikasi

```powershell
php artisan about
```

### Memeriksa Status Migrasi Database

```powershell
php artisan migrate:status
```

### Melihat Model Ollama

```powershell
ollama list
```

---

## Troubleshooting

### 1. Database Tidak Dapat Terhubung

Periksa beberapa hal berikut:

- Pastikan MySQL pada Laragon sudah berjalan.
- Pastikan nama database sesuai dengan file `.env`.
- Periksa username dan password database.
- Pastikan port MySQL sudah benar.

Setelah mengubah konfigurasi `.env`, jalankan:

```powershell
php artisan optimize:clear
```

### 2. Ollama Tidak Dapat Terhubung

Jika aplikasi tidak dapat terhubung ke Ollama:

- Pastikan Ollama telah terinstal.
- Pastikan layanan Ollama sedang berjalan.
- Periksa apakah model `gemma:2b` sudah terpasang.
- Periksa alamat API Ollama yang digunakan aplikasi.
- Pastikan nama model sesuai dengan model yang tersedia.

### 3. CSS atau JavaScript Tidak Muncul

Jalankan kembali:

```powershell
npm install
npm run build
```

Kemudian bersihkan cache:

```powershell
php artisan optimize:clear
```

### 4. Terjadi Kesalahan Middleware Admin

Jika halaman Admin mengalami kesalahan:

- Periksa implementasi middleware Admin.
- Pastikan alias middleware telah terdaftar di `bootstrap/app.php`.
- Periksa proses autentikasi dan otorisasi Admin.
- Periksa konfigurasi route Admin.

### 5. Gambar atau File Tidak Ditampilkan

Jalankan perintah:

```powershell
php artisan storage:link
```

Pastikan file tersimpan pada direktori storage yang sesuai.

---

## Struktur Direktori Proyek

Berikut merupakan gambaran umum struktur direktori Laravel:

```text
knowledge-explorer-bengkulu/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
│
├── bootstrap/
│   └── app.php
│
├── config/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── web.php
│   └── console.php
│
├── storage/
│
├── tests/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

Struktur di atas merupakan gambaran umum dan dapat berbeda sesuai implementasi aplikasi.

---

## Ruang Lingkup Penelitian

Proyek ini berfokus pada ekstraksi dan pelestarian pengetahuan budaya masyarakat lokal melalui pemanfaatan teknologi Large Language Model.

Informasi budaya yang menjadi fokus pengelolaan meliputi:

- Tradisi masyarakat.
- Istilah budaya.
- Praktik tradisional.
- Cerita masyarakat.
- Lokasi budaya.
- Tokoh budaya.
- Waktu atau periode pelaksanaan.
- Makna budaya.

Proses penelitian mengubah narasi masyarakat menjadi informasi yang lebih terstruktur melalui tahapan:

**Narasi → Informasi → Entitas → Hubungan → Basis Pengetahuan**

LLM digunakan sebagai alat bantu ekstraksi informasi, sedangkan proses pemeriksaan dan validasi dilakukan oleh Admin untuk menjaga keakuratan informasi sebelum dipublikasikan.

---

## Keamanan Sistem

Beberapa hal yang perlu diperhatikan dalam pengelolaan aplikasi:

- Jangan membagikan file `.env` ke publik.
- Jangan mengunggah API key, password, atau informasi rahasia ke GitHub.
- Terapkan autentikasi dan otorisasi pada halaman administrator.
- Lakukan validasi terhadap input pengguna.
- Periksa hasil ekstraksi LLM sebelum dipublikasikan.
- Hindari mengekspos data sensitif melalui tunnel publik.
- Gunakan HTTPS dan konfigurasi keamanan yang sesuai ketika aplikasi diterapkan pada lingkungan produksi.

---
