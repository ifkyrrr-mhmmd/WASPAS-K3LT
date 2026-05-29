# [Issue 10] Persiapan Deployment & Dokumentasi (Deployment Prep)

## Deskripsi
Tahap finalisasi siklus pengembangan agar aplikasi siap didistribusikan kepada anggota tim dan diunggah ke *repository* kontrol versi (GitHub).

## Tasks
- [x] Hapus file, folder sementara, dan artefak AI (`plan/`, `scratch/`, script Python yang tak terpakai) di *production/root folder*.
- [x] Pastikan file konfigurasi `.gitignore` sudah mencakup folder-folder berisiko seperti `vendor/`, `node_modules/`, `storage/`, dan `.env`.
- [x] Tulis ulang dokumentasi utama pada file `README.md`.
- [x] Sertakan langkah-langkah *instalasi lokal* untuk kelompok (Git clone, composer install, npm install, config database).
- [x] Sertakan instruksi untuk menginisialisasi Git dan melakukan *push* (upload) ke GitHub.
- [x] Audit final project structure.

## Expected Outcome
* Proyek rapi dan aman untuk dipublikasikan.
* Anggota tim lain dapat melakukan *setup* aplikasi dalam waktu kurang dari 5 menit berkat dokumentasi yang jelas.
