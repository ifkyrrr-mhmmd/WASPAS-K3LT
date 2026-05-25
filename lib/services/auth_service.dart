/// Firebase Authentication service for SPK WASPAS K3LT.
///
/// Provides email/password authentication with Firebase, including sign-in,
/// registration, sign-out, and password reset. All Firebase auth exceptions
/// are translated to Indonesian error messages via [AuthException].
library;

import 'package:firebase_auth/firebase_auth.dart';

/// Custom exception class for authentication errors.
///
/// Wraps Firebase authentication errors with user-friendly Indonesian
/// error messages suitable for display in the UI.
class AuthException implements Exception {
  /// Creates an [AuthException] with the given [message] and optional [code].
  const AuthException(this.message, {this.code});

  /// Human-readable error message in Indonesian.
  final String message;

  /// Firebase error code, if available.
  final String? code;

  @override
  String toString() => 'AuthException($code): $message';
}

/// Service class that wraps Firebase Authentication operations.
///
/// Usage:
/// ```dart
/// final authService = AuthService();
/// try {
///   final credential = await authService.signInWithEmail(email, password);
/// } on AuthException catch (e) {
///   print(e.message); // Indonesian error message
/// }
/// ```
class AuthService {
  /// Creates an [AuthService] instance.
  ///
  /// Optionally accepts a [FirebaseAuth] instance for testing purposes.
  AuthService({FirebaseAuth? firebaseAuth})
      : _auth = firebaseAuth ?? FirebaseAuth.instance;

  final FirebaseAuth _auth;

  /// Returns the currently signed-in [User], or `null` if not signed in.
  User? get currentUser => _auth.currentUser;

  /// A stream that emits the current [User] whenever the authentication
  /// state changes (sign-in, sign-out, token refresh).
  Stream<User?> get authStateChanges => _auth.authStateChanges();

  /// Signs in with [email] and [password].
  ///
  /// Returns a [UserCredential] on success.
  /// Throws an [AuthException] with an Indonesian message on failure.
  Future<UserCredential> signInWithEmail(
    String email,
    String password,
  ) async {
    try {
      final credential = await _auth.signInWithEmailAndPassword(
        email: email.trim(),
        password: password,
      );
      return credential;
    } on FirebaseAuthException catch (e) {
      throw _mapException(e);
    } catch (e) {
      throw const AuthException(
        'Terjadi kesalahan saat login. Silakan coba lagi.',
      );
    }
  }

  /// Signs in using Google Sign-In (web compatible).
  Future<UserCredential> signInWithGoogle() async {
    try {
      GoogleAuthProvider googleProvider = GoogleAuthProvider();
      final credential = await _auth.signInWithPopup(googleProvider);
      return credential;
    } on FirebaseAuthException catch (e) {
      throw _mapException(e);
    } catch (e) {
      throw const AuthException(
        'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.',
      );
    }
  }

  /// Registers a new user with [email], [password], and [displayName].
  ///
  /// After successful registration the user's display name is updated
  /// in Firebase Auth. Returns a [UserCredential] on success.
  /// Throws an [AuthException] with an Indonesian message on failure.
  Future<UserCredential> registerWithEmail(
    String email,
    String password,
    String displayName,
  ) async {
    try {
      final credential = await _auth.createUserWithEmailAndPassword(
        email: email.trim(),
        password: password,
      );

      // Update the display name in Firebase Auth profile.
      await credential.user?.updateDisplayName(displayName.trim());
      await credential.user?.reload();

      return credential;
    } on FirebaseAuthException catch (e) {
      throw _mapException(e);
    } catch (e) {
      throw const AuthException(
        'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
      );
    }
  }

  /// Signs out the current user.
  ///
  /// Throws an [AuthException] if the sign-out process fails.
  Future<void> signOut() async {
    try {
      await _auth.signOut();
    } catch (e) {
      throw const AuthException(
        'Terjadi kesalahan saat logout. Silakan coba lagi.',
      );
    }
  }

  /// Sends a password-reset email to [email].
  ///
  /// Throws an [AuthException] with an Indonesian message if the email
  /// is invalid or not registered.
  Future<void> resetPassword(String email) async {
    try {
      await _auth.sendPasswordResetEmail(email: email.trim());
    } on FirebaseAuthException catch (e) {
      throw _mapException(e);
    } catch (e) {
      throw const AuthException(
        'Terjadi kesalahan saat mengirim email reset password.',
      );
    }
  }

  /// Maps a [FirebaseAuthException] to an [AuthException] with an
  /// Indonesian user-facing message.
  AuthException _mapException(FirebaseAuthException e) {
    final String message;
    switch (e.code) {
      case 'wrong-password':
      case 'INVALID_LOGIN_CREDENTIALS':
      case 'invalid-credential':
        message = 'Email atau password salah.';
      case 'user-not-found':
        message = 'Akun tidak ditemukan.';
      case 'email-already-in-use':
        message = 'Email sudah terdaftar. Silakan gunakan email lain.';
      case 'weak-password':
        message =
            'Password terlalu lemah. Gunakan minimal 6 karakter.';
      case 'invalid-email':
        message = 'Format email tidak valid.';
      case 'user-disabled':
        message = 'Akun ini telah dinonaktifkan. Hubungi administrator.';
      case 'too-many-requests':
        message =
            'Terlalu banyak percobaan. Silakan coba lagi nanti.';
      case 'operation-not-allowed':
        message = 'Metode login ini tidak diizinkan.';
      case 'network-request-failed':
        message =
            'Koneksi internet bermasalah. Periksa jaringan Anda.';
      case 'requires-recent-login':
        message = 'Silakan login ulang untuk melanjutkan.';
      case 'expired-action-code':
        message = 'Kode verifikasi sudah kadaluarsa.';
      case 'invalid-action-code':
        message = 'Kode verifikasi tidak valid.';
      default:
        message =
            'Terjadi kesalahan autentikasi. Silakan coba lagi. (${e.code})';
    }
    return AuthException(message, code: e.code);
  }
}
