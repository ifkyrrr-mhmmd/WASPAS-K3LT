import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../config/theme.dart';

/// Reusable avatar widget for displaying K3LT preset safety avatars,
/// network image URLs, or fallback display name initials.
class UserAvatar extends StatelessWidget {
  final UserModel user;
  final double radius;
  final double iconSize;

  const UserAvatar({
    Key? key,
    required this.user,
    this.radius = 24,
    this.iconSize = 20,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final photoUrl = user.photoUrl;
    final displayName = user.displayName;

    if (photoUrl != null && photoUrl.startsWith('preset:')) {
      final presetName = photoUrl.replaceFirst('preset:', '');
      IconData icon;
      Color color;
      switch (presetName) {
        case 'engineering':
          icon = Icons.engineering_rounded;
          color = Colors.orange.shade700;
          break;
        case 'construction':
          icon = Icons.construction_rounded;
          color = Colors.green.shade700;
          break;
        case 'security':
          icon = Icons.security_rounded;
          color = Colors.blue.shade700;
          break;
        case 'leader':
          icon = Icons.admin_panel_settings_rounded;
          color = AppColors.teal;
          break;
        case 'hazard':
          icon = Icons.warning_amber_rounded;
          color = Colors.amber.shade800;
          break;
        case 'transport':
          icon = Icons.local_shipping_rounded;
          color = Colors.purple.shade700;
          break;
        default:
          icon = Icons.person_rounded;
          color = AppColors.teal;
      }
      return CircleAvatar(
        radius: radius,
        backgroundColor: color,
        child: Icon(icon, size: iconSize, color: Colors.white),
      );
    } else if (photoUrl != null && photoUrl.trim().isNotEmpty) {
      return CircleAvatar(
        radius: radius,
        backgroundImage: NetworkImage(photoUrl),
        backgroundColor: AppColors.teal.withOpacity(0.2),
      );
    } else {
      return CircleAvatar(
        radius: radius,
        backgroundColor: AppColors.teal,
        child: Text(
          displayName.isNotEmpty ? displayName[0].toUpperCase() : 'U',
          style: TextStyle(
            fontSize: iconSize,
            color: Colors.white,
            fontWeight: FontWeight.bold,
          ),
        ),
      );
    }
  }
}
