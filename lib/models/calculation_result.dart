import 'dart:convert';
import 'criteria_model.dart';
import 'alternative_model.dart';

/// A single entry in the WASPAS ranking results.
///
/// Contains the SAW score ([q1]), WP score ([q2]), final WASPAS score ([qi]),
/// and the assigned [rank].
class RankingEntry {
  /// Index of the alternative in the original alternatives list.
  final int alternativeIndex;

  /// SAW (Simple Additive Weighting) score: Q1 = Σ (r_ij × w_j).
  final double q1;

  /// WP (Weighted Product) score: Q2 = Π (r_ij ^ w_j).
  final double q2;

  /// Final WASPAS score: Qi = λ × Q1 + (1 − λ) × Q2.
  final double qi;

  /// Final rank (1 = best).
  final int rank;

  /// Creates a [RankingEntry].
  const RankingEntry({
    required this.alternativeIndex,
    required this.q1,
    required this.q2,
    required this.qi,
    required this.rank,
  });

  /// Creates a [RankingEntry] from a [Map].
  factory RankingEntry.fromMap(Map<String, dynamic> map) {
    return RankingEntry(
      alternativeIndex: map['alternativeIndex'] as int? ?? 0,
      q1: (map['q1'] as num?)?.toDouble() ?? 0.0,
      q2: (map['q2'] as num?)?.toDouble() ?? 0.0,
      qi: (map['qi'] as num?)?.toDouble() ?? 0.0,
      rank: map['rank'] as int? ?? 0,
    );
  }

  /// Converts this [RankingEntry] to a [Map].
  Map<String, dynamic> toMap() {
    return {
      'alternativeIndex': alternativeIndex,
      'q1': q1,
      'q2': q2,
      'qi': qi,
      'rank': rank,
    };
  }

  @override
  String toString() =>
      'RankingEntry(alt: $alternativeIndex, q1: ${q1.toStringAsFixed(4)}, '
      'q2: ${q2.toStringAsFixed(4)}, qi: ${qi.toStringAsFixed(4)}, '
      'rank: $rank)';
}

/// Stores a complete WASPAS calculation result.
///
/// Includes the input data ([criteria], [alternatives]), the [lambda]
/// parameter, the [normalizedMatrix], and the final [rankings].
class CalculationResult {
  /// Unique identifier for this calculation result.
  final String id;

  /// ID of the user who performed the calculation.
  final String userId;

  /// Human-readable title for this calculation (e.g. "Seleksi Q1 2026").
  final String title;

  /// Timestamp when the calculation was performed.
  final DateTime createdAt;

  /// Lambda (λ) parameter balancing SAW vs WP (0.0–1.0).
  ///
  /// - λ = 1.0 → pure SAW
  /// - λ = 0.0 → pure WP
  /// - λ = 0.5 → balanced (default)
  final double lambda;

  /// Criteria used in this calculation.
  final List<CriteriaModel> criteria;

  /// Alternatives (candidates) evaluated in this calculation.
  final List<AlternativeModel> alternatives;

  /// Normalized decision matrix (alternatives × criteria).
  ///
  /// `normalizedMatrix[i][j]` is the normalized value for alternative `i`,
  /// criterion `j`.
  final List<List<double>> normalizedMatrix;

  /// Ranked results sorted by final WASPAS score (descending).
  final List<RankingEntry> rankings;

  /// Creates a [CalculationResult].
  const CalculationResult({
    required this.id,
    required this.userId,
    required this.title,
    required this.createdAt,
    this.lambda = 0.5,
    required this.criteria,
    required this.alternatives,
    required this.normalizedMatrix,
    required this.rankings,
  });

  /// The top-ranked alternative name, or an empty string if rankings is empty.
  String get topAlternativeName {
    if (rankings.isEmpty) return '';
    final topIndex = rankings.first.alternativeIndex;
    if (topIndex < 0 || topIndex >= alternatives.length) return '';
    return alternatives[topIndex].name;
  }

  /// Creates a [CalculationResult] from a [Map] (e.g. Firestore document).
  factory CalculationResult.fromMap(Map<String, dynamic> map) {
    // Parse criteria list.
    final criteriaList = <CriteriaModel>[];
    final rawCriteria = map['criteria'];
    if (rawCriteria is List) {
      for (final c in rawCriteria) {
        if (c is Map<String, dynamic>) {
          criteriaList.add(CriteriaModel.fromMap(c));
        }
      }
    }

    // Parse alternatives list.
    final alternativesList = <AlternativeModel>[];
    final rawAlternatives = map['alternatives'];
    if (rawAlternatives is List) {
      for (final a in rawAlternatives) {
        if (a is Map<String, dynamic>) {
          alternativesList.add(AlternativeModel.fromMap(a));
        }
      }
    }

    // Parse normalized matrix.
    final normalizedMatrix = <List<double>>[];
    final rawMatrix = map['normalizedMatrix'];
    if (rawMatrix is String) {
      try {
        final decoded = jsonDecode(rawMatrix);
        if (decoded is List) {
          for (final row in decoded) {
            if (row is List) {
              normalizedMatrix.add(
                row.map((v) => (v as num?)?.toDouble() ?? 0.0).toList(),
              );
            }
          }
        }
      } catch (_) {}
    } else if (rawMatrix is List) {
      for (final row in rawMatrix) {
        if (row is List) {
          normalizedMatrix.add(
            row.map((v) => (v as num?)?.toDouble() ?? 0.0).toList(),
          );
        }
      }
    }

    // Parse rankings.
    final rankingsList = <RankingEntry>[];
    final rawRankings = map['rankings'];
    if (rawRankings is List) {
      for (final r in rawRankings) {
        if (r is Map<String, dynamic>) {
          rankingsList.add(RankingEntry.fromMap(r));
        }
      }
    }

    return CalculationResult(
      id: map['id'] as String? ?? '',
      userId: map['userId'] as String? ?? '',
      title: map['title'] as String? ?? '',
      createdAt: _parseDateTime(map['createdAt']) ?? DateTime.now(),
      lambda: (map['lambda'] as num?)?.toDouble() ?? 0.5,
      criteria: criteriaList,
      alternatives: alternativesList,
      normalizedMatrix: normalizedMatrix,
      rankings: rankingsList,
    );
  }

  /// Converts this [CalculationResult] to a [Map] for serialization.
  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'userId': userId,
      'title': title,
      'createdAt': createdAt.millisecondsSinceEpoch,
      'lambda': lambda,
      'criteria': criteria.map((c) => c.toMap()).toList(),
      'alternatives': alternatives.map((a) => a.toMap()).toList(),
      'normalizedMatrix': jsonEncode(normalizedMatrix),
      'rankings': rankings.map((r) => r.toMap()).toList(),
    };
  }

  /// Parses a timestamp from either an int (millis) or ISO 8601 String.
  static DateTime? _parseDateTime(dynamic raw) {
    if (raw == null) return null;
    if (raw is int) return DateTime.fromMillisecondsSinceEpoch(raw);
    if (raw is String) return DateTime.tryParse(raw);
    return null;
  }

  @override
  String toString() =>
      'CalculationResult(id: $id, title: $title, '
      'alternatives: ${alternatives.length}, '
      'criteria: ${criteria.length}, '
      'topRank: $topAlternativeName)';
}
