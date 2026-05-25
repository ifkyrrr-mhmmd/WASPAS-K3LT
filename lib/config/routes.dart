import 'package:flutter/material.dart';

// Screen imports – these files will be created by other developers.
import '../screens/splash_screen.dart';
import '../screens/login_screen.dart';
import '../screens/register_screen.dart';
import '../screens/dashboard_screen.dart';
import '../screens/calculator/calculator_screen.dart';
import '../screens/history_screen.dart';
import '../screens/sensitivity_screen.dart';
import '../screens/profile_screen.dart';

/// Centralized route configuration for the SPK K3LT application.
///
/// Contains all named route constants and a [generateRoute] factory
/// that builds [MaterialPageRoute]s with custom slide transitions.
///
/// Usage:
/// ```dart
/// Navigator.pushNamed(context, AppRoutes.dashboard);
/// ```
class AppRoutes {
  AppRoutes._();

  // ── Route Name Constants ─────────────────────────────────────────────
  /// Splash / loading screen shown at app startup.
  static const String splash = '/splash';

  /// Login screen for authentication.
  static const String login = '/login';

  /// Registration screen for new users.
  static const String register = '/register';

  /// Main dashboard after authentication.
  static const String dashboard = '/dashboard';

  /// WASPAS calculator screen.
  static const String calculator = '/calculator';

  /// Calculation history screen.
  static const String history = '/history';

  /// Sensitivity analysis screen.
  static const String sensitivity = '/sensitivity';

  /// User profile / settings screen.
  static const String profile = '/profile';

  /// The initial route when the app launches.
  static const String initial = splash;

  /// Generates a [Route] for the given [RouteSettings].
  ///
  /// Returns a [PageRouteBuilder] with a slide-up transition for most
  /// screens, or a fade transition for the splash screen. If an unknown
  /// route is requested, a 404-style error page is returned.
  static Route<dynamic> generateRoute(RouteSettings settings) {
    switch (settings.name) {
      case splash:
        return _buildFadeRoute(
          const SplashScreen(),
          settings,
        );

      case login:
        return _buildSlideRoute(
          const LoginScreen(),
          settings,
        );

      case register:
        return _buildSlideRoute(
          const RegisterScreen(),
          settings,
        );

      case dashboard:
        return _buildFadeRoute(
          const DashboardScreen(),
          settings,
        );

      case calculator:
        return _buildSlideRoute(
          const CalculatorScreen(),
          settings,
        );

      case history:
        return _buildSlideRoute(
          const HistoryScreen(),
          settings,
        );

      case sensitivity:
        return _buildSlideRoute(
          const SensitivityScreen(),
          settings,
        );

      case profile:
        return _buildSlideRoute(
          const ProfileScreen(),
          settings,
        );

      default:
        return _buildFadeRoute(
          _UnknownRouteScreen(routeName: settings.name),
          settings,
        );
    }
  }

  /// Creates a [PageRouteBuilder] with a vertical slide-up + fade transition.
  static PageRouteBuilder<dynamic> _buildSlideRoute(
    Widget page,
    RouteSettings settings,
  ) {
    return PageRouteBuilder(
      settings: settings,
      pageBuilder: (context, animation, secondaryAnimation) => page,
      transitionsBuilder: (context, animation, secondaryAnimation, child) {
        const begin = Offset(0.0, 0.05);
        const end = Offset.zero;
        const curve = Curves.easeOutCubic;

        final tween = Tween(begin: begin, end: end).chain(
          CurveTween(curve: curve),
        );
        final fadeTween = Tween<double>(begin: 0.0, end: 1.0).chain(
          CurveTween(curve: curve),
        );

        return FadeTransition(
          opacity: animation.drive(fadeTween),
          child: SlideTransition(
            position: animation.drive(tween),
            child: child,
          ),
        );
      },
      transitionDuration: const Duration(milliseconds: 300),
      reverseTransitionDuration: const Duration(milliseconds: 250),
    );
  }

  /// Creates a [PageRouteBuilder] with a simple fade transition.
  static PageRouteBuilder<dynamic> _buildFadeRoute(
    Widget page,
    RouteSettings settings,
  ) {
    return PageRouteBuilder(
      settings: settings,
      pageBuilder: (context, animation, secondaryAnimation) => page,
      transitionsBuilder: (context, animation, secondaryAnimation, child) {
        return FadeTransition(
          opacity: animation.drive(
            CurveTween(curve: Curves.easeIn),
          ),
          child: child,
        );
      },
      transitionDuration: const Duration(milliseconds: 400),
      reverseTransitionDuration: const Duration(milliseconds: 300),
    );
  }
}

/// Fallback screen shown when an unregistered route is navigated to.
class _UnknownRouteScreen extends StatelessWidget {
  /// The route name that was not found.
  final String? routeName;

  const _UnknownRouteScreen({this.routeName});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Halaman Tidak Ditemukan')),
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(
                Icons.error_outline_rounded,
                size: 64,
                color: Theme.of(context).colorScheme.error,
              ),
              const SizedBox(height: 16),
              Text(
                'Rute "$routeName" tidak ditemukan.',
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.titleMedium,
              ),
              const SizedBox(height: 24),
              ElevatedButton.icon(
                onPressed: () => Navigator.of(context).pop(),
                icon: const Icon(Icons.arrow_back),
                label: const Text('Kembali'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
