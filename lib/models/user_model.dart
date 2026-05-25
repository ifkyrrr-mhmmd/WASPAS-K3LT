/// Represents a user of the SPK K3LT application.
///
/// Each user has a [role] that determines their access level:
/// - `admin` – full access, can manage users and all calculations.
/// - `assessor` – can create, edit, and view calculations.
/// - `viewer` – read-only access to calculation results.
class UserModel {
  /// Unique identifier (typically from Firebase Auth UID).
  final String uid;

  /// User's email address.
  final String email;

  /// User's display name.
  final String displayName;

  /// User role: `'admin'`, `'assessor'`, or `'viewer'`.
  final String role;

  /// Timestamp when the account was created.
  final DateTime createdAt;

  /// Timestamp of the user's most recent login, or `null` if never logged in.
  final DateTime? lastLoginAt;

  /// User's optional profile picture URL.
  final String? photoUrl;

  /// Creates a [UserModel].
  const UserModel({
    required this.uid,
    required this.email,
    required this.displayName,
    this.role = 'viewer',
    required this.createdAt,
    this.lastLoginAt,
    this.photoUrl,
  });

  /// List of valid role values.
  static const List<String> validRoles = ['admin', 'assessor', 'viewer'];

  /// Whether this user has admin privileges.
  bool get isAdmin => role == 'admin';

  /// Whether this user can create and edit calculations.
  bool get canEdit => role == 'admin' || role == 'assessor';

  /// Creates a [UserModel] from a [Map] (e.g. Firestore document).
  ///
  /// Expects the following keys:
  /// - `uid` (String)
  /// - `email` (String)
  /// - `displayName` (String)
  /// - `role` (String, defaults to `'viewer'`)
  /// - `createdAt` (int – milliseconds since epoch, or ISO 8601 String)
  /// - `lastLoginAt` (int or String, optional)
  factory UserModel.fromMap(Map<String, dynamic> map) {
    return UserModel(
      uid: map['uid'] as String? ?? '',
      email: map['email'] as String? ?? '',
      displayName: map['displayName'] as String? ?? '',
      role: _parseRole(map['role']),
      createdAt: _parseDateTime(map['createdAt']) ?? DateTime.now(),
      lastLoginAt: _parseDateTime(map['lastLoginAt']),
      photoUrl: map['photoUrl'] as String?,
    );
  }

  /// Converts this [UserModel] to a [Map] for serialization.
  ///
  /// Timestamps are stored as milliseconds since epoch for portability.
  Map<String, dynamic> toMap() {
    return {
      'uid': uid,
      'email': email,
      'displayName': displayName,
      'role': role,
      'createdAt': createdAt.millisecondsSinceEpoch,
      if (lastLoginAt != null)
        'lastLoginAt': lastLoginAt!.millisecondsSinceEpoch,
      if (photoUrl != null)
        'photoUrl': photoUrl,
    };
  }

  /// Returns a copy of this [UserModel] with the given fields replaced.
  UserModel copyWith({
    String? uid,
    String? email,
    String? displayName,
    String? role,
    DateTime? createdAt,
    DateTime? lastLoginAt,
    String? photoUrl,
  }) {
    return UserModel(
      uid: uid ?? this.uid,
      email: email ?? this.email,
      displayName: displayName ?? this.displayName,
      role: role ?? this.role,
      createdAt: createdAt ?? this.createdAt,
      lastLoginAt: lastLoginAt ?? this.lastLoginAt,
      photoUrl: photoUrl ?? this.photoUrl,
    );
  }

  /// Validates and normalizes a role string.
  static String _parseRole(dynamic raw) {
    final value = (raw as String?)?.toLowerCase().trim() ?? 'viewer';
    return validRoles.contains(value) ? value : 'viewer';
  }

  /// Parses a timestamp from either an int (millis) or ISO 8601 String.
  static DateTime? _parseDateTime(dynamic raw) {
    if (raw == null) return null;
    if (raw is int) return DateTime.fromMillisecondsSinceEpoch(raw);
    if (raw is String) return DateTime.tryParse(raw);
    return null;
  }

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is UserModel &&
          runtimeType == other.runtimeType &&
          uid == other.uid;

  @override
  int get hashCode => uid.hashCode;

  @override
  String toString() =>
      'UserModel(uid: $uid, email: $email, displayName: $displayName, '
      'role: $role)';
}
