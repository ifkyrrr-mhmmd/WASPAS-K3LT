import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../../providers/waspas_provider.dart';
import '../../../models/criteria_model.dart';
import '../../../config/theme.dart';

class CriteriaStep extends StatelessWidget {
  const CriteriaStep({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<WaspasProvider>();
    final criteria = provider.criteria;

    double totalWeight = criteria.fold(0, (sum, item) => sum + item.weight);
    bool isWeightValid = (totalWeight - 1.0).abs() < 0.005;
    
    int benefitCount = criteria.where((c) => c.type == 'Benefit').length;
    int costCount = criteria.where((c) => c.type == 'Cost').length;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        // Validation Pills
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: [
            Chip(
              label: Text('Total Bobot: ${totalWeight.toStringAsFixed(4)}'),
              backgroundColor: isWeightValid ? AppColors.mint.withOpacity(0.2) : Colors.red.withOpacity(0.2),
              labelStyle: TextStyle(
                color: isWeightValid ? AppColors.mintDark : Colors.red,
                fontWeight: FontWeight.bold,
              ),
            ),
            Chip(
              label: Text('Benefit: $benefitCount'),
              backgroundColor: AppColors.teal.withOpacity(0.1),
            ),
            Chip(
              label: Text('Cost: $costCount'),
              backgroundColor: AppColors.teal.withOpacity(0.1),
            ),
          ],
        ),
        const SizedBox(height: 16),
        
        ListView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          itemCount: criteria.length,
          itemBuilder: (context, index) {
            final c = criteria[index];
            return Card(
              key: ValueKey(c.id),
              margin: const EdgeInsets.only(bottom: 12),
              elevation: 2,
              child: Padding(
                padding: const EdgeInsets.all(12.0),
                child: Column(
                  children: [
                    Row(
                      children: [
                        CircleAvatar(
                          radius: 12,
                          backgroundColor: AppColors.deep,
                          child: Text('${index + 1}', style: const TextStyle(fontSize: 12, color: Colors.white)),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: TextFormField(
                            key: ValueKey('${c.id}_name'),
                            initialValue: c.name,
                            decoration: const InputDecoration(labelText: 'Nama Kriteria', isDense: true),
                            onChanged: (val) {
                              final updated = c.copyWith(name: val);
                              provider.updateCriteria(index, updated);
                            },
                          ),
                        ),
                        IconButton(
                          icon: const Icon(Icons.delete_outline, color: Colors.red),
                          onPressed: () => provider.removeCriteria(c.id),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Row(
                      children: [
                        Expanded(
                          child: DropdownButtonFormField<String>(
                            key: ValueKey('${c.id}_type'),
                            value: c.type,
                            decoration: const InputDecoration(labelText: 'Tipe', isDense: true),
                            items: ['Benefit', 'Cost'].map((String value) {
                              return DropdownMenuItem<String>(
                                value: value,
                                child: Text(value),
                              );
                            }).toList(),
                            onChanged: (val) {
                              if (val != null) {
                                provider.updateCriteria(index, c.copyWith(type: val));
                              }
                            },
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: TextFormField(
                            key: ValueKey('${c.id}_weight'),
                            initialValue: c.weight.toString(),
                            decoration: const InputDecoration(labelText: 'Bobot (0-1)', isDense: true),
                            keyboardType: const TextInputType.numberWithOptions(decimal: true),
                            onChanged: (val) {
                              double? weight = double.tryParse(val);
                              if (weight != null) {
                                provider.updateCriteria(index, c.copyWith(weight: weight));
                              }
                            },
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            );
          },
        ),
        
        OutlinedButton.icon(
          onPressed: () {
            provider.addCriteria(CriteriaModel(
              id: DateTime.now().millisecondsSinceEpoch.toString(),
              name: 'Kriteria ${criteria.length + 1}',
              type: 'Benefit',
              weight: 0.0,
            ));
          },
          icon: const Icon(Icons.add),
          label: const Text('Tambah Kriteria'),
          style: OutlinedButton.styleFrom(
            padding: const EdgeInsets.symmetric(vertical: 16),
          ),
        ),
      ],
    );
  }
}
