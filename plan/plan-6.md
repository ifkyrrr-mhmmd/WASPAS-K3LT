# [Issue 6] Modul Perhitungan WASPAS Part 2 (Logika & Normalisasi)

## Deskripsi
Mengimplementasikan algoritma inti WASPAS pada sisi backend (Controller / Service Class) dan menampilkannya dalam tabel hasil evaluasi.

## Tasks
- [x] Implementasi Logika Normalisasi (Step 3):
  - Jika kriteria Benefit: $x_{ij} / max(x_j)$
  - Jika kriteria Cost: $min(x_j) / x_{ij}$
- [x] Implementasi Logika WSM (Weighted Sum Model) - (Step 4).
- [x] Implementasi Logika WPM (Weighted Product Model) - (Step 5).
- [x] Hitung nilai Qi final dengan formula $\lambda WSM + (1-\lambda) WPM$. (Default $\lambda = 0.5$).
- [x] Urutkan (Rank) alternatif berdasarkan nilai Qi tertinggi.
- [x] Tampilkan seluruh tabel langkah (Matriks Normalisasi, WSM, WPM, dan Hasil Akhir) di UI hasil perhitungan.

## Expected Outcome
* Perhitungan berjalan secara matematis dan presisi.
* Hasil peringkat sesuai dengan perhitungan manual WASPAS yang diuji coba.
