# [Issue 4] Modul Manajemen Kriteria & Alternatif (Master Data)

## Deskripsi
Mengembangkan fitur CRUD (Create, Read, Update, Delete) untuk mengelola data master yaitu Kriteria (bobot dan jenis atribut) serta Alternatif (kandidat/divisi yang dinilai).

## Tasks
- [x] Buat controller `CriteriaController` dan `AlternativeController`.
- [x] Buat form input (Create/Edit) untuk Kriteria dengan validasi agar total bobot idealnya bisa dikontrol (sum = 1 atau 100).
- [x] Sediakan validasi tipe atribut: `Cost` (Biaya) atau `Benefit` (Keuntungan).
- [x] Buat form input (Create/Edit) untuk Alternatif.
- [x] Tampilkan data menggunakan Data Table yang interaktif (pencarian & pagination).
- [x] Implementasikan fitur proteksi (jangan izinkan hapus kriteria jika sedang dipakai dalam perhitungan aktif).

## Expected Outcome
* Pengguna dapat dengan mudah menambahkan, mengubah, dan menghapus kandidat (alternatif) serta parameter penilaian (kriteria).
* Data tervalidasi dengan baik (mencegah input bobot teks, dll).
