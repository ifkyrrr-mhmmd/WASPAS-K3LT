/// Authentication state provider for SPK WASPAS K3LT.
///
/// Manages the authentication lifecycle — login, registration, logout,
/// and password reset — while keeping the [UserModel] profile in sync
/// with Firestore. Exposes reactive state via [ChangeNotifier].
library;

import 'package:flutter/material.dart';

import '../models/user_model.dart';
import '../services/auth_service.dart';
import '../services/firestore_service.dart';

/// Provides authentication state to the widget tree via [Provider].
///
/// Example:
/// ```dart
/// final authProvider = context.read<AuthProvider>();
/// final success = await authProvider.login(email, password);
/// ```
class AuthProvider extends ChangeNotifier {
  /// Creates an [AuthProvider].
  ///
  /// Optionally accepts pre-built service instances for testing.
  AuthProvider({
    AuthService? authService,
    FirestoreService? firestoreService,
  })  : _authService = authService ?? AuthService(),
        _firestoreService = firestoreService ?? FirestoreService();

  final AuthService _authService;
  final FirestoreService _firestoreService;

  // ---------------------------------------------------------------------------
  // State
  // ---------------------------------------------------------------------------

  /// The currently authenticated user's profile, or `null` if logged out.
  UserModel? _currentUser;
  UserModel? get currentUser => _currentUser;

  /// Whether an authentication operation is in progress.
  bool _isLoading = false;
  bool get isLoading => _isLoading;

  /// The most recent error message, or `null` if no error.
  String? _errorMessage;
  String? get errorMessage => _errorMessage;

  /// Whether a user is currently authenticated and a profile is loaded.
  bool get isAuthenticated => _currentUser != null;

  // ---------------------------------------------------------------------------
  // Login
  // ---------------------------------------------------------------------------

  /// Signs in with [email] and [password].
  ///
  /// On success, the user profile is fetched from Firestore and
  /// [currentUser] is populated. Returns `true` on success, `false`
  /// on failure (with [errorMessage] set).
  Future<bool> login(String email, String password) async {
    _setLoading(true);
    _clearError();

    try {
      final credential = await _authService.signInWithEmail(email, password);
      final uid = credential.user!.uid;

      // Fetch the existing Firestore profile.
      final profile = await _firestoreService.getUserProfile(uid);
      if (profile != null) {
        _currentUser = profile;
        // Update last-login timestamp in background.
        _firestoreService.updateLastLogin(uid);
      } else {
        // Edge case: Firebase Auth user exists but no Firestore profile.
        // Create a minimal profile so the app can proceed.
        final newUser = UserModel(
          uid: uid,
          email: email.trim(),
          displayName: credential.user?.displayName ?? 'User',
          role: 'viewer',
          createdAt: DateTime.now(),
          lastLoginAt: DateTime.now(),
        );
        await _firestoreService.createUserProfile(newUser);
        _currentUser = newUser;
      }

      _setLoading(false);
      return true;
    } on AuthException catch (e) {
      _setError(e.message);
      return false;
    } catch (e) {
      _setError('Terjadi kesalahan saat login. Silakan coba lagi.');
      return false;
    }
  }

