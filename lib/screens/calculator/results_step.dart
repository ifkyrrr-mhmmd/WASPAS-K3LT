import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../../providers/waspas_provider.dart';
import '../../../providers/auth_provider.dart';
import '../../../services/pdf_service.dart';
import '../../../config/theme.dart';
import '../../../widgets/gradient_button.dart';
import '../../../widgets/metric_card.dart';
import '../../../widgets/ranking_card.dart';
import '../../../widgets/bar_chart_widget.dart';
import '../../../models/calculation_result.dart';

class ResultsStep extends StatelessWidget {
  final String title;
  final CalculationResult? resultOverride;

  const ResultsStep({Key? key, required this.title, this.resultOverride}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<WaspasProvider>();
    final authProvider = context.watch<AuthProvider>();
    
    final isHistory = resultOverride != null;
    final errorMsg = isHistory ? null : provider.getValidationError();
    final result = resultOverride ?? provider.lastResult;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        if (errorMsg != null)
          Container(
            margin: const EdgeInsets.only(bottom: 16),
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.red.shade50,
              border: Border.all(color: Colors.red.shade200),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              children: [
                Icon(Icons.error_outline, color: Colors.red.shade700),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    errorMsg,
                    style: TextStyle(color: Colors.red.shade700, fontSize: 13),
                  ),
                ),
              ],
            ),
          ),

        if (!isHistory)
          GradientButton(
            text: 'Hitung WASPAS',
            onPressed: provider.validate()
                ? () {
                    final userId = authProvider.currentUser?.uid ?? 'anonymous';
                    final r = provider.calculate(userId, title);
                    if (r != null) {
                      provider.saveResult(r);
                    }
                  }
                : null,
            isFullWidth: true,
            icon: Icons.calculate,
          ),
        
        if (result != null) ...[
          if (!isHistory) ...[
            const SizedBox(height: 32),
            const Divider(),
          ],
          const SizedBox(height: 16),
          
          IntrinsicHeight(
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Expanded(
                  child: MetricCard(
                    label: 'Terbaik',
                    value: result.alternatives[result.rankings.first.alternativeIndex].name,
                    icon: Icons.emoji_events,
                    color: AppColors.mint,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: MetricCard(
                    label: 'Skor Qi',
                    value: result.rankings.first.qi.toStringAsFixed(4),
                    icon: Icons.score,
                    color: AppColors.teal,
                  ),
                ),
              ],
            ),
          ),
          
          const SizedBox(height: 24),
          const Text('Hasil Perangkingan', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.deep)),
          const SizedBox(height: 12),
          
          ...result.rankings.map((r) {
            return RankingCard(
              rank: r.rank,
              name: result.alternatives[r.alternativeIndex].name,
              q1: r.q1,
              q2: r.q2,
              qi: r.qi,
              maxQi: result.rankings.first.qi,
            );
          }).toList(),

          const SizedBox(height: 24),
          const Text('Visualisasi Skor', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.deep)),
          const SizedBox(height: 12),
          BarChartWidget(
            entries: result.rankings.map((r) => BarChartEntry(
              name: result.alternatives[r.alternativeIndex].name,
              value: r.qi,
            )).toList(),
          ),
          
          const SizedBox(height: 24),
          ExpansionTile(
            title: const Text('Detail Perhitungan (SAW & WP)'),
            children: [
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                child: DataTable(
                  columns: const [
                    DataColumn(label: Text('Alternatif')),
                    DataColumn(label: Text('Q1 (SAW)')),
                    DataColumn(label: Text('Q2 (WP)')),
                  ],
                  rows: result.rankings.map((r) {
                    return DataRow(cells: [
                      DataCell(Text(result.alternatives[r.alternativeIndex].name)),
                      DataCell(Text(r.q1.toStringAsFixed(4))),
                      DataCell(Text(r.q2.toStringAsFixed(4))),
                    ]);
                  }).toList(),
                ),
              ),
            ],
          ),
          
          const SizedBox(height: 24),
          Row(
            children: [
              Expanded(
                child: OutlinedButton.icon(
                  onPressed: () async {
                    final pdfService = PdfService();
                    await pdfService.sharePdf(result);
                  },
                  icon: const Icon(Icons.picture_as_pdf),
                  label: const Text('Export PDF'),
                  style: OutlinedButton.styleFrom(padding: const EdgeInsets.symmetric(vertical: 16)),
                ),
              ),
            ],
          ),
          const SizedBox(height: 32),
        ],
      ],
    );
  }
}
