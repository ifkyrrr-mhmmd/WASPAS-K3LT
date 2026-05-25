import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../../providers/waspas_provider.dart';
import '../../../models/alternative_model.dart';
import '../../../config/theme.dart';

class MatrixStep extends StatelessWidget {
  const MatrixStep({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<WaspasProvider>();
    final alternatives = provider.alternatives;
    final criteria = provider.criteria;

    bool hasZeroValues = false;
    for (var alt in alternatives) {
      for (var c in criteria) {
        if ((alt.values[c.id] ?? 0.0) <= 0.0) {
          hasZeroValues = true;
          break;
        }
      }
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        if (hasZeroValues)
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
                Icon(Icons.warning_amber_rounded, color: Colors.red.shade700),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    'Peringatan: Terdapat nilai 0 atau kosong dalam matriks. '
                    'Harap isi semua nilai lebih dari 0 untuk hasil yang akurat.',
                    style: TextStyle(color: Colors.red.shade700, fontSize: 13),
                  ),
                ),
              ],
            ),
          ),
          
        ListView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          itemCount: alternatives.length,
          itemBuilder: (context, altIndex) {
            final alt = alternatives[altIndex];
            return Card(
              key: ValueKey(alt.id),
              margin: const EdgeInsets.only(bottom: 16),
              elevation: 2,
              child: Padding(
                padding: const EdgeInsets.all(12.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        CircleAvatar(
                          radius: 12,
                          backgroundColor: AppColors.teal,
                          child: Text('${altIndex + 1}', style: const TextStyle(fontSize: 12, color: Colors.white)),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: TextFormField(
                            key: ValueKey('${alt.id}_name'),
                            initialValue: alt.name,
                            decoration: const InputDecoration(labelText: 'Nama Alternatif', isDense: true),
                            onChanged: (val) {
                              provider.updateAlternative(altIndex, alt.copyWith(name: val));
                            },
                          ),
                        ),
                        IconButton(
                          icon: const Icon(Icons.delete_outline, color: Colors.red),
                          onPressed: () => provider.removeAlternative(alt.id),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    const Text('Nilai Kriteria:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                    const SizedBox(height: 8),
                    // Wrap criteria inputs
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: criteria.map((c) {
                        return SizedBox(
                          width: MediaQuery.of(context).size.width / 2 - 40,
                          child: TextFormField(
                            key: ValueKey('${alt.id}_${c.id}'),
                            initialValue: (alt.values[c.id] ?? 0.0).toString(),
                            decoration: InputDecoration(
                              labelText: c.name,
                              isDense: true,
                              labelStyle: const TextStyle(fontSize: 12),
                            ),
                            keyboardType: const TextInputType.numberWithOptions(decimal: true),
                            onChanged: (val) {
                              double? parsed = double.tryParse(val);
                              provider.updateAlternativeValue(altIndex, c.id, parsed ?? 0.0);
                            },
                          ),
                        );
                      }).toList(),
                    ),
                  ],
                ),
              ),
            );
          },
        ),
        
        OutlinedButton.icon(
          onPressed: () {
            provider.addAlternative(AlternativeModel(
              id: DateTime.now().millisecondsSinceEpoch.toString(),
              name: 'Alternatif ${alternatives.length + 1}',
              values: {},
            ));
          },
          icon: const Icon(Icons.add),
          label: const Text('Tambah Alternatif'),
          style: OutlinedButton.styleFrom(
            padding: const EdgeInsets.symmetric(vertical: 16),
          ),
        ),
      ],
    );
  }
}
