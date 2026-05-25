import 'package:flutter/material.dart';
import '../../models/calculation_result.dart';
import 'calculator/results_step.dart';

class HistoryDetailScreen extends StatelessWidget {
  final CalculationResult item;

  const HistoryDetailScreen({Key? key, required this.item}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Detail Riwayat'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text(
              item.title,
              style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
            ),
            const SizedBox(height: 8),
            Text(
              'Dibuat pada: ${item.createdAt.toString().split('.')[0]}',
              style: TextStyle(color: Colors.grey.shade600),
            ),
            const SizedBox(height: 24),
            ResultsStep(
              title: item.title,
              resultOverride: item,
            ),
          ],
        ),
      ),
    );
  }
}
