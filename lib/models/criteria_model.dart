/// Represents a single evaluation criterion in the WASPAS decision matrix.
///
/// Each criterion has a [type] of either `'Benefit'` (higher is better) or
/// `'Cost'` (lower is better), and a [weight] that determines its relative
/// importance in the calculation.
class CriteriaModel {
  /// Unique identifier for this criterion.
  final String id;

  /// Human-readable name (e.g. "Pengalaman K3LT").
  final String name;

  /// Criterion type: `'Benefit'` or `'Cost'`.
  ///
  /// - **Benefit**: maximized – higher values are preferred.
  /// - **Cost**: minimized – lower values are preferred.
  final String type;

  /// Relative importance weight, typically in the range (0, 1].
  ///
  /// The sum of all criteria weights in a calculation should equal 1.0.
  final double weight;

  /// Creates a [CriteriaModel].
  const CriteriaModel({
    required this.id,
    required this.name,
    required this.type,
    required this.weight,
  });

  /// Valid criterion type values.
  static const List<String> validTypes = ['Benefit', 'Cost'];

  /// Whether this criterion is a benefit type (higher is better).
  bool get isBenefit => type == 'Benefit';

  /// Whether this criterion is a cost type (lower is better).
  bool get isCost => type == 'Cost';

  /// Creates a [CriteriaModel] from a [Map] (e.g. Firestore document).
  ///
  /// Expects keys: `id`, `name`, `type`, `weight`.
  factory CriteriaModel.fromMap(Map<String, dynamic> map) {
    return CriteriaModel(
      id: map['id'] as String? ?? '',
      name: map['name'] as String? ?? '',
      type: _parseType(map['type']),
      weight: (map['weight'] as num?)?.toDouble() ?? 0.0,
    );
  }

  /// Converts this [CriteriaModel] to a [Map] for serialization.
  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'type': type,
      'weight': weight,
    };
  }

  /// Returns a copy of this [CriteriaModel] with the given fields replaced.
  CriteriaModel copyWith({
    String? id,
    String? name,
    String? type,
    double? weight,
  }) {
    return CriteriaModel(
      id: id ?? this.id,
      name: name ?? this.name,
      type: type ?? this.type,
      weight: weight ?? this.weight,
    );
  }

  /// Validates and normalizes the criterion type string.
  static String _parseType(dynamic raw) {
    final value = (raw as String?)?.trim() ?? 'Benefit';
    // Case-insensitive match.
    for (final valid in validTypes) {
      if (valid.toLowerCase() == value.toLowerCase()) return valid;
    }
    return 'Benefit';
  }

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is CriteriaModel &&
          runtimeType == other.runtimeType &&
          id == other.id;

  @override
  int get hashCode => id.hashCode;

  @override
  String toString() =>
      'CriteriaModel(id: $id, name: $name, type: $type, weight: $weight)';
}
