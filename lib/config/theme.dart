import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

/// Application color palette for SPK Seleksi Kepala Divisi K3LT.
///
/// The palette consists of four primary colors:
/// - **Deep** (#281C59): Primary brand color, used for AppBar, buttons, and emphasis.
/// - **Teal** (#4E8D9C): Secondary color for accents and supporting elements.
/// - **Mint** (#85C79A): Success/positive states and accents.
/// - **Cream** (#EDF7BD): Highlights, backgrounds, and subtle surfaces.
class AppColors {
  AppColors._();

  // ── Primary: Deep ──────────────────────────────────────────────────────
  /// Deep purple – main brand color.
  static const Color deep = Color(0xFF281C59);

  /// Lighter shade of deep for hover/splash states.
  static const Color deepLight = Color(0xFF3D2E80);

  /// Darker shade of deep for pressed states.
  static const Color deepDark = Color(0xFF1A1240);

  /// Very light tint of deep for surface backgrounds.
  static const Color deepSurface = Color(0xFFEAE6F5);

  // ── Secondary: Teal ────────────────────────────────────────────────────
  /// Teal – secondary brand color.
  static const Color teal = Color(0xFF4E8D9C);

  /// Lighter shade of teal.
  static const Color tealLight = Color(0xFF6DAAB8);

  /// Darker shade of teal.
  static const Color tealDark = Color(0xFF376A76);

  /// Very light tint of teal for surface backgrounds.
  static const Color tealSurface = Color(0xFFE3F1F4);

  // ── Accent: Mint ───────────────────────────────────────────────────────
  /// Mint green – accent/success color.
  static const Color mint = Color(0xFF85C79A);

  /// Lighter shade of mint.
  static const Color mintLight = Color(0xFFA8D9B6);

  /// Darker shade of mint.
  static const Color mintDark = Color(0xFF5FA876);

  /// Very light tint of mint for surface backgrounds.
  static const Color mintSurface = Color(0xFFE8F5ED);

  // ── Highlight: Cream ───────────────────────────────────────────────────
  /// Cream – highlight/background color.
  static const Color cream = Color(0xFFEDF7BD);

  /// Lighter shade of cream.
  static const Color creamLight = Color(0xFFF5FBDB);

  /// Darker shade of cream (for contrast text on cream).
  static const Color creamDark = Color(0xFFD4E08A);

  // ── Extra Colors for UI ────────────────────────────────────────────────
  static const Color gold = Color(0xFFFFD700);
  static const Color silver = Color(0xFFC0C0C0);
  static const Color bronze = Color(0xFFCD7F32);
  static const Color background = Color(0xFFF8F9FC);
  static const Color textHint = Color(0xFF9E9E9E);
  static const Color textMuted = Color(0xFF757575);
  static const Color borderLight = Color(0xFFEEEEEE);
  static const Color cardShadow = Color(0x14281C59); // deep with 8% opacity
  
  static const LinearGradient accentGradient = LinearGradient(
    colors: [teal, mint],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  // ── Semantic Colors ────────────────────────────────────────────────────
  /// Error / destructive action color.
  static const Color error = Color(0xFFD32F2F);

  /// Error surface / background.
  static const Color errorSurface = Color(0xFFFDE8E8);

  /// Warning color.
  static const Color warning = Color(0xFFF9A825);

  /// Warning surface / background.
  static const Color warningSurface = Color(0xFFFFF8E1);

  /// Info color.
  static const Color info = Color(0xFF1976D2);

  /// General text on light backgrounds.
  static const Color textPrimary = Color(0xFF1C1B1F);

  /// Secondary/muted text on light backgrounds.
  static const Color textSecondary = Color(0xFF5F5F6B);

  /// Disabled text or icon color.
  static const Color textDisabled = Color(0xFF9E9E9E);

  /// Divider/border color.
  static const Color divider = Color(0xFFE0E0E0);

  /// Scaffold background.
  static const Color scaffoldBackground = Color(0xFFF8F9FC);

  /// Card / surface background.
  static const Color surface = Color(0xFFFFFFFF);

  /// Material color swatch generated from [deep].
  static const MaterialColor deepSwatch = MaterialColor(0xFF281C59, {
    50: Color(0xFFEAE6F5),
    100: Color(0xFFC9C0E5),
    200: Color(0xFFA697D4),
    300: Color(0xFF826DC3),
    400: Color(0xFF674DB6),
    500: Color(0xFF4C2DA9),
    600: Color(0xFF4428A2),
    700: Color(0xFF392198),
    800: Color(0xFF2F1B8F),
    900: Color(0xFF281C59),
  });
}

class AppRadius {
  static const double sm = 8.0;
  static const double md = 12.0;
  static const double lg = 16.0;
  static const double xl = 24.0;
  static const double xxl = 32.0;
}

/// Application theme configuration.
///
/// Provides a fully configured [ThemeData] using the [AppColors] palette
/// and Google Fonts **Inter** for all text styles.
class AppTheme {
  AppTheme._();

