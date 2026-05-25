import '../models/criteria_model.dart';
import '../models/alternative_model.dart';

/// Pre-built K3LT (Keselamatan, Kesehatan Kerja, Lingkungan, dan
/// Transportasi) criteria templates and sample candidate data.
///
/// These templates provide ready-to-use criteria configurations for the
/// SPK Seleksi Kepala Divisi K3LT using the WASPAS method.
class K3ltTemplates {
  K3ltTemplates._();

  // ═══════════════════════════════════════════════════════════════════════
  //  Template Definitions
  // ═══════════════════════════════════════════════════════════════════════

  /// Returns all available template names.
  static List<String> get templateNames => [
        'Template Standar K3LT',
        'Template Ringkas K3LT',
        'Template Lengkap K3LT',
      ];

  /// Returns the criteria list for the given [templateName].
  ///
  /// Throws [ArgumentError] if the template name is not recognized.
  static List<CriteriaModel> getCriteria(String templateName) {
    switch (templateName) {
      case 'Template Standar K3LT':
        return standardCriteria;
      case 'Template Ringkas K3LT':
        return compactCriteria;
      case 'Template Lengkap K3LT':
        return comprehensiveCriteria;
      default:
        throw ArgumentError('Template "$templateName" tidak ditemukan.');
    }
  }

  /// Returns sample alternatives for the given [templateName].
  ///
  /// Throws [ArgumentError] if the template name is not recognized.
  static List<AlternativeModel> getSampleAlternatives(String templateName) {
    switch (templateName) {
      case 'Template Standar K3LT':
        return standardSampleAlternatives;
      case 'Template Ringkas K3LT':
        return compactSampleAlternatives;
      case 'Template Lengkap K3LT':
        return comprehensiveSampleAlternatives;
      default:
        throw ArgumentError('Template "$templateName" tidak ditemukan.');
    }
  }

  // ═══════════════════════════════════════════════════════════════════════
  //  Template 1: Standar K3LT (7 criteria, weights sum = 1.0)
  // ═══════════════════════════════════════════════════════════════════════

  /// Standard K3LT template with 7 balanced criteria.
  static List<CriteriaModel> get standardCriteria => const [
        CriteriaModel(
          id: 'std_c1',
          name: 'Pengalaman K3LT (tahun)',
          type: 'Benefit',
          weight: 0.20,
        ),
        CriteriaModel(
          id: 'std_c2',
          name: 'Sertifikasi K3 (jumlah)',
          type: 'Benefit',
          weight: 0.15,
        ),
        CriteriaModel(
          id: 'std_c3',
          name: 'Tingkat Pendidikan',
          type: 'Benefit',
          weight: 0.15,
        ),
        CriteriaModel(
          id: 'std_c4',
          name: 'Insiden yang Ditangani',
          type: 'Benefit',
          weight: 0.20,
        ),
        CriteriaModel(
          id: 'std_c5',
          name: 'Pelanggaran Safety',
          type: 'Cost',
          weight: 0.10,
        ),
        CriteriaModel(
          id: 'std_c6',
          name: 'Biaya Training (juta)',
          type: 'Cost',
          weight: 0.10,
        ),
        CriteriaModel(
          id: 'std_c7',
          name: 'Assessment Leadership',
          type: 'Benefit',
          weight: 0.10,
        ),
      ];

  /// Sample candidates for the standard template.
  static List<AlternativeModel> get standardSampleAlternatives => const [
        AlternativeModel(
          id: 'std_a1',
          name: 'Budi Santoso',
          values: {
            'std_c1': 12,
            'std_c2': 5,
            'std_c3': 4, // 4 = S2
            'std_c4': 45,
            'std_c5': 2,
            'std_c6': 15,
            'std_c7': 85,
          },
        ),
        AlternativeModel(
          id: 'std_a2',
          name: 'Siti Rahayu',
          values: {
            'std_c1': 8,
            'std_c2': 7,
            'std_c3': 5, // 5 = S3
            'std_c4': 30,
            'std_c5': 1,
            'std_c6': 20,
            'std_c7': 90,
          },
        ),
        AlternativeModel(
          id: 'std_a3',
          name: 'Ahmad Hidayat',
          values: {
            'std_c1': 15,
            'std_c2': 4,
            'std_c3': 3, // 3 = S1
            'std_c4': 60,
            'std_c5': 3,
            'std_c6': 10,
            'std_c7': 78,
          },
        ),
        AlternativeModel(
          id: 'std_a4',
          name: 'Dewi Lestari',
          values: {
            'std_c1': 10,
            'std_c2': 6,
            'std_c3': 4,
            'std_c4': 35,
            'std_c5': 1,
            'std_c6': 18,
            'std_c7': 88,
          },
        ),
        AlternativeModel(
          id: 'std_a5',
          name: 'Rudi Hermawan',
          values: {
            'std_c1': 6,
            'std_c2': 3,
            'std_c3': 3,
            'std_c4': 20,
            'std_c5': 4,
            'std_c6': 12,
            'std_c7': 72,
          },
        ),
      ];

  // ═══════════════════════════════════════════════════════════════════════
  //  Template 2: Ringkas K3LT (4 criteria, weights sum = 1.0)
  // ═══════════════════════════════════════════════════════════════════════

