import 'dart:math';

import '../models/calculation_result.dart';

/// Internal intermediate result returned by [WaspasCalculator.calculateWaspas].
class WaspasResult {
  /// Normalized decision matrix (alternatives × criteria).
  final List<List<double>> normalizedMatrix;

  /// SAW scores (Q1) for each alternative.
  final List<double> q1Scores;

  /// WP scores (Q2) for each alternative.
  final List<double> q2Scores;

  /// Final WASPAS scores (Qi) for each alternative.
  final List<double> qiScores;

  /// Ranked results sorted descending by [qiScores].
  final List<RankingEntry> rankings;

  /// Creates a [WaspasResult].
  const WaspasResult({
    required this.normalizedMatrix,
    required this.q1Scores,
    required this.q2Scores,
    required this.qiScores,
    required this.rankings,
  });
}

/// Static utility class implementing the WASPAS (Weighted Aggregated Sum
/// Product Assessment) multi-criteria decision-making method.
///
/// WASPAS combines two approaches:
/// 1. **SAW** (Simple Additive Weighting): Q1_i = Σ (r_ij × w_j)
/// 2. **WP** (Weighted Product): Q2_i = Π (r_ij ^ w_j)
///
/// The final score is: **Qi = λ × Q1_i + (1 − λ) × Q2_i**
///
/// Where λ ∈ [0, 1] controls the balance between SAW and WP.
class WaspasCalculator {
  WaspasCalculator._();

  /// Normalizes the decision [matrix] based on criterion [types].
  ///
  /// - **Benefit** criterion: `normalized[i][j] = matrix[i][j] / max(column_j)`
  /// - **Cost** criterion: `normalized[i][j] = min(column_j) / matrix[i][j]`
  ///
  /// Parameters:
  /// - [matrix]: 2D list of shape (alternatives × criteria).
  /// - [types]: list of `'Benefit'` or `'Cost'` for each criterion.
  ///
  /// Returns a new 2D list with the same dimensions, containing the
  /// normalized values.
  ///
  /// Throws [ArgumentError] if dimensions are inconsistent.
  static List<List<double>> normalizeMatrix(
    List<List<double>> matrix,
    List<String> types,
  ) {
    if (matrix.isEmpty) return [];

    final numAlternatives = matrix.length;
    final numCriteria = types.length;

    // Validate dimensions.
    for (var i = 0; i < numAlternatives; i++) {
      if (matrix[i].length != numCriteria) {
        throw ArgumentError(
          'Baris ke-${i + 1} memiliki ${matrix[i].length} kolom, '
          'diharapkan $numCriteria kolom sesuai jumlah kriteria.',
        );
      }
    }

    // Initialize result matrix.
    final normalized = List.generate(
      numAlternatives,
      (_) => List<double>.filled(numCriteria, 0.0),
    );

    for (var j = 0; j < numCriteria; j++) {
      // Extract column values.
      final column = [for (var i = 0; i < numAlternatives; i++) matrix[i][j]];

      final isBenefit =
          types[j].toLowerCase() == 'benefit';

      if (isBenefit) {
        // Benefit: divide by max of column.
        final maxVal = column.reduce(max);
        for (var i = 0; i < numAlternatives; i++) {
          normalized[i][j] = maxVal == 0 ? 0.0 : matrix[i][j] / maxVal;
        }
      } else {
        // Cost: divide min of column by each value.
        final minVal = column.reduce(min);
        for (var i = 0; i < numAlternatives; i++) {
          normalized[i][j] =
              matrix[i][j] == 0 ? 0.0 : minVal / matrix[i][j];
        }
      }
    }

    return normalized;
  }

  /// Calculates SAW (Simple Additive Weighting) scores.
  ///
  /// **Q1_i = Σ (r_ij × w_j)** for each alternative i.
  ///
  /// Parameters:
  /// - [normalizedMatrix]: normalized decision matrix.
  /// - [weights]: list of weights for each criterion.
  ///
  /// Returns a list of Q1 scores, one per alternative.
  static List<double> calculateSaw(
    List<List<double>> normalizedMatrix,
    List<double> weights,
  ) {
    if (normalizedMatrix.isEmpty) return [];

    final numAlternatives = normalizedMatrix.length;
    final numCriteria = weights.length;
    final scores = <double>[];

    for (var i = 0; i < numAlternatives; i++) {
      var sum = 0.0;
      for (var j = 0; j < numCriteria; j++) {
        sum += normalizedMatrix[i][j] * weights[j];
      }
      scores.add(sum);
    }

    return scores;
  }

