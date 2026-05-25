/// Firestore CRUD service for SPK WASPAS K3LT.
///
/// Manages all Firestore operations including user profiles, WASPAS
/// calculation results, and audit logs. Provides both one-shot queries
/// and real-time streams for reactive UI updates.
library;

import 'package:cloud_firestore/cloud_firestore.dart';

import '../models/audit_log.dart';
import '../models/calculation_result.dart';
import '../models/user_model.dart';

/// Service class that encapsulates all Firestore database operations.
///
/// Collections used:
/// - `users` — User profiles and metadata
/// - `calculations` — WASPAS calculation results
/// - `audit_logs` — Audit trail for accountability
///
/// Usage:
/// ```dart
/// final firestoreService = FirestoreService();
/// await firestoreService.createUserProfile(userModel);
/// ```
class FirestoreService {
  /// Creates a [FirestoreService] instance.
  ///
  /// Optionally accepts a [FirebaseFirestore] instance for testing.
  FirestoreService({FirebaseFirestore? firestore})
      : _db = firestore ?? FirebaseFirestore.instance;

  final FirebaseFirestore _db;

  // ---------------------------------------------------------------------------
  // Collection references
  // ---------------------------------------------------------------------------

  /// Reference to the `users` collection.
  CollectionReference<Map<String, dynamic>> get _usersRef =>
      _db.collection('users');

  /// Reference to the `calculations` collection.
  CollectionReference<Map<String, dynamic>> get _calculationsRef =>
      _db.collection('calculations');

  /// Reference to the `audit_logs` collection.
  CollectionReference<Map<String, dynamic>> get _auditLogsRef =>
      _db.collection('audit_logs');

  // ---------------------------------------------------------------------------
  // User Profile Operations
  // ---------------------------------------------------------------------------

  /// Creates a new user profile document in the `users` collection.
  ///
  /// The document ID is set to [user.uid] so it can be quickly retrieved
  /// by Firebase Auth UID.
  Future<void> createUserProfile(UserModel user) async {
    await _usersRef.doc(user.uid).set(user.toMap());
  }

  /// Retrieves the user profile for the given [uid].
  ///
  /// Returns `null` if the document does not exist.
  Future<UserModel?> getUserProfile(String uid) async {
    final doc = await _usersRef.doc(uid).get();
    if (!doc.exists || doc.data() == null) return null;

    return UserModel.fromMap({...doc.data()!, 'uid': doc.id});
  }

  /// Partially updates the user profile for [uid] with the given [data].
  ///
  /// Only the keys present in [data] are overwritten; other fields remain
  /// unchanged.
  Future<void> updateUserProfile(
    String uid,
    Map<String, dynamic> data,
  ) async {
    await _usersRef.doc(uid).update(data);
  }

  /// Convenience method that updates the `lastLoginAt` timestamp for [uid]
  /// to the current server time.
  Future<void> updateLastLogin(String uid) async {
    await _usersRef.doc(uid).update({
      'lastLoginAt': FieldValue.serverTimestamp(),
    });
  }

  // ---------------------------------------------------------------------------
  // Calculation Operations
  // ---------------------------------------------------------------------------

  /// Saves a [CalculationResult] to Firestore and returns the document ID.
  ///
  /// If [result.id] is non-empty it is used as the document ID; otherwise
  /// Firestore auto-generates one. The returned ID can be used for
  /// subsequent reads or deletes.
  Future<String> saveCalculation(CalculationResult result) async {
    final data = result.toMap();

    if (result.id.isNotEmpty) {
      await _calculationsRef.doc(result.id).set(data);
      return result.id;
    }

    final docRef = await _calculationsRef.add(data);
    return docRef.id;
  }

  /// Fetches a list of [CalculationResult] documents owned by [userId],
  /// ordered by `createdAt` descending (newest first).
  ///
  /// Optionally pass [limit] to cap the number of results.
  Future<List<CalculationResult>> getCalculations(
    String userId, {
    int? limit,
  }) async {
    Query<Map<String, dynamic>> query = _calculationsRef
        .where('userId', isEqualTo: userId);

    final snapshot = await query.get();
    var results = snapshot.docs
        .map((doc) => CalculationResult.fromMap({...doc.data(), 'id': doc.id}))
        .toList();
        
    // Sort locally to avoid needing a composite index in Firestore
    results.sort((a, b) => b.createdAt.compareTo(a.createdAt));
    
    if (limit != null && results.length > limit) {
      results = results.sublist(0, limit);
    }
    
    return results;
  }

  /// Fetches a single [CalculationResult] by document [id].
  ///
  /// Returns `null` if the document does not exist.
  Future<CalculationResult?> getCalculation(String id) async {
    final doc = await _calculationsRef.doc(id).get();
    if (!doc.exists || doc.data() == null) return null;

    return CalculationResult.fromMap({...doc.data()!, 'id': doc.id});
  }

  /// Deletes the calculation document with the given [id].
  Future<void> deleteCalculation(String id) async {
    await _calculationsRef.doc(id).delete();
  }

  /// Returns a real-time stream of [CalculationResult] documents owned by
  /// [userId], ordered by `createdAt` descending.
  ///
  /// The stream emits a new list every time the underlying data changes.
  Stream<List<CalculationResult>> calculationsStream(String userId) {
    return _calculationsRef
        .where('userId', isEqualTo: userId)
        .snapshots()
        .map((snapshot) {
      final results = snapshot.docs
          .map((doc) => CalculationResult.fromMap({...doc.data(), 'id': doc.id}))
          .toList();
      results.sort((a, b) => b.createdAt.compareTo(a.createdAt));
      return results;
    });
  }

  // ---------------------------------------------------------------------------
  // Audit Log Operations
  // ---------------------------------------------------------------------------

  /// Adds a new audit log entry to the `audit_logs` collection.
  Future<void> addAuditLog(AuditLog log) async {
    await _auditLogsRef.add(log.toMap());
  }

  /// Fetches a list of [AuditLog] entries for [userId], ordered by
  /// `timestamp` descending (newest first).
  ///
  /// Optionally pass [limit] to cap the number of results.
  Future<List<AuditLog>> getAuditLogs(
    String userId, {
    int? limit,
  }) async {
    Query<Map<String, dynamic>> query = _auditLogsRef
        .where('userId', isEqualTo: userId)
        .orderBy('timestamp', descending: true);

    if (limit != null) {
      query = query.limit(limit);
    }

    final snapshot = await query.get();
    return snapshot.docs
        .map((doc) => AuditLog.fromMap({...doc.data(), 'id': doc.id}))
        .toList();
  }

  /// Returns a real-time stream of [AuditLog] entries for [userId],
  /// ordered by `timestamp` descending.
  Stream<List<AuditLog>> auditLogsStream(String userId) {
    return _auditLogsRef
        .where('userId', isEqualTo: userId)
        .orderBy('timestamp', descending: true)
        .snapshots()
        .map(
          (snapshot) => snapshot.docs
              .map(
                (doc) => AuditLog.fromMap({...doc.data(), 'id': doc.id}),
              )
              .toList(),
        );
  }
}
