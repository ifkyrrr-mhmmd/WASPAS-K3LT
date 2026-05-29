# [Issue 7] Modul Riwayat & Cetak Laporan (History & PDF Report)

## Deskripsi
Menyimpan setiap hasil akhir kalkulasi ke dalam database agar dapat dilacak di masa depan, serta memberikan opsi cetak/ekspor hasil ke dalam bentuk dokumen.

## Tasks
- [x] Saat proses evaluasi WASPAS selesai, simpan data ke tabel `histories` secara serial atau JSON (mencakup matriks awal, bobot saat itu, dan hasil peringkat akhir).
- [x] Buat halaman "Riwayat (History)" untuk melihat daftar perhitungan yang pernah dilakukan.
- [x] Buat halaman detail riwayat untuk melihat kembali metrik yang digunakan di masa lampau.
- [x] Implementasi fitur Cetak PDF atau *Print View* (menggunakan *window.print()* atau plugin PDF seperti `dompdf`).
- [x] Bersihkan header cetak dari SVG yang tidak perlu, gunakan font serif/sans-serif yang formal (Inter/Arial).

## Expected Outcome
* Pengguna memiliki jejak rekam pengambilan keputusan (audit-trail).
* Laporan keputusan K3LT dapat dicetak sebagai bukti dokumentasi yang sah.
