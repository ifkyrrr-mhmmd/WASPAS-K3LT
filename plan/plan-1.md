# [Issue 1] Setup & Konfigurasi Awal (Project Initialization)

## Deskripsi
Tahap pertama dalam pengembangan Sistem Pendukung Keputusan (SPK) WASPAS - K3LT adalah melakukan inisialisasi *environment* pengembangan, menginstal framework, dan mengatur autentikasi dasar agar sistem siap dibangun.

## Tasks
- [x] Instalasi Laravel versi terbaru via Composer.
- [x] Konfigurasi environment variabel pada file `.env` (Koneksi database MySQL, App Key).
- [x] Setup package manager (NPM/Yarn) dan kompilasi aset dasar dengan Vite.
- [x] Instalasi dan konfigurasi Tailwind CSS beserta plugin yang diperlukan (misal: typography, forms).
- [x] Instalasi Alpine.js untuk interaktivitas komponen frontend.
- [x] Implementasi sistem autentikasi dasar (Login, Register, Logout) menggunakan Laravel Breeze atau Jetstream.
- [x] Konfigurasi routing dasar untuk *guest* dan *authenticated users*.

## Expected Outcome
* Aplikasi dapat diakses di `localhost:8000`.
* User dapat melakukan registrasi dan login.
* Tailwind CSS dan Alpine.js berfungsi dengan baik di frontend.