  /// The light theme for the application.
  static ThemeData get light {
    final baseTextTheme = GoogleFonts.interTextTheme();

    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.light,
      colorScheme: ColorScheme.fromSeed(
        seedColor: AppColors.deep,
        primary: AppColors.deep,
        onPrimary: Colors.white,
        secondary: AppColors.teal,
        onSecondary: Colors.white,
        tertiary: AppColors.mint,
        onTertiary: Colors.white,
        error: AppColors.error,
        onError: Colors.white,
        surface: AppColors.surface,
        onSurface: AppColors.textPrimary,
      ),
      scaffoldBackgroundColor: AppColors.scaffoldBackground,
      dividerColor: AppColors.divider,

      // ── Text Theme ───────────────────────────────────────────────────
      textTheme: baseTextTheme.copyWith(
        displayLarge: baseTextTheme.displayLarge?.copyWith(
          color: AppColors.deep,
          fontWeight: FontWeight.w700,
        ),
        displayMedium: baseTextTheme.displayMedium?.copyWith(
          color: AppColors.deep,
          fontWeight: FontWeight.w700,
        ),
        displaySmall: baseTextTheme.displaySmall?.copyWith(
          color: AppColors.deep,
          fontWeight: FontWeight.w600,
        ),
        headlineLarge: baseTextTheme.headlineLarge?.copyWith(
          color: AppColors.textPrimary,
          fontWeight: FontWeight.w700,
        ),
        headlineMedium: baseTextTheme.headlineMedium?.copyWith(
          color: AppColors.textPrimary,
          fontWeight: FontWeight.w600,
        ),
        headlineSmall: baseTextTheme.headlineSmall?.copyWith(
          color: AppColors.textPrimary,
          fontWeight: FontWeight.w600,
        ),
        titleLarge: baseTextTheme.titleLarge?.copyWith(
          color: AppColors.textPrimary,
          fontWeight: FontWeight.w600,
        ),
        titleMedium: baseTextTheme.titleMedium?.copyWith(
          color: AppColors.textPrimary,
          fontWeight: FontWeight.w500,
        ),
        titleSmall: baseTextTheme.titleSmall?.copyWith(
          color: AppColors.textSecondary,
          fontWeight: FontWeight.w500,
        ),
        bodyLarge: baseTextTheme.bodyLarge?.copyWith(
          color: AppColors.textPrimary,
        ),
        bodyMedium: baseTextTheme.bodyMedium?.copyWith(
          color: AppColors.textPrimary,
        ),
        bodySmall: baseTextTheme.bodySmall?.copyWith(
          color: AppColors.textSecondary,
        ),
        labelLarge: baseTextTheme.labelLarge?.copyWith(
          color: AppColors.textPrimary,
          fontWeight: FontWeight.w600,
        ),
        labelMedium: baseTextTheme.labelMedium?.copyWith(
          color: AppColors.textSecondary,
          fontWeight: FontWeight.w500,
        ),
        labelSmall: baseTextTheme.labelSmall?.copyWith(
          color: AppColors.textSecondary,
        ),
      ),

      // ── AppBar Theme ─────────────────────────────────────────────────
      appBarTheme: AppBarTheme(
        elevation: 0,
        scrolledUnderElevation: 2,
        backgroundColor: AppColors.deep,
        foregroundColor: Colors.white,
        centerTitle: true,
        titleTextStyle: GoogleFonts.inter(
          fontSize: 18,
          fontWeight: FontWeight.w600,
          color: Colors.white,
        ),
        iconTheme: const IconThemeData(color: Colors.white),
        actionsIconTheme: const IconThemeData(color: Colors.white),
      ),

      // ── Card Theme ───────────────────────────────────────────────────
      cardTheme: CardThemeData(
        elevation: 1,
        shadowColor: AppColors.deep.withValues(alpha: 0.08),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
        ),
        color: AppColors.surface,
        surfaceTintColor: Colors.transparent,
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      ),

