# Aplikasi Sistem Pendukung Keputusan WASPAS - K3LT

Sistem Pendukung Keputusan (SPK) untuk pemilihan divisi K3LT (Kesehatan, Keselamatan Kerja, Lingkungan, dan Ketertiban) menggunakan metode **WASPAS (Weighted Aggregated Sum Product Assessment)**. Aplikasi ini dibangun menggunakan Laravel, Tailwind CSS, dan Alpine.js.

## Prasyarat (Requirements)

Sebelum menjalankan aplikasi ini, pastikan laptop Anda sudah terinstal:
1. **PHP** (minimal versi 8.1)
2. **Composer** (untuk instalasi library PHP)
3. **Node.js & NPM** (untuk kompilasi Tailwind CSS dan aset frontend)
4. **MySQL / MariaDB** (bisa menggunakan XAMPP, Laragon, dsb.)
5. **Git** (opsional, untuk kloning repositori)

---

## Langkah-Langkah Menjalankan Project (Untuk Anggota Kelompok)

Jika Anda mendapatkan project ini dari GitHub atau file ZIP, ikuti langkah-langkah berikut untuk menjalankannya di laptop Anda:

### 1. Kloning atau Ekstrak Project
- Buka terminal/command prompt.
- Jika menggunakan Git, jalankan:
  ```bash
  git clone <url-repository-github>
  cd program-waspas-laravel
  ```
- Jika dari file ZIP, ekstrak folder tersebut dan buka terminal di dalam folder project.

### 2. Install Dependensi (Library)
Di dalam terminal project, jalankan perintah ini secara berurutan:
```bash
composer install
npm install
```
*(Proses ini mungkin memakan waktu beberapa saat tergantung kecepatan internet)*

### 3. Konfigurasi Environment (`.env`)
Laravel membutuhkan file konfigurasi. Buat copy dari file `.env.example` dan ubah namanya menjadi `.env`:
```bash
cp .env.example .env
```
*(Di Windows, Anda bisa menggunakan file explorer: copy-paste `.env.example` lalu rename menjadi `.env`)*

Buka file `.env` di text editor (VS Code / Notepad) dan atur koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_waspas_k3lt
DB_USERNAME=root
DB_PASSWORD=
```
*(Sesuaikan dengan konfigurasi database di laptop Anda, misalnya di XAMPP secara default password dikosongkan).*

### 4. Buat Database
- Buka phpMyAdmin (biasanya `http://localhost/phpmyadmin`).
- Buat database baru dengan nama sesuai yang ditulis di `.env` (contoh: `db_waspas_k3lt`).

### 5. Generate Application Key dan Migrasi Database
Jalankan perintah berikut di terminal:
```bash
php artisan key:generate
php artisan migrate --seed
```
*(Perintah `--seed` digunakan jika Anda memiliki seeder untuk data awal, jika tidak ada cukup `php artisan migrate`)*

### 6. Jalankan Server Lokal
Anda membutuhkan 2 terminal yang berjalan secara bersamaan:

**Terminal 1 (Untuk menjalankan Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Untuk mengkompilasi Tailwind & CSS secara realtime):**
```bash
npm run dev
```

Sekarang, Anda bisa membuka aplikasi di browser pada alamat: **http://localhost:8000**

---

## Informasi Penting: Folder yang Berisiko (Jangan Di-upload ke GitHub)

Saat mengupload project Laravel ke GitHub, **TIDAK SEMUA** file dan folder boleh di-upload. Ada beberapa folder berisiko dan sensitif:

1. **`vendor/`**: Berisi ribuan file library PHP hasil dari `composer install`. Ukurannya sangat besar. Cukup file `composer.json` yang di-upload, anggota lain tinggal menjalankan `composer install`.
2. **`node_modules/`**: Sama seperti vendor, berisi library JavaScript hasil `npm install`. Jangan di-upload.
3. **`.env`**: File paling sensitif! Berisi password database, API key, dll. Jika di-upload, kredensial Anda bisa dicuri orang. Yang di-upload hanya `.env.example` sebagai referensi.
4. **`storage/`**: Berisi file log error (`storage/logs`), cache, dan file upload user. Biasanya tidak perlu di-upload.
5. **`public/build/`** atau **`public/hot/`**: Hasil kompilasi Vite yang otomatis dibuat saat menjalankan `npm run dev` atau `npm run build`.

**Kabar Baiknya:** Project ini sudah memiliki file `.gitignore` yang secara otomatis mengabaikan file dan folder berisiko tersebut. Pastikan Anda **TIDAK MENGHAPUS** file `.gitignore` ini.

---

## Langkah-Langkah Upload Project ke GitHub

Untuk mengupload project yang sudah berjalan ini ke repository bersama di GitHub:

1. **Buat Repository Baru di GitHub** (Pilih "New Repository", beri nama, biarkan kosong tanpa README/gitignore).
2. **Buka Terminal di dalam folder project lokal Anda.**
3. **Inisialisasi Git (jika belum ada):**
   ```bash
   git init
   ```
4. **Tambahkan semua file (yang diizinkan oleh .gitignore):**
   ```bash
   git add .
   ```
5. **Simpan perubahan (Commit):**
   ```bash
   git commit -m "Initial commit: Aplikasi WASPAS K3LT"
   ```
6. **Hubungkan ke Repository GitHub:**
   ```bash
   git remote add origin <url-repository-anda.git>
   ```
   *(Contoh: `git remote add origin https://github.com/username/repo-name.git`)*
7. **Upload ke GitHub (Push):**
   ```bash
   git branch -M main
   git push -u origin main
   ```

*(Setelah ini berhasil, minta anggota kelompok Anda untuk mengikuti "Langkah-Langkah Menjalankan Project" di atas).*
