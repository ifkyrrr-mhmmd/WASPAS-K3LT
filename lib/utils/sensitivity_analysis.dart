import 'waspas_calculator.dart';

/// A single data point from a sensitivity analysis run.
///
/// Records the parameter value tested, the resulting rankings, and
/// whether the top-ranked alternative changed compared to the baseline.
class SensitivityResult {
  /// The lambda or weight value used in this iteration.
  final double parameterValue;

  /// Label describing which parameter was varied (e.g. "Lambda", "Bobot C3").
  final String parameterLabel;

  /// The alternative index that ranked #1 at this parameter value.
  final int topRankedIndex;

  /// Full ranking list from best to worst.
  final List<RankingEntrySnapshot> rankings;

  /// Whether the top-ranked alternative differs from the baseline result.
  final bool topRankChanged;

  /// Creates a [SensitivityResult].
  const SensitivityResult({
    required this.parameterValue,
    required this.parameterLabel,
    required this.topRankedIndex,
    required this.rankings,
    required this.topRankChanged,
  });
}

/// Lightweight snapshot of a ranking entry for sensitivity analysis output.
class RankingEntrySnapshot {
  /// Index of the alternative in the original list.
  final int alternativeIndex;

  /// Final WASPAS score (Qi).
  final double qi;

  /// Assigned rank (1 = best).
  final int rank;

  /// Creates a [RankingEntrySnapshot].
  const RankingEntrySnapshot({
    required this.alternativeIndex,
    required this.qi,
    required this.rank,
  });
}

/// Sensitivity analysis utilities for the WASPAS method.
///
/// Provides two types of analysis:
/// 1. **Lambda analysis** – varies λ from 0.0 to 1.0 to observe how the
///    balance between SAW and WP affects rankings.
/// 2. **Weight analysis** – varies one criterion's weight while
///    proportionally redistributing the remaining weight to other criteria.
class SensitivityAnalysis {
  SensitivityAnalysis._();

  /// Analyses how varying lambda (λ) affects the WASPAS rankings.
  ///
  /// Lambda is varied from 0.0 to 1.0 in [steps] equal intervals.
  /// For each value, a full WASPAS calculation is performed and the
  /// resulting ranking is recorded.
  ///
  /// Parameters:
  /// - [matrix]: raw decision matrix (alternatives × criteria).
  /// - [weights]: criterion weights.
  /// - [types]: `'Benefit'` or `'Cost'` for each criterion.
  /// - [steps]: number of intervals (e.g. 10 → λ = 0.0, 0.1, ..., 1.0).
  ///
  /// Returns a list of [SensitivityResult] for each lambda value.
  ///
  /// The baseline top rank is determined at λ = 0.5. Each result's
  /// [SensitivityResult.topRankChanged] indicates whether the winner
  /// differs from the baseline.
  static List<SensitivityResult> analyzeLambda(
    List<List<double>> matrix,
    List<double> weights,
    List<String> types, {
    int steps = 10,
  }) {
    if (matrix.isEmpty || weights.isEmpty || types.isEmpty) return [];
    if (steps < 1) steps = 1;

    // Determine baseline top rank at λ = 0.5.
    final baseline = WaspasCalculator.calculateWaspas(
      matrix,
      weights,
      types,
      lambda: 0.5,
    );
    final baselineTopIndex = baseline.rankings.first.alternativeIndex;

    final results = <SensitivityResult>[];

    for (var step = 0; step <= steps; step++) {
      final lambda = step / steps;

      final waspasResult = WaspasCalculator.calculateWaspas(
        matrix,
        weights,
        types,
        lambda: lambda,
      );

      final topIndex = waspasResult.rankings.first.alternativeIndex;

      results.add(SensitivityResult(
        parameterValue: lambda,
        parameterLabel: 'Lambda',
        topRankedIndex: topIndex,
        rankings: waspasResult.rankings
            .map((r) => RankingEntrySnapshot(
                  alternativeIndex: r.alternativeIndex,
                  qi: r.qi,
                  rank: r.rank,
                ))
            .toList(),
        topRankChanged: topIndex != baselineTopIndex,
      ));
    }

    return results;
  }