  /// Compact K3LT template with 4 high-level criteria.
  static List<CriteriaModel> get compactCriteria => const [
        CriteriaModel(
          id: 'cmp_c1',
          name: 'Kompetensi Teknis',
          type: 'Benefit',
          weight: 0.30,
        ),
        CriteriaModel(
          id: 'cmp_c2',
          name: 'Pengalaman Manajerial',
          type: 'Benefit',
          weight: 0.25,
        ),
        CriteriaModel(
          id: 'cmp_c3',
          name: 'Track Record K3',
          type: 'Benefit',
          weight: 0.25,
        ),
        CriteriaModel(
          id: 'cmp_c4',
          name: 'Pelanggaran',
          type: 'Cost',
          weight: 0.20,
        ),
      ];

  /// Sample candidates for the compact template.
  static List<AlternativeModel> get compactSampleAlternatives => const [
        AlternativeModel(
          id: 'cmp_a1',
          name: 'Andi Pratama',
          values: {
            'cmp_c1': 85,
            'cmp_c2': 7,
            'cmp_c3': 90,
            'cmp_c4': 2,
          },
        ),
        AlternativeModel(
          id: 'cmp_a2',
          name: 'Rina Wulandari',
          values: {
            'cmp_c1': 92,
            'cmp_c2': 5,
            'cmp_c3': 85,
            'cmp_c4': 1,
          },
        ),
        AlternativeModel(
          id: 'cmp_a3',
          name: 'Hendra Gunawan',
          values: {
            'cmp_c1': 78,
            'cmp_c2': 10,
            'cmp_c3': 88,
            'cmp_c4': 3,
          },
        ),
      ];

  // ═══════════════════════════════════════════════════════════════════════
  //  Template 3: Lengkap K3LT (10 criteria, weights sum = 1.0)
  // ═══════════════════════════════════════════════════════════════════════

  /// Comprehensive K3LT template with 10 detailed criteria.
  static List<CriteriaModel> get comprehensiveCriteria => const [
        CriteriaModel(
          id: 'cpr_c1',
          name: 'Pengalaman K3LT (tahun)',
          type: 'Benefit',
          weight: 0.12,
        ),
        CriteriaModel(
          id: 'cpr_c2',
          name: 'Sertifikasi K3',
          type: 'Benefit',
          weight: 0.10,
        ),
        CriteriaModel(
          id: 'cpr_c3',
          name: 'Pendidikan Terakhir',
          type: 'Benefit',
          weight: 0.10,
        ),
        CriteriaModel(
          id: 'cpr_c4',
          name: 'Insiden Ditangani',
          type: 'Benefit',
          weight: 0.12,
        ),
        CriteriaModel(
          id: 'cpr_c5',
          name: 'Pelanggaran Safety',
          type: 'Cost',
          weight: 0.08,
        ),
        CriteriaModel(
          id: 'cpr_c6',
          name: 'Biaya Training',
          type: 'Cost',
          weight: 0.08,
        ),
        CriteriaModel(
          id: 'cpr_c7',
          name: 'Leadership Score',
          type: 'Benefit',
          weight: 0.10,
        ),
        CriteriaModel(
          id: 'cpr_c8',
          name: 'Komunikasi',
          type: 'Benefit',
          weight: 0.10,
        ),
        CriteriaModel(
          id: 'cpr_c9',
          name: 'Inovasi & Improvement',
          type: 'Benefit',
          weight: 0.10,
        ),
        CriteriaModel(
          id: 'cpr_c10',
          name: 'Kehadiran & Disiplin',
          type: 'Benefit',
          weight: 0.10,
        ),
      ];

  /// Sample candidates for the comprehensive template.
  static List<AlternativeModel> get comprehensiveSampleAlternatives => const [
        AlternativeModel(
          id: 'cpr_a1',
          name: 'Fajar Nugroho',
          values: {
            'cpr_c1': 14,
            'cpr_c2': 6,
            'cpr_c3': 4,
            'cpr_c4': 50,
            'cpr_c5': 1,
            'cpr_c6': 12,
            'cpr_c7': 88,
            'cpr_c8': 82,
            'cpr_c9': 75,
            'cpr_c10': 95,
          },
        ),
        AlternativeModel(
          id: 'cpr_a2',
          name: 'Maya Sari',
          values: {
            'cpr_c1': 9,
            'cpr_c2': 8,
            'cpr_c3': 5,
            'cpr_c4': 35,
            'cpr_c5': 1,
            'cpr_c6': 22,
            'cpr_c7': 92,
            'cpr_c8': 90,
            'cpr_c9': 88,
            'cpr_c10': 90,
          },
        ),
        AlternativeModel(
          id: 'cpr_a3',
          name: 'Doni Setiawan',
          values: {
            'cpr_c1': 11,
            'cpr_c2': 5,
            'cpr_c3': 3,
            'cpr_c4': 42,
            'cpr_c5': 3,
            'cpr_c6': 8,
            'cpr_c7': 80,
            'cpr_c8': 78,
            'cpr_c9': 82,
            'cpr_c10': 88,
          },
        ),
        AlternativeModel(
          id: 'cpr_a4',
          name: 'Linda Permata',
          values: {
            'cpr_c1': 7,
            'cpr_c2': 4,
            'cpr_c3': 4,
            'cpr_c4': 28,
            'cpr_c5': 2,
            'cpr_c6': 15,
            'cpr_c7': 85,
            'cpr_c8': 88,
            'cpr_c9': 90,
            'cpr_c10': 92,
          },
        ),
        AlternativeModel(
          id: 'cpr_a5',
          name: 'Eko Prasetyo',
          values: {
            'cpr_c1': 16,
            'cpr_c2': 7,
            'cpr_c3': 3,
            'cpr_c4': 55,
            'cpr_c5': 2,
            'cpr_c6': 10,
            'cpr_c7': 76,
            'cpr_c8': 74,
            'cpr_c9': 70,
            'cpr_c10': 85,
          },
        ),
      ];
}
