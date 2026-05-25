import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/waspas_provider.dart';
import '../utils/sensitivity_analysis.dart';
import '../config/theme.dart';
import '../models/calculation_result.dart';

class SensitivityScreen extends StatefulWidget {
  const SensitivityScreen({Key? key}) : super(key: key);

  @override
  State<SensitivityScreen> createState() => _SensitivityScreenState();
}

class _SensitivityScreenState extends State<SensitivityScreen> {
  int _selectedCriteriaIndex = 0;
  String? _selectedHistoryId;

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<WaspasProvider>();
    
    CalculationResult? selectedResult;
    if (_selectedHistoryId == 'active') {
      selectedResult = provider.lastResult;
    } else if (_selectedHistoryId != null) {
      try {
        selectedResult = provider.history.firstWhere((e) => e.id == _selectedHistoryId);
      } catch (_) {
        selectedResult = provider.lastResult;
      }
    } else {
      selectedResult = provider.lastResult ?? (provider.history.isNotEmpty ? provider.history.first : null);
      if (selectedResult != null) {
        // Just set the initial value silently to avoid build-during-build issues
        _selectedHistoryId = selectedResult.id == provider.lastResult?.id ? 'active' : selectedResult.id;
      }
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Analisis Sensitivitas Kriteria'),
        backgroundColor: AppColors.deep,
      ),
      body: selectedResult == null
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.analytics_outlined, size: 80, color: Colors.grey.shade300),
                  const SizedBox(height: 16),
                  const Text(
                    'Belum ada data untuk dianalisis.\nLakukan perhitungan WASPAS terlebih dahulu.',
                    textAlign: TextAlign.center,
                    style: TextStyle(color: AppColors.textMuted),
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: () => Navigator.pushReplacementNamed(context, '/calculator'),
                    child: const Text('Ke Kalkulator'),
                  ),
                ],
              ),
            )
          : Column(
              children: [
                if (provider.lastResult != null || provider.history.isNotEmpty)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                    color: Colors.white,
                    child: DropdownButtonFormField<String>(
                      isExpanded: true,
                      decoration: const InputDecoration(
                        labelText: 'Sumber Data Analisis',
                        border: OutlineInputBorder(),
                        isDense: true,
                      ),
                      value: _selectedHistoryId ?? (provider.lastResult != null ? 'active' : null),
                      items: [
                        if (provider.lastResult != null)
                          const DropdownMenuItem(
                            value: 'active',
                            child: Text('Perhitungan Saat Ini (Aktif)'),
                          ),
                        ...provider.history.map((h) => DropdownMenuItem(
                          value: h.id,
                          child: Text('Riwayat: ${h.title} (${h.createdAt.toString().split('.')[0]})'),
                        )),
                      ],
                      onChanged: (val) {
                        if (val != null) {
                          setState(() {
                            _selectedHistoryId = val;
                            _selectedCriteriaIndex = 0; // reset index
                          });
                        }
                      },
                    ),
                  ),
                Expanded(child: _buildWeightAnalysis(selectedResult, provider.lambda)),
              ],
            ),
    );
  }

  Widget _buildWeightAnalysis(CalculationResult result, double lambda) {
    if (result.criteria.isEmpty) return const SizedBox.shrink();

    final matrix = result.alternatives.map((a) {
      return result.criteria.map((c) => a.values[c.id] ?? 0.0).toList();
    }).toList();
    final weights = result.criteria.map((c) => c.weight).toList();
    final types = result.criteria.map((c) => c.type).toList();

    // Pastikan index yang dipilih valid
    if (_selectedCriteriaIndex >= result.criteria.length) {
      _selectedCriteriaIndex = 0;
    }

    final results = SensitivityAnalysis.analyzeWeight(
      matrix, weights, types, _selectedCriteriaIndex, lambda: lambda, steps: 10
    );

    // Filter kapan peringkat 1 berubah
    final List<Widget> summaryCards = [];
    final baselineTopIndex = results.first.topRankedIndex;
    int currentTopIndex = baselineTopIndex;

    for (var r in results) {
      if (r.topRankedIndex != currentTopIndex) {
        currentTopIndex = r.topRankedIndex;
        final candidateName = result.alternatives[currentTopIndex].name;
        final weightPercent = (r.parameterValue * 100).toStringAsFixed(0);
        
        summaryCards.add(
          Card(
            margin: const EdgeInsets.only(bottom: 8),
            color: Colors.orange.shade50,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8),
              side: BorderSide(color: Colors.orange.shade200),
            ),
            child: ListTile(
              leading: const Icon(Icons.swap_horiz_rounded, color: Colors.orange),
              title: Text('Jika bobot mencapai $weightPercent%'),
              subtitle: Text(
                'Peringkat 1 akan diambil alih oleh $candidateName',
                style: const TextStyle(fontWeight: FontWeight.bold),
              ),
            ),
          )
        );
      }
    }

    final originalWeightPercent = (weights[_selectedCriteriaIndex] * 100).toStringAsFixed(1);
    final isStable = summaryCards.isEmpty;

    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: AppColors.teal.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: AppColors.teal.withValues(alpha: 0.3)),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Row(
                children: [
                  Icon(Icons.info_outline, color: AppColors.teal),
                  SizedBox(width: 8),
                  Text('Apa itu Analisis Sensitivitas?', style: TextStyle(fontWeight: FontWeight.bold, color: AppColors.deep)),
                ],
              ),
              const SizedBox(height: 8),
              const Text(
                'Fitur ini membantu Anda melihat seberapa stabil hasil keputusan. Jika kita mengubah bobot dari salah satu kriteria, apakah kandidat terbaik (Peringkat 1) akan berubah?',
                style: TextStyle(color: AppColors.textSecondary, height: 1.4, fontSize: 13),
              ),
            ],
          ),
        ),
        
        const SizedBox(height: 24),
        const Text('Pilih Kriteria untuk Dianalisis:', style: TextStyle(fontWeight: FontWeight.bold)),
        const SizedBox(height: 12),
        DropdownButtonFormField<int>(
          decoration: const InputDecoration(border: OutlineInputBorder()),
          value: _selectedCriteriaIndex,
          items: List.generate(result.criteria.length, (index) {
            return DropdownMenuItem(
              value: index,
              child: Text(result.criteria[index].name),
            );
          }),
          onChanged: (val) {
            if (val != null) {
              setState(() => _selectedCriteriaIndex = val);
            }
          },
        ),

        const SizedBox(height: 32),
        const Text('Hasil Analisis:', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.deep)),
        const SizedBox(height: 12),
        
        Text(
          'Bobot asli kriteria ini adalah $originalWeightPercent%.',
          style: const TextStyle(fontSize: 14),
        ),
        const SizedBox(height: 16),
        
        if (isStable)
          Card(
            color: AppColors.mint.withValues(alpha: 0.1),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8),
              side: BorderSide(color: AppColors.mintDark.withValues(alpha: 0.5)),
            ),
            child: const Padding(
              padding: EdgeInsets.all(16.0),
              child: Row(
                children: [
                  Icon(Icons.check_circle_outline, color: AppColors.mintDark, size: 32),
                  SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      'Sangat Stabil!\nKandidat Peringkat 1 tidak akan berubah meskipun bobot kriteria ini diubah-ubah dari 0% hingga 100%.',
                      style: TextStyle(color: AppColors.mintDark, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
            ),
          )
        else
          Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: summaryCards,
          ),
          
        const SizedBox(height: 32),
        ExpansionTile(
          title: const Text('Tampilkan Tabel Detail', style: TextStyle(fontSize: 14)),
          children: [
            SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: DataTable(
                headingRowColor: MaterialStateProperty.all(AppColors.teal.withOpacity(0.1)),
                columns: const [
                  DataColumn(label: Text('Simulasi Bobot')),
                  DataColumn(label: Text('Peringkat 1')),
                  DataColumn(label: Text('Peringkat 2')),
                  DataColumn(label: Text('Peringkat 3')),
                ],
                rows: results.map((r) {
                  return DataRow(
                    color: MaterialStateProperty.all(
                      r.topRankChanged ? Colors.orange.withOpacity(0.1) : null
                    ),
                    cells: [
                      DataCell(Text('${(r.parameterValue * 100).toStringAsFixed(1)}%', style: const TextStyle(fontWeight: FontWeight.bold))),
                      DataCell(Text(result.alternatives[r.rankings[0].alternativeIndex].name)),
                      DataCell(Text(r.rankings.length > 1 ? result.alternatives[r.rankings[1].alternativeIndex].name : '-')),
                      DataCell(Text(r.rankings.length > 2 ? result.alternatives[r.rankings[2].alternativeIndex].name : '-')),
                    ],
                  );
                }).toList(),
              ),
            ),
          ]
        ),
      ],
    );
  }
}
