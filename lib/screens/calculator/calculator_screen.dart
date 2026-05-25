import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/waspas_provider.dart';
import '../../config/theme.dart';
import 'criteria_step.dart';
import 'matrix_step.dart';
import 'results_step.dart';

class CalculatorScreen extends StatefulWidget {
  const CalculatorScreen({Key? key}) : super(key: key);

  @override
  State<CalculatorScreen> createState() => _CalculatorScreenState();
}

class _CalculatorScreenState extends State<CalculatorScreen> {
  int _currentStep = 0;
  final TextEditingController _titleController = TextEditingController(text: "Seleksi Kepala Divisi K3LT");

  @override
  void dispose() {
    _titleController.dispose();
    super.dispose();
  }

  bool _canNavigateTo(int targetStep, WaspasProvider provider) {
    if (targetStep == 0) return true;
    
    if (targetStep >= 1) {
      if (provider.criteria.isEmpty) return false;
      double totalWeight = provider.criteria.fold(0.0, (sum, c) => sum + c.weight);
      if ((totalWeight - 1.0).abs() > 0.005) return false;
    }
    
    if (targetStep >= 2) {
      if (provider.alternatives.isEmpty) return false;
      for (var alt in provider.alternatives) {
        if (alt.name.trim().isEmpty) return false;
        for (var c in provider.criteria) {
          if ((alt.values[c.id] ?? 0) <= 0) return false;
        }
      }
    }
    
    return true;
  }

  void _showTemplatePicker() {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return SafeArea(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Padding(
                padding: EdgeInsets.all(16.0),
                child: Text(
                  'Pilih Template Kriteria K3LT',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
              ),
              ListTile(
                leading: const Icon(Icons.assignment),
                title: const Text('Template Standar K3LT'),
                onTap: () {
                  context.read<WaspasProvider>().loadTemplate('Template Standar K3LT');
                  Navigator.pop(context);
                },
              ),
              ListTile(
                leading: const Icon(Icons.assignment_ind),
                title: const Text('Template Ringkas K3LT'),
                onTap: () {
                  context.read<WaspasProvider>().loadTemplate('Template Ringkas K3LT');
                  Navigator.pop(context);
                },
              ),
              ListTile(
                leading: const Icon(Icons.assignment_turned_in),
                title: const Text('Template Lengkap K3LT'),
                onTap: () {
                  context.read<WaspasProvider>().loadTemplate('Template Lengkap K3LT');
                  Navigator.pop(context);
                },
              ),
            ],
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<WaspasProvider>();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Kalkulator WASPAS'),
        backgroundColor: AppColors.deep,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            tooltip: 'Reset Data',
            onPressed: () {
              showDialog(
                context: context,
                builder: (context) => AlertDialog(
                  title: const Text('Reset Data?'),
                  content: const Text('Semua input kriteria dan alternatif akan dihapus.'),
                  actions: [
                    TextButton(
                      onPressed: () => Navigator.pop(context),
                      child: const Text('Batal'),
                    ),
                    TextButton(
                      onPressed: () {
                        provider.reset();
                        Navigator.pop(context);
                        setState(() => _currentStep = 0);
                      },
                      child: const Text('Reset', style: TextStyle(color: Colors.red)),
                    ),
                  ],
                ),
              );
            },
          ),
          IconButton(
            icon: const Icon(Icons.auto_awesome),
            tooltip: 'Template K3LT',
            onPressed: _showTemplatePicker,
          ),
        ],
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.white,
            child: Column(
              children: [
                TextField(
                  controller: _titleController,
                  decoration: const InputDecoration(
                    labelText: 'Judul Seleksi',
                    border: OutlineInputBorder(),
                    prefixIcon: Icon(Icons.title),
                  ),
                ),

              ],
            ),
          ),
          Expanded(
            child: Stepper(
              type: StepperType.horizontal,
              currentStep: _currentStep,
              onStepTapped: (step) {
                if (_canNavigateTo(step, provider)) {
                  setState(() => _currentStep = step);
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Harap selesaikan kriteria terlebih dahulu dengan total bobot kriteria wajib bernilai 1.0!'),
                      backgroundColor: Colors.orange,
                      behavior: SnackBarBehavior.floating,
                    ),
                  );
                }
              },
              onStepContinue: () {
                if (_canNavigateTo(_currentStep + 1, provider)) {
                  if (_currentStep < 2) {
                    setState(() => _currentStep += 1);
                  }
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Harap pastikan kriteria/matriks telah terisi lengkap dengan total bobot kriteria 1.0!'),
                      backgroundColor: Colors.orange,
                      behavior: SnackBarBehavior.floating,
                    ),
                  );
                }
              },
              onStepCancel: () {
                if (_currentStep > 0) {
                  setState(() => _currentStep -= 1);
                }
              },
              controlsBuilder: (context, details) {
                if (_currentStep == 2) {
                  return const SizedBox.shrink(); // Hide default controls on last step
                }
                return Padding(
                  padding: const EdgeInsets.only(top: 16.0),
                  child: Row(
                    children: [
                      Expanded(
                        child: ElevatedButton(
                          onPressed: details.onStepContinue,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppColors.teal,
                            padding: const EdgeInsets.symmetric(vertical: 16),
                          ),
                          child: const Text('Lanjut'),
                        ),
                      ),
                      const SizedBox(width: 12),
                      if (_currentStep > 0)
                        Expanded(
                          child: OutlinedButton(
                            onPressed: details.onStepCancel,
                            style: OutlinedButton.styleFrom(
                              padding: const EdgeInsets.symmetric(vertical: 16),
                            ),
                            child: const Text('Kembali'),
                          ),
                        ),
                    ],
                  ),
                );
              },
              steps: [
                Step(
                  title: const Text('Kriteria'),
                  content: const CriteriaStep(),
                  isActive: _currentStep >= 0,
                  state: _currentStep > 0 ? StepState.complete : StepState.indexed,
                ),
                Step(
                  title: const Text('Matriks'),
                  content: const MatrixStep(),
                  isActive: _currentStep >= 1,
                  state: _currentStep > 1 ? StepState.complete : StepState.indexed,
                ),
                Step(
                  title: const Text('Hasil'),
                  content: ResultsStep(title: _titleController.text),
                  isActive: _currentStep >= 2,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
