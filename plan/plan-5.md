# [Issue 5] Modul Perhitungan WASPAS Part 1 (Matriks Keputusan)

## Deskripsi
Membuat antarmuka dan *backend* awal untuk memasukkan nilai evaluasi setiap alternatif terhadap masing-masing kriteria. Ini adalah tahap input (Langkah 1 & 2 dari sistem SPK).

## Tasks
- [x] Buat halaman *wizard* atau step-by-step untuk proses perhitungan.
- [x] Buat Step 1: Penentuan Kriteria Aktif (opsional untuk memuat *preset* bobot kriteria saat ini).
- [x] Buat Step 2: Form input Matriks Keputusan. Buat tabel dinamis dimana baris = Alternatif, dan kolom = Kriteria.
- [x] Validasi input matriks: Pastikan tidak ada input yang kosong atau bernilai negatif.
- [x] Gunakan SweetAlert2 untuk memberikan konfirmasi berhasil/gagal memuat preset dan menyimpan matriks.

## Expected Outcome
* Tabel matriks keputusan dapat diisi secara intuitif oleh pengguna.
* Notifikasi sukses/gagal muncul dengan benar.
