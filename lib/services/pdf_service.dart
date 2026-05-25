/// PDF report generation service for SPK WASPAS K3LT.
///
/// Generates a professional-quality PDF document containing the full
/// WASPAS calculation report — criteria weights, decision matrix,
/// normalised matrix, Q1/Q2/Qi scores, and final rankings — with the
/// app's branded colour scheme.
library;

import 'dart:typed_data';

import 'package:intl/intl.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';

import '../models/calculation_result.dart';

/// Service responsible for building and sharing PDF reports.
class PdfService {
  // ---------------------------------------------------------------------------
  // Brand colours (converted from hex to PdfColor)
  // ---------------------------------------------------------------------------

  static const PdfColor _deep = PdfColor.fromInt(0xFF281C59);
  static const PdfColor _teal = PdfColor.fromInt(0xFF4E8D9C);
  static const PdfColor _mint = PdfColor.fromInt(0xFF85C79A);
  static const PdfColor _white = PdfColor.fromInt(0xFFFFFFFF);
  static const PdfColor _lightGrey = PdfColor.fromInt(0xFFF5F5F5);
  static const PdfColor _darkText = PdfColor.fromInt(0xFF212121);

  // ---------------------------------------------------------------------------
  // Public API
  // ---------------------------------------------------------------------------

  /// Generates a complete PDF report from [result] and returns the raw bytes.
  ///
  /// The returned [Uint8List] can be written to a file, shared, or printed.
  Future<Uint8List> generateReport(CalculationResult result) async {
    final pdf = pw.Document(
      title: 'Laporan WASPAS — ${result.title}',
      author: 'SPK WASPAS K3LT',
      creator: 'SPK WASPAS K3LT Flutter App',
    );

    final dateFormatter = DateFormat('dd MMMM yyyy, HH:mm', 'id_ID');
    final formattedDate = dateFormatter.format(result.createdAt);
    final numberFormat = NumberFormat('#,##0.####', 'id_ID');

    // Pre-compute data for tables.
    final criteria = result.criteria;
    final alternatives = result.alternatives;
    final normalised = result.normalizedMatrix;
    final rankings = result.rankings..sort((a, b) => a.rank.compareTo(b.rank));
    final bestRanking = rankings.first;
    final bestName = alternatives[bestRanking.alternativeIndex].name;

    pdf.addPage(
      pw.MultiPage(
        pageFormat: PdfPageFormat.a4,
        margin: const pw.EdgeInsets.all(40),
        header: (context) => _buildPageHeader(formattedDate),
        footer: (context) => _buildPageFooter(formattedDate, context),
        build: (context) => [
          // 1 – Title block
          _buildTitleBlock(),
          pw.SizedBox(height: 8),
          _buildSubHeader(),
          pw.SizedBox(height: 6),
          _buildLambdaInfo(result.lambda),
          pw.SizedBox(height: 16),

          // 2 – Kriteria & Bobot
          _sectionTitle('1. Kriteria dan Bobot'),
          _buildCriteriaTable(criteria),
          pw.SizedBox(height: 16),

          // 3 – Matriks Keputusan
          _sectionTitle('2. Matriks Keputusan'),
          _buildDecisionMatrix(criteria, alternatives),
          pw.SizedBox(height: 16),

          // 4 – Matriks Ternormalisasi
          _sectionTitle('3. Matriks Ternormalisasi'),
          _buildNormalisedMatrix(criteria, alternatives, normalised, numberFormat),
          pw.SizedBox(height: 16),

          // 5 – Detail Q1 & Q2
          _sectionTitle('4. Detail Q1 (SAW) dan Q2 (WP)'),
          _buildQDetailTable(alternatives, rankings, numberFormat),
          pw.SizedBox(height: 16),

          // 6 – Hasil Perangkingan
          _sectionTitle('5. Hasil Perangkingan'),
          _buildRankingTable(alternatives, rankings, numberFormat),
          pw.SizedBox(height: 20),

          // 7 – Conclusion
          _buildConclusionBox(bestName, bestRanking.qi, numberFormat),
        ],
      ),
    );

    return pdf.save();
  }