      // ── Elevated Button Theme ────────────────────────────────────────
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.deep,
          foregroundColor: Colors.white,
          elevation: 2,
          shadowColor: AppColors.deep.withValues(alpha: 0.3),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          textStyle: GoogleFonts.inter(
            fontSize: 15,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),

      // ── Outlined Button Theme ────────────────────────────────────────
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.deep,
          side: const BorderSide(color: AppColors.deep, width: 1.5),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          textStyle: GoogleFonts.inter(
            fontSize: 15,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),

      // ── Text Button Theme ────────────────────────────────────────────
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: AppColors.teal,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(8),
          ),
          textStyle: GoogleFonts.inter(
            fontSize: 14,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),

      // ── Floating Action Button Theme ─────────────────────────────────
      floatingActionButtonTheme: const FloatingActionButtonThemeData(
        backgroundColor: AppColors.teal,
        foregroundColor: Colors.white,
        elevation: 4,
        shape: CircleBorder(),
      ),

      // ── Input Decoration Theme ───────────────────────────────────────
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: AppColors.scaffoldBackground,
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 14,
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.divider),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.divider),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.teal, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.error),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.error, width: 2),
        ),
        labelStyle: GoogleFonts.inter(
          color: AppColors.textSecondary,
          fontSize: 14,
        ),
        hintStyle: GoogleFonts.inter(
          color: AppColors.textDisabled,
          fontSize: 14,
        ),
        errorStyle: GoogleFonts.inter(
          color: AppColors.error,
          fontSize: 12,
        ),
        prefixIconColor: AppColors.teal,
        suffixIconColor: AppColors.textSecondary,
      ),

      // ── Chip Theme ───────────────────────────────────────────────────
      chipTheme: ChipThemeData(
        backgroundColor: AppColors.deepSurface,
        selectedColor: AppColors.deep,
        labelStyle: GoogleFonts.inter(
          fontSize: 13,
          fontWeight: FontWeight.w500,
          color: AppColors.deep,
        ),
        secondaryLabelStyle: GoogleFonts.inter(
          fontSize: 13,
          fontWeight: FontWeight.w500,
          color: Colors.white,
        ),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
        ),
        side: BorderSide.none,
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      ),

      // ── Bottom Navigation Bar Theme ──────────────────────────────────
      bottomNavigationBarTheme: BottomNavigationBarThemeData(
        backgroundColor: AppColors.surface,
        selectedItemColor: AppColors.deep,
        unselectedItemColor: AppColors.textDisabled,
        selectedLabelStyle: GoogleFonts.inter(
          fontSize: 12,
          fontWeight: FontWeight.w600,
        ),
        unselectedLabelStyle: GoogleFonts.inter(
          fontSize: 12,
          fontWeight: FontWeight.w400,
        ),
        type: BottomNavigationBarType.fixed,
        elevation: 8,
      ),

      // ── Dialog Theme ─────────────────────────────────────────────────
      dialogTheme: DialogThemeData(
        backgroundColor: AppColors.surface,
        elevation: 8,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
        ),
        titleTextStyle: GoogleFonts.inter(
          fontSize: 20,
          fontWeight: FontWeight.w600,
          color: AppColors.textPrimary,
        ),
        contentTextStyle: GoogleFonts.inter(
          fontSize: 14,
          color: AppColors.textSecondary,
        ),
      ),

      // ── SnackBar Theme ───────────────────────────────────────────────
      snackBarTheme: SnackBarThemeData(
        backgroundColor: AppColors.deepDark,
        contentTextStyle: GoogleFonts.inter(
          fontSize: 14,
          color: Colors.white,
        ),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        behavior: SnackBarBehavior.floating,
      ),

      // ── Divider Theme ────────────────────────────────────────────────
      dividerTheme: const DividerThemeData(
        color: AppColors.divider,
        thickness: 1,
        space: 1,
      ),

      // ── Tab Bar Theme ────────────────────────────────────────────────
      tabBarTheme: TabBarThemeData(
        labelColor: Colors.white,
        unselectedLabelColor: Colors.white70,
        indicatorColor: AppColors.mint,
        labelStyle: GoogleFonts.inter(
          fontSize: 14,
          fontWeight: FontWeight.w600,
        ),
        unselectedLabelStyle: GoogleFonts.inter(
          fontSize: 14,
          fontWeight: FontWeight.w400,
        ),
      ),
    );
  }
}
