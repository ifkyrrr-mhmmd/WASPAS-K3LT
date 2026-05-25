/// Form validation utilities for the SPK K3LT application.
///
/// All validator methods follow the Flutter [FormField] convention:
/// - Return `null` if the input is valid.
/// - Return an error message [String] in Indonesian if invalid.
class Validators {
  Validators._();

  /// Email regex pattern for basic validation.
  static final RegExp _emailRegex = RegExp(
    r'^[a-zA-Z0-9.!#$%&*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}'
    r'[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}'
    r'[a-zA-Z0-9])?)*$',
  );

  /// Validates an email address.
  ///
  /// Rules:
  /// - Must not be empty.
  /// - Must match a basic email pattern.
  ///
  /// Returns `null` if valid, or an Indonesian error message if invalid.
  static String? validateEmail(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Email tidak boleh kosong.';
    }
    final trimmed = value.trim();
    if (!_emailRegex.hasMatch(trimmed)) {
      return 'Format email tidak valid.';
    }
    return null;
  }

  /// Validates a password.
  ///
  /// Rules:
  /// - Must not be empty.
  /// - Must be at least 6 characters long.
  ///
  /// Returns `null` if valid, or an Indonesian error message if invalid.
  static String? validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return 'Password tidak boleh kosong.';
    }
    if (value.length < 6) {
      return 'Password minimal 6 karakter.';
    }
    return null;
  }

  /// Validates a display name or general name field.
  ///
  /// Rules:
  /// - Must not be empty.
  /// - Must be at least 2 characters long (after trimming).
  ///
  /// Returns `null` if valid, or an Indonesian error message if invalid.
  static String? validateName(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Nama tidak boleh kosong.';
    }
    if (value.trim().length < 2) {
      return 'Nama minimal 2 karakter.';
    }
    return null;
  }

  /// Validates a criterion weight value (entered as a string).
  ///
  /// Rules:
  /// - Must not be empty.
  /// - Must be a valid number.
  /// - Must be greater than 0.
  /// - Must be less than or equal to 1.
  ///
  /// Returns `null` if valid, or an Indonesian error message if invalid.
  static String? validateWeight(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Bobot tidak boleh kosong.';
    }
    final parsed = double.tryParse(value.trim());
    if (parsed == null) {
      return 'Bobot harus berupa angka.';
    }
    if (parsed <= 0) {
      return 'Bobot harus lebih besar dari 0.';
    }
    if (parsed > 1) {
      return 'Bobot tidak boleh lebih dari 1.';
    }
    return null;
  }

  /// Validates a criterion/alternative value (entered as a string).
  ///
  /// Rules:
  /// - Must not be empty.
  /// - Must be a valid number.
  /// - Must be greater than 0.
  ///
  /// Returns `null` if valid, or an Indonesian error message if invalid.
  static String? validateValue(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Nilai tidak boleh kosong.';
    }
    final parsed = double.tryParse(value.trim());
    if (parsed == null) {
      return 'Nilai harus berupa angka.';
    }
    if (parsed <= 0) {
      return 'Nilai harus lebih besar dari 0.';
    }
    return null;
  }

  /// Validates that a list of weights sums to 1.0 within a tolerance.
  ///
  /// The allowed tolerance is ±0.005, so a sum between 0.995 and 1.005
  /// is considered valid.
  ///
  /// Returns `true` if the total is valid, `false` otherwise.
  static bool validateTotalWeight(List<double> weights) {
    if (weights.isEmpty) return false;
    final sum = weights.fold<double>(0.0, (acc, w) => acc + w);
    return (sum - 1.0).abs() <= 0.005;
  }

  /// Returns a human-readable error message for total weight validation.
  ///
  /// Returns `null` if the total weight is valid, or an Indonesian error
  /// message if the sum deviates from 1.0 beyond tolerance.
  static String? validateTotalWeightMessage(List<double> weights) {
    if (weights.isEmpty) {
      return 'Setidaknya satu bobot harus ditentukan.';
    }
    final sum = weights.fold<double>(0.0, (acc, w) => acc + w);
    if ((sum - 1.0).abs() > 0.005) {
      return 'Total bobot harus sama dengan 1.0. '
          'Saat ini: ${sum.toStringAsFixed(3)}.';
    }
    return null;
  }
}