  /// Generates the PDF and opens the print preview / save dialog.
  Future<void> sharePdf(CalculationResult result) async {
    await Printing.layoutPdf(
      onLayout: (PdfPageFormat format) async {
        return await generateReport(result);
      },
      name: 'Laporan_WASPAS_K3LT_${DateFormat('yyyyMMdd_HHmmss').format(result.createdAt)}',
    );
  }

  // ---------------------------------------------------------------------------
  // Header / Footer
  // ---------------------------------------------------------------------------

  pw.Widget _buildPageHeader(String date) {
    return pw.Container(
      margin: const pw.EdgeInsets.only(bottom: 12),
      padding: const pw.EdgeInsets.only(bottom: 8),
      decoration: const pw.BoxDecoration(
        border: pw.Border(
          bottom: pw.BorderSide(color: _teal, width: 2),
        ),
      ),
      child: pw.Row(
        mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
        children: [
          pw.Text(
            'SPK WASPAS K3LT',
            style: pw.TextStyle(
              fontSize: 10,
              color: _deep,
              fontWeight: pw.FontWeight.bold,
            ),
          ),
          pw.Text(
            date,
            style: const pw.TextStyle(fontSize: 9, color: _teal),
          ),
        ],
      ),
    );
  }

  pw.Widget _buildPageFooter(String date, pw.Context context) {
    return pw.Container(
      margin: const pw.EdgeInsets.only(top: 12),
      padding: const pw.EdgeInsets.only(top: 8),
      decoration: const pw.BoxDecoration(
        border: pw.Border(
          top: pw.BorderSide(color: _teal, width: 1),
        ),
      ),
      child: pw.Row(
        mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
        children: [
          pw.Text(
            'Dibuat menggunakan SPK WASPAS K3LT — $date',
            style: const pw.TextStyle(fontSize: 8, color: _teal),
          ),
          pw.Text(
            'Halaman ${context.pageNumber} / ${context.pagesCount}',
            style: const pw.TextStyle(fontSize: 8, color: _teal),
          ),
        ],
      ),
    );
  }

  // ---------------------------------------------------------------------------
  // Title / Sub-header / Lambda
  // ---------------------------------------------------------------------------

  pw.Widget _buildTitleBlock() {
    return pw.Container(
      width: double.infinity,
      padding: const pw.EdgeInsets.symmetric(vertical: 14, horizontal: 16),
      decoration: pw.BoxDecoration(
        color: _deep,
        borderRadius: pw.BorderRadius.circular(6),
      ),
      child: pw.Text(
        'Laporan Hasil Seleksi Kepala Divisi K3LT',
        textAlign: pw.TextAlign.center,
        style: pw.TextStyle(
          fontSize: 16,
          fontWeight: pw.FontWeight.bold,
          color: _white,
        ),
      ),
    );
  }

  pw.Widget _buildSubHeader() {
    return pw.Text(
      'Metode WASPAS (Weighted Aggregated Sum Product Assessment)',
      textAlign: pw.TextAlign.center,
      style: pw.TextStyle(
        fontSize: 11,
        fontWeight: pw.FontWeight.bold,
        color: _teal,
      ),
    );
  }

  pw.Widget _buildLambdaInfo(double lambda) {
    return pw.Container(
      padding: const pw.EdgeInsets.symmetric(vertical: 6, horizontal: 12),
      decoration: pw.BoxDecoration(
        color: _lightGrey,
        borderRadius: pw.BorderRadius.circular(4),
        border: pw.Border.all(color: _teal, width: 0.5),
      ),
      child: pw.RichText(
        textAlign: pw.TextAlign.center,
        text: pw.TextSpan(
          children: [
            pw.TextSpan(
              text: 'Nilai Lambda (λ): ',
              style: pw.TextStyle(
                fontSize: 10,
                fontWeight: pw.FontWeight.bold,
                color: _darkText,
              ),
            ),
            pw.TextSpan(
              text: lambda.toStringAsFixed(2),
              style: const pw.TextStyle(fontSize: 10, color: _teal),
            ),
          ],
        ),
      ),
    );
  }

