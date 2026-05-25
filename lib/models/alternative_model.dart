/// Represents an alternative (candidate) in the WASPAS decision matrix.
///
/// Each alternative has a [name] (e.g. a candidate's name) and a [values]
/// map that associates each criterion ID with the candidate's score for
/// that criterion.
class AlternativeModel {
  /// Unique identifier for this alternative.
  final String id;

  /// Human-readable name (e.g. candidate name "Budi Santoso").
  final String name;

  /// Scores keyed by criteria ID.
  ///
  /// Example: `{'c1': 8.0, 'c2': 3.0, 'c3': 75.0}`
  final Map<String, double> values;

  /// Creates an [AlternativeModel].
  const AlternativeModel({
    required this.id,
    required this.name,
    required this.values,
  });

  /// Returns the value for the given [criteriaId], or `0.0` if absent.
  double valueOf(String criteriaId) => values[criteriaId] ?? 0.0;

  /// Creates an [AlternativeModel] from a [Map] (e.g. Firestore document).
  ///
  /// Expects keys: `id`, `name`, `values` (a `Map<String, num>`).
  factory AlternativeModel.fromMap(Map<String, dynamic> map) {
    final rawValues = map['values'];
    final parsedValues = <String, double>{};

    if (rawValues is Map) {
      for (final entry in rawValues.entries) {
        final key = entry.key.toString();
        final value = (entry.value as num?)?.toDouble() ?? 0.0;
        parsedValues[key] = value;
      }
    }

    return AlternativeModel(
      id: map['id'] as String? ?? '',
      name: map['name'] as String? ?? '',
      values: parsedValues,
    );
  }

  /// Converts this [AlternativeModel] to a [Map] for serialization.
  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'values': Map<String, double>.from(values),
    };
  }

  /// Returns a copy of this [AlternativeModel] with the given fields replaced.
  AlternativeModel copyWith({
    String? id,
    String? name,
    Map<String, double>? values,
  }) {
    return AlternativeModel(
      id: id ?? this.id,
      name: name ?? this.name,
      values: values ?? Map<String, double>.from(this.values),
    );
  }

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is AlternativeModel &&
          runtimeType == other.runtimeType &&
          id == other.id;

  @override
  int get hashCode => id.hashCode;

  @override
  String toString() =>
      'AlternativeModel(id: $id, name: $name, values: $values)';
}
