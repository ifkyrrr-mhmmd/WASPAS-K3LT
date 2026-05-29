# [Issue 2] Desain Skema Database (Database Design)

## Deskripsi
Merancang dan mengimplementasikan struktur tabel pada database untuk mengakomodasi kebutuhan data Master (Kriteria, Alternatif), Transaksional (Riwayat Perhitungan), dan Sistem (Audit Log, Pengaturan).

## Tasks
- [x] Buat file migrasi untuk tabel `criteria` (id, name, weight, type (benefit/cost), code).
- [x] Buat file migrasi untuk tabel `alternatives` (id, name, code, description).
- [x] Buat file migrasi untuk tabel `evaluations` atau relasi antara alternatif dan kriteria (jika nilai statis).
- [x] Buat file migrasi untuk tabel `histories` (menyimpan hasil perhitungan secara serial atau JSON, skor akhir, peringkat).
- [x] Buat file migrasi untuk tabel `audit_logs` (mencatat aktivitas krusial pengguna).
- [x] Buat Model Eloquent untuk setiap tabel beserta relasi antar model (`hasMany`, `belongsTo`).
- [x] Buat Database Seeder untuk memasukkan *dummy data* awal (misal: Kriteria C1-C5 beserta bobotnya).

## Expected Outcome
* Struktur database terbentuk dengan relasi yang tepat saat menjalankan `php artisan migrate --seed`.
* Data awal kriteria K3LT otomatis terisi.
