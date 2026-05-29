# [Issue 8] Modul Analisis Sensitivitas (Sensitivity Analysis)

## Deskripsi
Menambahkan fitur tingkat lanjut di mana evaluator bisa melihat seberapa tangguh sebuah peringkat terhadap perubahan bobot kriteria.

## Tasks
- [x] Buat halaman *Sensitivity Analysis*.
- [x] Tarik data riwayat/kalkulasi terakhir dari database beserta data JSON `scores`-nya.
- [x] Buat form interaktif di mana pengguna bisa menggeser nilai slider (bobot) untuk masing-masing kriteria secara *real-time* atau lewat form submisi.
- [x] Lakukan re-kalkulasi logika normalisasi WASPAS dengan bobot sementara tersebut.
- [x] Tampilkan grafik perbandingan atau tabel perbandingan ranking (Sebelum vs Sesudah perubahan bobot).

## Expected Outcome
* Manajer K3LT dapat mensimulasikan skenario *"What-if"* tanpa mengubah data utama master kriteria.
* Membantu memvalidasi seberapa kokoh alternatif terpilih dalam berbagai skenario kebijakan.