  /// Signs in using Google Sign-In (web compatible).
  Future<bool> loginWithGoogle() async {
    _setLoading(true);
    _clearError();

    try {
      final credential = await _authService.signInWithGoogle();
      final uid = credential.user!.uid;

      // Fetch the existing Firestore profile or create a new one.
      final profile = await _firestoreService.getUserProfile(uid);
      if (profile != null) {
        _currentUser = profile;
        _firestoreService.updateLastLogin(uid);
      } else {
        final newUser = UserModel(
          uid: uid,
          email: credential.user?.email ?? '',
          displayName: credential.user?.displayName ?? 'User Google',
          role: 'assessor',
          createdAt: DateTime.now(),
          lastLoginAt: DateTime.now(),
        );
        await _firestoreService.createUserProfile(newUser);
        _currentUser = newUser;
      }

      _setLoading(false);
      return true;
    } on AuthException catch (e) {
      _setError(e.message);
      return false;
    } catch (e) {
      _setError('Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
      return false;
    }
  }

  /// Updates the currently authenticated user's profile.
  ///
  /// Updates both Firestore and local state. Returns `true` on success,
  /// `false` on failure.
  Future<bool> updateProfile({
    String? displayName,
    String? photoUrl,
  }) async {
    if (_currentUser == null) return false;
    _setLoading(true);
    _clearError();

    try {
      final uid = _currentUser!.uid;
      final updates = <String, dynamic>{};
      
      if (displayName != null && displayName.trim().isNotEmpty) {
        updates['displayName'] = displayName.trim();
        await _authService.currentUser?.updateDisplayName(displayName.trim());
      }
      
      if (photoUrl != null) {
        updates['photoUrl'] = photoUrl;
        await _authService.currentUser?.updatePhotoURL(photoUrl);
      }

      if (updates.isNotEmpty) {
        await _firestoreService.updateUserProfile(uid, updates);
        _currentUser = _currentUser!.copyWith(
          displayName: displayName != null && displayName.trim().isNotEmpty 
              ? displayName.trim() 
              : _currentUser!.displayName,
          photoUrl: photoUrl ?? _currentUser!.photoUrl,
        );
      }
      
      _setLoading(false);
      return true;
    } catch (e) {
      _setError('Gagal memperbarui profil: $e');
      return false;
    }
  }

  // ---------------------------------------------------------------------------
  // Register
  // ---------------------------------------------------------------------------

  /// Registers a new user with [email], [password], and [displayName].
  ///
  /// A new Firestore user profile is created with the `assessor` role.
  /// Returns `true` on success, `false` on failure (with [errorMessage] set).
  Future<bool> register(
    String email,
    String password,
    String displayName,
  ) async {
    _setLoading(true);
    _clearError();

    try {
      final credential = await _authService.registerWithEmail(
        email,
        password,
        displayName,
      );
      final uid = credential.user!.uid;

      final newUser = UserModel(
        uid: uid,
        email: email.trim(),
        displayName: displayName.trim(),
        role: 'assessor',
        createdAt: DateTime.now(),
        lastLoginAt: DateTime.now(),
      );

      await _firestoreService.createUserProfile(newUser);
      _currentUser = newUser;

      _setLoading(false);
      return true;
    } on AuthException catch (e) {
      _setError(e.message);
      return false;
    } catch (e) {
      _setError('Terjadi kesalahan saat registrasi. Silakan coba lagi.');
      return false;
    }
  }

  // ---------------------------------------------------------------------------
  // Logout
  // ---------------------------------------------------------------------------

  /// Signs out the current user and clears all local state.
  Future<void> logout() async {
    _setLoading(true);
    try {
      await _authService.signOut();
    } catch (_) {
      // Swallow sign-out errors — we clear local state regardless.
    }
    _currentUser = null;
    _setLoading(false);
  }

  // ---------------------------------------------------------------------------
  // Password Reset
  // ---------------------------------------------------------------------------

  /// Sends a password-reset email to [email].
  ///
  /// Sets [errorMessage] on failure.
  Future<void> resetPassword(String email) async {
    _setLoading(true);
    _clearError();

    try {
      await _authService.resetPassword(email);
      _setLoading(false);
    } on AuthException catch (e) {
      _setError(e.message);
    } catch (e) {
      _setError('Gagal mengirim email reset password.');
    }
  }

  // ---------------------------------------------------------------------------
  // Auth State Check
  // ---------------------------------------------------------------------------

  /// Checks whether a user is already signed in (e.g. on app startup).
  ///
  /// If a Firebase Auth user exists, the corresponding Firestore profile
  /// is loaded into [currentUser]. If the profile is missing a minimal
  /// one is created automatically.
  Future<void> checkAuthState() async {
    _setLoading(true);

    try {
      final firebaseUser = _authService.currentUser;
      if (firebaseUser == null) {
        _currentUser = null;
        _setLoading(false);
        return;
      }

      final profile =
          await _firestoreService.getUserProfile(firebaseUser.uid);
      if (profile != null) {
        _currentUser = profile;
      } else {
        // Create a fallback profile from Firebase Auth data.
        final newUser = UserModel(
          uid: firebaseUser.uid,
          email: firebaseUser.email ?? '',
          displayName: firebaseUser.displayName ?? 'User',
          role: 'viewer',
          createdAt: DateTime.now(),
          lastLoginAt: DateTime.now(),
        );
        await _firestoreService.createUserProfile(newUser);
        _currentUser = newUser;
      }
    } catch (e) {
      _currentUser = null;
    }

    _setLoading(false);
  }

  // ---------------------------------------------------------------------------
  // Error helpers
  // ---------------------------------------------------------------------------

  /// Clears the current error message. Call from the UI when the user
  /// dismisses an error dialog.
  void clearError() => _clearError();

  void _clearError() {
    if (_errorMessage != null) {
      _errorMessage = null;
      notifyListeners();
    }
  }

  void _setError(String message) {
    _errorMessage = message;
    _isLoading = false;
    notifyListeners();
  }

  void _setLoading(bool value) {
    _isLoading = value;
    notifyListeners();
  }
}