  // ---------------------------------------------------------------------------
  // Section Titles
  // ---------------------------------------------------------------------------

  pw.Widget _sectionTitle(String title) {
    return pw.Padding(
      padding: const pw.EdgeInsets.only(bottom: 6),
      child: pw.Text(
        title,
        style: pw.TextStyle(
          fontSize: 12,
          fontWeight: pw.FontWeight.bold,
          color: _deep,
        ),
      ),
    );
  }

  // ---------------------------------------------------------------------------
  // Table helpers
  // ---------------------------------------------------------------------------

  pw.TextStyle get _headerTextStyle => pw.TextStyle(
        fontSize: 9,
        fontWeight: pw.FontWeight.bold,
        color: _white,
      );

  pw.TextStyle get _cellTextStyle => const pw.TextStyle(
        fontSize: 9,
        color: _darkText,
      );

  pw.BoxDecoration get _headerDecoration => const pw.BoxDecoration(
        color: _teal,
      );



  // ---------------------------------------------------------------------------
  // 1. Kriteria & Bobot table
  // ---------------------------------------------------------------------------

  pw.Widget _buildCriteriaTable(List criteria) {
    return pw.TableHelper.fromTextArray(
      border: pw.TableBorder.all(color: PdfColors.grey400, width: 0.5),
      headerDecoration: _headerDecoration,
      headerStyle: _headerTextStyle,
      cellStyle: _cellTextStyle,
      cellAlignment: pw.Alignment.center,
      headerAlignment: pw.Alignment.center,
      headers: ['No', 'Kode', 'Nama Kriteria', 'Tipe', 'Bobot'],
      data: List.generate(criteria.length, (i) {
        final c = criteria[i];
        return [
          '${i + 1}',
          'C${i + 1}',
          c.name,
          c.isBenefit ? 'Benefit' : 'Cost',
          c.weight.toStringAsFixed(3),
        ];
      }),
      oddRowDecoration: const pw.BoxDecoration(color: _lightGrey),
    );
  }

  // ---------------------------------------------------------------------------
  // 2. Matriks Keputusan
  // ---------------------------------------------------------------------------

  pw.Widget _buildDecisionMatrix(List criteria, List alternatives) {
    final headers = ['Alternatif', ...List.generate(criteria.length, (i) => 'C${i + 1}')];
    final data = List.generate(alternatives.length, (i) {
      final alt = alternatives[i];
      return [
        alt.name,
        ...List.generate(criteria.length, (j) {
          final value = alt.values[criteria[j].id] ?? 0.0;
          // Show as integer if it is a whole number.
          return value == value.roundToDouble()
              ? value.toInt().toString()
              : value.toStringAsFixed(2);
        }),
      ];
    });

    return pw.TableHelper.fromTextArray(
      border: pw.TableBorder.all(color: PdfColors.grey400, width: 0.5),
      headerDecoration: _headerDecoration,
      headerStyle: _headerTextStyle,
      cellStyle: _cellTextStyle,
      cellAlignment: pw.Alignment.center,
      headerAlignment: pw.Alignment.center,
      headers: headers,
      data: data,
      oddRowDecoration: const pw.BoxDecoration(color: _lightGrey),
    );
  }

  // ---------------------------------------------------------------------------
  // 3. Matriks Ternormalisasi
  // ---------------------------------------------------------------------------

  pw.Widget _buildNormalisedMatrix(
    List criteria,
    List alternatives,
    List<List<double>> normalised,
    NumberFormat nf,
  ) {
    final headers = ['Alternatif', ...List.generate(criteria.length, (i) => 'C${i + 1}')];
    final data = List.generate(alternatives.length, (i) {
      return [
        alternatives[i].name,
        ...List.generate(criteria.length, (j) => nf.format(normalised[i][j])),
      ];
    });

    return pw.TableHelper.fromTextArray(
      border: pw.TableBorder.all(color: PdfColors.grey400, width: 0.5),
      headerDecoration: _headerDecoration,
      headerStyle: _headerTextStyle,
      cellStyle: _cellTextStyle,
      cellAlignment: pw.Alignment.center,
      headerAlignment: pw.Alignment.center,
      headers: headers,
      data: data,
      oddRowDecoration: const pw.BoxDecoration(color: _lightGrey),
    );
  }

