import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/criteria_model.dart';
import '../models/alternative_model.dart';
import '../models/calculation_result.dart';
import '../utils/waspas_calculator.dart';
import '../utils/k3lt_templates.dart';
import '../services/firestore_service.dart';

class WaspasProvider extends ChangeNotifier {
  WaspasProvider() {
    _loadFromLocal();
  }

  List<CriteriaModel> _criteria = [];
  List<AlternativeModel> _alternatives = [];
  double _lambda = 0.5;
  CalculationResult? _lastResult;
  List<CalculationResult> _history = [];
  bool _isLoading = false;
  String? _errorMessage;

  List<CriteriaModel> get criteria => _criteria;
  List<AlternativeModel> get alternatives => _alternatives;
  double get lambda => _lambda;
  CalculationResult? get lastResult => _lastResult;
  List<CalculationResult> get history => _history;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  final FirestoreService _firestoreService = FirestoreService();

  void addCriteria(CriteriaModel criteriaModel) {
    _criteria.add(criteriaModel);
    notifyListeners();
    _saveToLocal();
  }

  void removeCriteria(String id) {
    _criteria.removeWhere((c) => c.id == id);
    notifyListeners();
    _saveToLocal();
  }

  void updateCriteria(int index, CriteriaModel updated) {
    if (index >= 0 && index < _criteria.length) {
      _criteria[index] = updated;
      notifyListeners();
      _saveToLocal();
    }
  }

  void addAlternative(AlternativeModel alt) {
    _alternatives.add(alt);
    notifyListeners();
    _saveToLocal();
  }

  void removeAlternative(String id) {
    _alternatives.removeWhere((a) => a.id == id);
    notifyListeners();
    _saveToLocal();
  }

  void updateAlternative(int index, AlternativeModel updated) {
    if (index >= 0 && index < _alternatives.length) {
      _alternatives[index] = updated;
      notifyListeners();
      _saveToLocal();
    }
  }

  void updateAlternativeValue(int altIndex, String criteriaId, double value) {
    if (altIndex >= 0 && altIndex < _alternatives.length) {
      final values = Map<String, double>.from(_alternatives[altIndex].values);
      values[criteriaId] = value;
      _alternatives[altIndex] = _alternatives[altIndex].copyWith(values: values);
      notifyListeners();
      _saveToLocal();
    }
  }

  void setLambda(double value) {
    _lambda = value;
    notifyListeners();
    _saveToLocal();
  }

  void loadTemplate(String templateName) {
    try {
      final tpl = K3ltTemplates.getCriteria(templateName);
      _criteria = tpl.map((c) => c.copyWith()).toList();
      
      final alts = K3ltTemplates.getSampleAlternatives(templateName);
      _alternatives = alts.map((a) => a.copyWith()).toList();
      
      _lastResult = null;
      notifyListeners();
      _saveToLocal();
    } catch (e) {
      _errorMessage = e.toString();
      notifyListeners();
    }
  }

  bool validate() {
    if (_criteria.isEmpty) {
      _errorMessage = 'Minimal harus ada 1 kriteria.';
      notifyListeners();
      return false;
    }
    if (_alternatives.isEmpty) {
      _errorMessage = 'Minimal harus ada 1 alternatif.';
      notifyListeners();
      return false;
    }
    double totalWeight = _criteria.fold(0.0, (sum, c) => sum + c.weight);
    if ((totalWeight - 1.0).abs() > 0.005) {
      _errorMessage = 'Total bobot kriteria harus 1.0.';
      notifyListeners();
      return false;
    }
    for (var alt in _alternatives) {
      if (alt.name.trim().isEmpty) {
        _errorMessage = 'Nama alternatif tidak boleh kosong.';
        notifyListeners();
        return false;
      }
      for (var c in _criteria) {
        if ((alt.values[c.id] ?? 0) <= 0) {
          _errorMessage = 'Semua nilai matriks harus lebih besar dari 0.';
          notifyListeners();
          return false;
        }
      }
    }
    _errorMessage = null;
    notifyListeners();
    return true;
  }