  /// Analyses how varying a single criterion's weight affects rankings.
  ///
  /// The weight of the criterion at [criteriaIndex] is varied from 0.0
  /// to its maximum possible value in [steps] equal intervals. The
  /// remaining weight is redistributed proportionally to all other
  /// criteria, preserving their relative ratios.
  ///
  /// Parameters:
  /// - [matrix]: raw decision matrix.
  /// - [weights]: original criterion weights.
  /// - [types]: criterion types.
  /// - [criteriaIndex]: index of the criterion whose weight is varied.
  /// - [lambda]: WASPAS lambda parameter (default 0.5).
  /// - [steps]: number of intervals (default 10).
  ///
  /// Returns a list of [SensitivityResult] for each weight value.
  static List<SensitivityResult> analyzeWeight(
    List<List<double>> matrix,
    List<double> weights,
    List<String> types,
    int criteriaIndex, {
    double lambda = 0.5,
    int steps = 10,
  }) {
    if (matrix.isEmpty || weights.isEmpty || types.isEmpty) return [];
    if (criteriaIndex < 0 || criteriaIndex >= weights.length) return [];
    if (steps < 1) steps = 1;

    // Calculate the sum of weights of OTHER criteria to determine their
    // relative proportions.
    final otherWeightsSum = weights
        .asMap()
        .entries
        .where((e) => e.key != criteriaIndex)
        .fold<double>(0.0, (sum, e) => sum + e.value);

    // Determine baseline top rank with original weights.
    final baseline = WaspasCalculator.calculateWaspas(
      matrix,
      weights,
      types,
      lambda: lambda,
    );
    final baselineTopIndex = baseline.rankings.first.alternativeIndex;

    // The maximum weight for this criterion is 1.0 (all weight assigned
    // to it). We use 0.95 as practical max to avoid degenerate cases
    // where all other weights are essentially zero.
    const maxWeight = 0.95;

    final results = <SensitivityResult>[];
    final criteriaName =
        'C${criteriaIndex + 1}'; // For label; caller can override.

    for (var step = 0; step <= steps; step++) {
      final targetWeight = (step / steps) * maxWeight;
      final remainingWeight = 1.0 - targetWeight;

      // Build adjusted weight vector.
      final adjustedWeights = List<double>.from(weights);
      adjustedWeights[criteriaIndex] = targetWeight;

      // Redistribute remaining weight proportionally.
      for (var j = 0; j < weights.length; j++) {
        if (j == criteriaIndex) continue;
        if (otherWeightsSum == 0) {
          // If all other weights were zero, distribute evenly.
          adjustedWeights[j] =
              remainingWeight / (weights.length - 1);
        } else {
          adjustedWeights[j] =
              (weights[j] / otherWeightsSum) * remainingWeight;
        }
      }

      final waspasResult = WaspasCalculator.calculateWaspas(
        matrix,
        adjustedWeights,
        types,
        lambda: lambda,
      );

      final topIndex = waspasResult.rankings.first.alternativeIndex;

      results.add(SensitivityResult(
        parameterValue: targetWeight,
        parameterLabel: 'Bobot $criteriaName',
        topRankedIndex: topIndex,
        rankings: waspasResult.rankings
            .map((r) => RankingEntrySnapshot(
                  alternativeIndex: r.alternativeIndex,
                  qi: r.qi,
                  rank: r.rank,
                ))
            .toList(),
        topRankChanged: topIndex != baselineTopIndex,
      ));
    }

    return results;
  }

  /// Convenience method to check whether the top rank is stable across
  /// all results in a sensitivity analysis.
  ///
  /// Returns `true` if the top-ranked alternative never changes.
  static bool isRankingStable(List<SensitivityResult> results) {
    return results.every((r) => !r.topRankChanged);
  }

  /// Returns the set of unique top-ranked alternative indices across
  /// all sensitivity results.
  ///
  /// A single entry means the ranking is perfectly stable.
  static Set<int> uniqueTopRanks(List<SensitivityResult> results) {
    return results.map((r) => r.topRankedIndex).toSet();
  }
}