  // ---------------------------------------------------------------------------
  // 4. Q1 (SAW) & Q2 (WP) detail table
  // ---------------------------------------------------------------------------

  pw.Widget _buildQDetailTable(
    List alternatives,
    List rankings,
    NumberFormat nf,
  ) {
    final headers = ['Alternatif', 'Q1 (SAW)', 'Q2 (WP)'];
    final data = List.generate(rankings.length, (i) {
      final r = rankings[i];
      return [
        alternatives[r.alternativeIndex].name,
        nf.format(r.q1),
        nf.format(r.q2),
      ];
    });

    return pw.TableHelper.fromTextArray(
      border: pw.TableBorder.all(color: PdfColors.grey400, width: 0.5),
      headerDecoration: _headerDecoration,
      headerStyle: _headerTextStyle,
      cellStyle: _cellTextStyle,
      cellAlignment: pw.Alignment.center,
      headerAlignment: pw.Alignment.center,
      headers: headers,
      data: data,
      oddRowDecoration: const pw.BoxDecoration(color: _lightGrey),
    );
  }

  // ---------------------------------------------------------------------------
  // 5. Hasil Perangkingan table
  // ---------------------------------------------------------------------------

  pw.Widget _buildRankingTable(
    List alternatives,
    List rankings,
    NumberFormat nf,
  ) {
    final headers = ['Rank', 'Alternatif', 'Q1 (SAW)', 'Q2 (WP)', 'Qi'];
    final data = List.generate(rankings.length, (i) {
      final r = rankings[i];
      return [
        '${r.rank}',
        alternatives[r.alternativeIndex].name,
        nf.format(r.q1),
        nf.format(r.q2),
        nf.format(r.qi),
      ];
    });

    return pw.TableHelper.fromTextArray(
      border: pw.TableBorder.all(color: PdfColors.grey400, width: 0.5),
      headerDecoration: _headerDecoration,
      headerStyle: _headerTextStyle,
      cellStyle: _cellTextStyle,
      cellAlignment: pw.Alignment.center,
      headerAlignment: pw.Alignment.center,
      headers: headers,
      data: data,
      oddRowDecoration: const pw.BoxDecoration(color: _lightGrey),
    );
  }

  // ---------------------------------------------------------------------------
  // Conclusion box
  // ---------------------------------------------------------------------------

  pw.Widget _buildConclusionBox(
    String bestName,
    double qi,
    NumberFormat nf,
  ) {
    return pw.Container(
      width: double.infinity,
      padding: const pw.EdgeInsets.all(16),
      decoration: pw.BoxDecoration(
        color: const PdfColor.fromInt(0xFFE8F5E9),
        borderRadius: pw.BorderRadius.circular(6),
        border: pw.Border.all(color: _mint, width: 1.5),
      ),
      child: pw.Column(
        children: [
          pw.Text(
            'KESIMPULAN',
            style: pw.TextStyle(
              fontSize: 13,
              fontWeight: pw.FontWeight.bold,
              color: _deep,
            ),
          ),
          pw.SizedBox(height: 8),
          pw.RichText(
            textAlign: pw.TextAlign.center,
            text: pw.TextSpan(
              style: const pw.TextStyle(fontSize: 11, color: _darkText),
              children: [
                const pw.TextSpan(text: 'Alternatif terbaik adalah: '),
                pw.TextSpan(
                  text: bestName,
                  style: pw.TextStyle(
                    fontWeight: pw.FontWeight.bold,
                    color: _teal,
                  ),
                ),
                const pw.TextSpan(text: '\ndengan nilai Qi: '),
                pw.TextSpan(
                  text: nf.format(qi),
                  style: pw.TextStyle(
                    fontWeight: pw.FontWeight.bold,
                    color: _teal,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