  String? getValidationError() => _errorMessage;

  CalculationResult? calculate(String userId, String title) {
    if (!validate()) return null;

    final matrix = _alternatives.map((a) {
      return _criteria.map((c) => a.values[c.id] ?? 0.0).toList();
    }).toList();
    final weights = _criteria.map((c) => c.weight).toList();
    final types = _criteria.map((c) => c.type).toList();

    try {
      final result = WaspasCalculator.calculateWaspas(matrix, weights, types, lambda: _lambda);
      
      _lastResult = CalculationResult(
        id: DateTime.now().millisecondsSinceEpoch.toString(),
        userId: userId,
        title: title.isEmpty ? 'Perhitungan Baru' : title,
        createdAt: DateTime.now(),
        lambda: _lambda,
        criteria: _criteria.map((c) => c.copyWith()).toList(),
        alternatives: _alternatives.map((a) => a.copyWith()).toList(),
        normalizedMatrix: result.normalizedMatrix,
        rankings: result.rankings,
      );
      
      notifyListeners();
      return _lastResult;
    } catch (e) {
      _errorMessage = e.toString();
      notifyListeners();
      return null;
    }
  }

  Future<void> saveResult(CalculationResult result) async {
    try {
      _isLoading = true;
      notifyListeners();
      
      await _firestoreService.saveCalculation(result);
      // add to history locally to avoid reloading immediately
      _history.insert(0, result);
    } catch (e) {
      _errorMessage = 'Gagal menyimpan hasil: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> loadHistory(String userId) async {
    try {
      _isLoading = true;
      notifyListeners();
      
      _history = await _firestoreService.getCalculations(userId);
    } catch (e) {
      _errorMessage = 'Gagal memuat riwayat: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> deleteHistory(String id) async {
    try {
      await _firestoreService.deleteCalculation(id);
      _history.removeWhere((item) => item.id == id);
      notifyListeners();
    } catch (e) {
      _errorMessage = 'Gagal menghapus riwayat: $e';
      notifyListeners();
    }
  }

  void reset() {
    _criteria.clear();
    _alternatives.clear();
    _lambda = 0.5;
    _lastResult = null;
    _errorMessage = null;
    notifyListeners();
    _saveToLocal();
  }

  void loadFromResult(CalculationResult result) {
    _criteria = result.criteria.map((c) => c.copyWith()).toList();
    _alternatives = result.alternatives.map((a) => a.copyWith()).toList();
    _lambda = result.lambda;
    _lastResult = result;
    _errorMessage = null;
    notifyListeners();
    _saveToLocal();
  }

  Future<void> _saveToLocal() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final data = {
        'criteria': _criteria.map((c) => c.toMap()).toList(),
        'alternatives': _alternatives.map((a) => a.toMap()).toList(),
        'lambda': _lambda,
      };
      await prefs.setString('waspas_active_inputs', jsonEncode(data));
    } catch (e) {
      debugPrint('Gagal menyimpan input lokal: $e');
    }
  }

  Future<void> _loadFromLocal() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final saved = prefs.getString('waspas_active_inputs');
      if (saved != null) {
        final decoded = jsonDecode(saved);
        if (decoded is Map<String, dynamic>) {
          if (decoded['criteria'] is List) {
            _criteria = (decoded['criteria'] as List)
                .map((c) => CriteriaModel.fromMap(c as Map<String, dynamic>))
                .toList();
          }
          if (decoded['alternatives'] is List) {
            _alternatives = (decoded['alternatives'] as List)
                .map((a) => AlternativeModel.fromMap(a as Map<String, dynamic>))
                .toList();
          }
          if (decoded['lambda'] != null) {
            _lambda = (decoded['lambda'] as num).toDouble();
          }
          notifyListeners();
        }
      }
    } catch (e) {
      debugPrint('Gagal memuat input lokal: $e');
    }
  }
}
