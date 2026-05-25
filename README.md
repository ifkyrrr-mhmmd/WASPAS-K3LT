# SPK WASPAS K3LT

**Sistem Pendukung Keputusan Seleksi Kepala Divisi K3LT**  
*Menggunakan Metode WASPAS (Weighted Aggregated Sum Product Assessment)*

> Dibuat oleh **Kelompok 2** — Teknik Informatika  
> Mata Kuliah: Sistem Pendukung Keputusan / Mobile Programming

---

## 📱 Tentang Aplikasi

Aplikasi mobile Android untuk membantu proses seleksi Kepala Divisi K3LT (Keselamatan, Kesehatan Kerja, Lingkungan, dan Transportasi) menggunakan metode WASPAS yang menggabungkan SAW (Simple Additive Weighting) dan WP (Weighted Product).

### Rumus WASPAS
```
Qi = λ × Q1(SAW) + (1 - λ) × Q2(WP)

Dimana:
  Q1 = Σ (rij × wj)     — Simple Additive Weighting
  Q2 = Π (rij ^ wj)     — Weighted Product
  rij = nilai ternormalisasi
  wj  = bobot kriteria
```

---

## ✨ Fitur

- 🔐 **Autentikasi** — Login/Register dengan Firebase Auth
- 📊 **Dashboard** — Ringkasan statistik dan riwayat perhitungan
- 🧮 **Kalkulator WASPAS** — Input kriteria, bobot, alternatif, dan hitung perangkingan
- 🏭 **Template K3LT** — Template kriteria bawaan khusus domain K3LT
- ⚙️ **Konfigurasi Lambda** — Atur bobot antara SAW dan WP
- 📈 **Analisis Sensitivitas** — Uji sensitivitas terhadap perubahan lambda dan bobot
- 💾 **Simpan & Riwayat** — Data tersimpan di Firebase Firestore
- 📄 **Export PDF** — Cetak laporan hasil seleksi dalam format PDF
- 📝 **Audit Trail** — Catatan riwayat aktivitas pengguna

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Framework | Flutter 3.38+ |
| Bahasa | Dart 3.10+ |
| Database | Firebase Firestore |
| Autentikasi | Firebase Auth |
| State Management | Provider |
| PDF | pdf + printing |
| Font | Google Fonts (Inter) |

---

## 🚀 Cara Menjalankan

### Prasyarat
- Flutter SDK 3.38+
- Android Studio / VS Code
- Firebase project (lihat Setup Firebase)

### Setup Firebase
1. Buat project di [Firebase Console](https://console.firebase.google.com)
2. Aktifkan **Authentication** (Email/Password)
3. Aktifkan **Cloud Firestore** 
4. Download `google-services.json` dan taruh di `android/app/`
5. Jalankan `flutterfire configure` atau setup manual

### Menjalankan Aplikasi
```bash
flutter pub get
flutter run
```

### Build APK
```bash
flutter build apk --release
```

---

## 📁 Struktur Proyek

```
lib/
├── main.dart                    # Entry point
├── config/
│   ├── theme.dart               # App theme & colors
│   └── routes.dart              # Route configuration
├── models/
│   ├── user_model.dart          # User data model
│   ├── criteria_model.dart      # Criteria data model
│   ├── alternative_model.dart   # Alternative data model
│   ├── calculation_result.dart  # Calculation result model
│   └── audit_log.dart           # Audit log model
├── services/
│   ├── auth_service.dart        # Firebase Auth service
│   ├── firestore_service.dart   # Firestore CRUD service
│   └── pdf_service.dart         # PDF export service
├── providers/
│   ├── auth_provider.dart       # Auth state management
│   └── waspas_provider.dart     # WASPAS state management
├── utils/
│   ├── waspas_calculator.dart   # WASPAS algorithm
│   ├── k3lt_templates.dart      # K3LT criteria templates
│   ├── sensitivity_analysis.dart # Sensitivity analysis
│   └── validators.dart          # Form validators
├── screens/
│   ├── splash_screen.dart       # Splash screen
│   ├── login_screen.dart        # Login screen
│   ├── register_screen.dart     # Register screen
│   ├── dashboard_screen.dart    # Main dashboard
│   ├── calculator/
│   │   ├── calculator_screen.dart # Calculator main
│   │   ├── criteria_step.dart   # Criteria input step
│   │   ├── matrix_step.dart     # Matrix input step
│   │   └── results_step.dart    # Results display step
│   ├── history_screen.dart      # Calculation history
│   ├── sensitivity_screen.dart  # Sensitivity analysis
│   └── profile_screen.dart      # User profile
└── widgets/
    ├── custom_text_field.dart    # Reusable text field
    ├── gradient_button.dart      # Gradient button
    ├── metric_card.dart          # Metric display card
    ├── ranking_card.dart         # Ranking result card
    ├── bar_chart_widget.dart     # Bar chart visualization
    └── loading_overlay.dart      # Loading overlay
```

---

## 👥 Kelompok 2

Teknik Informatika — 2026

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik (Tugas Akhir / Projek Akhir).