  /// Calculates WP (Weighted Product) scores.
  ///
  /// **Q2_i = Π (r_ij ^ w_j)** for each alternative i.
  ///
  /// Parameters:
  /// - [normalizedMatrix]: normalized decision matrix.
  /// - [weights]: list of weights for each criterion.
  ///
  /// Returns a list of Q2 scores, one per alternative.
  static List<double> calculateWp(
    List<List<double>> normalizedMatrix,
    List<double> weights,
  ) {
    if (normalizedMatrix.isEmpty) return [];

    final numAlternatives = normalizedMatrix.length;
    final numCriteria = weights.length;
    final scores = <double>[];

    for (var i = 0; i < numAlternatives; i++) {
      var product = 1.0;
      for (var j = 0; j < numCriteria; j++) {
        final value = normalizedMatrix[i][j];
        // Avoid pow(0, w) which yields 0 and collapses the entire product.
        // A zero normalized value means the alternative scored zero on that
        // criterion, so the product contribution is 0.
        if (value == 0.0) {
          product = 0.0;
          break;
        }
        product *= pow(value, weights[j]);
      }
      scores.add(product);
    }

    return scores;
  }

  /// Performs a complete WASPAS calculation.
  ///
  /// Steps:
  /// 1. Normalize the [matrix] using [types].
  /// 2. Calculate SAW scores (Q1).
  /// 3. Calculate WP scores (Q2).
  /// 4. Combine: Qi = λ × Q1_i + (1 − λ) × Q2_i.
  /// 5. Sort descending by Qi and assign ranks.
  ///
  /// Parameters:
  /// - [matrix]: raw decision matrix (alternatives × criteria).
  /// - [weights]: criterion weights (should sum to 1.0).
  /// - [types]: `'Benefit'` or `'Cost'` for each criterion.
  /// - [lambda]: balance parameter, default 0.5.
  ///
  /// Throws [ArgumentError] on invalid input (empty matrix, mismatched
  /// dimensions, lambda out of range).
  static WaspasResult calculateWaspas(
    List<List<double>> matrix,
    List<double> weights,
    List<String> types, {
    double lambda = 0.5,
  }) {
    // ── Input validation ─────────────────────────────────────────────
    if (matrix.isEmpty) {
      throw ArgumentError('Matriks keputusan tidak boleh kosong.');
    }
    if (weights.isEmpty) {
      throw ArgumentError('Bobot kriteria tidak boleh kosong.');
    }
    if (types.isEmpty) {
      throw ArgumentError('Tipe kriteria tidak boleh kosong.');
    }
    if (weights.length != types.length) {
      throw ArgumentError(
        'Jumlah bobot (${weights.length}) tidak sesuai dengan '
        'jumlah tipe kriteria (${types.length}).',
      );
    }
    if (lambda < 0.0 || lambda > 1.0) {
      throw ArgumentError(
        'Nilai lambda harus antara 0.0 dan 1.0, diterima: $lambda.',
      );
    }

    // ── Step 1: Normalize ────────────────────────────────────────────
    final normalizedMatrix = normalizeMatrix(matrix, types);

    // ── Step 2: SAW ──────────────────────────────────────────────────
    final q1Scores = calculateSaw(normalizedMatrix, weights);

    // ── Step 3: WP ───────────────────────────────────────────────────
    final q2Scores = calculateWp(normalizedMatrix, weights);

    // ── Step 4: WASPAS combine ───────────────────────────────────────
    final numAlternatives = matrix.length;
    final qiScores = <double>[];
    for (var i = 0; i < numAlternatives; i++) {
      final qi = lambda * q1Scores[i] + (1 - lambda) * q2Scores[i];
      qiScores.add(qi);
    }

    // ── Step 5: Sort & rank ──────────────────────────────────────────
    // Create index list and sort descending by Qi.
    final indices = List<int>.generate(numAlternatives, (i) => i);
    indices.sort((a, b) => qiScores[b].compareTo(qiScores[a]));

    final rankings = <RankingEntry>[];
    for (var rank = 0; rank < indices.length; rank++) {
      final idx = indices[rank];
      rankings.add(RankingEntry(
        alternativeIndex: idx,
        q1: q1Scores[idx],
        q2: q2Scores[idx],
        qi: qiScores[idx],
        rank: rank + 1,
      ));
    }

    return WaspasResult(
      normalizedMatrix: normalizedMatrix,
      q1Scores: q1Scores,
      q2Scores: q2Scores,
      qiScores: qiScores,
      rankings: rankings,
    );
  }
}
