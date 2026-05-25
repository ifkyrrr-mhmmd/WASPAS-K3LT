/// Represents an auditable user action within the SPK K3LT application.
///
/// Audit logs record who did what and when, with optional [details] that
/// can store before/after snapshots of modified data.
class AuditLog {
  /// Unique identifier for this log entry.
  final String id;

  /// ID of the user who performed the action.
  final String userId;

  /// Display name of the user at the time of the action.
  final String userName;

  /// Type of action performed.
  ///
  /// One of: `'create'`, `'update'`, `'delete'`, `'calculate'`, `'export'`.
  final String action;

  /// Human-readable description of the action.
  ///
  /// Example: "Menghitung WASPAS untuk Seleksi Kepala K3LT Q1 2026".
  final String description;

  /// Timestamp when the action was performed.
  final DateTime timestamp;

  /// Optional additional details (e.g. before/after values).
  ///
  /// Example:
  /// ```dart
  /// {
  ///   'before': {'title': 'Draft'},
  ///   'after': {'title': 'Final'},
  /// }
  /// ```
  final Map<String, dynamic>? details;

  /// Creates an [AuditLog].
  const AuditLog({
    required this.id,
    required this.userId,
    required this.userName,
    required this.action,
    required this.description,
    required this.timestamp,
    this.details,
  });

  /// Valid action values.
  static const List<String> validActions = [
    'create',
    'update',
    'delete',
    'calculate',
    'export',
  ];

  /// Creates an [AuditLog] from a [Map] (e.g. Firestore document).
  ///
  /// Expects keys: `id`, `userId`, `userName`, `action`, `description`,
  /// `timestamp`, and optionally `details`.
  factory AuditLog.fromMap(Map<String, dynamic> map) {
    return AuditLog(
      id: map['id'] as String? ?? '',
      userId: map['userId'] as String? ?? '',
      userName: map['userName'] as String? ?? '',
      action: _parseAction(map['action']),
      description: map['description'] as String? ?? '',
      timestamp: _parseDateTime(map['timestamp']) ?? DateTime.now(),
      details: map['details'] as Map<String, dynamic>?,
    );
  }

  /// Converts this [AuditLog] to a [Map] for serialization.
  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'userId': userId,
      'userName': userName,
      'action': action,
      'description': description,
      'timestamp': timestamp.millisecondsSinceEpoch,
      if (details != null) 'details': details,
    };
  }

  /// Validates and normalizes the action string.
  static String _parseAction(dynamic raw) {
    final value = (raw as String?)?.toLowerCase().trim() ?? 'create';
    return validActions.contains(value) ? value : 'create';
  }

  /// Parses a timestamp from either an int (millis) or ISO 8601 String.
  static DateTime? _parseDateTime(dynamic raw) {
    if (raw == null) return null;
    if (raw is int) return DateTime.fromMillisecondsSinceEpoch(raw);
    if (raw is String) return DateTime.tryParse(raw);
    return null;
  }

  /// Returns a human-readable label for the [action] in Indonesian.
  String get actionLabel {
    switch (action) {
      case 'create':
        return 'Buat';
      case 'update':
        return 'Ubah';
      case 'delete':
        return 'Hapus';
      case 'calculate':
        return 'Hitung';
      case 'export':
        return 'Ekspor';
      default:
        return action;
    }
  }

  @override
  String toString() =>
      'AuditLog(id: $id, user: $userName, action: $action, '
      'description: $description, timestamp: $timestamp)';
}
