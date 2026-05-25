import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../config/theme.dart';

/// Kartu hasil ranking per alternatif.
/// Badge emas (#1), perak (#2), perunggu (#3), dan deep-muted lainnya.
class RankingCard extends StatelessWidget {
  final int rank;
  final String name;
  final double q1;
  final double q2;
  final double qi;
  final double maxQi;

  const RankingCard({
    super.key,
    required this.rank,
    required this.name,
    required this.q1,
    required this.q2,
    required this.qi,
    required this.maxQi,
  });

  Color get _badgeColor {
    switch (rank) {
      case 1:
        return AppColors.gold;
      case 2:
        return AppColors.silver;
      case 3:
        return AppColors.bronze;
      default:
        return AppColors.deep.withValues(alpha: 0.4);
    }
  }

  Color get _badgeTextColor {
    switch (rank) {
      case 1:
        return const Color(0xFF5D4700);
      case 2:
        return const Color(0xFF4A4A4A);
      case 3:
        return Colors.white;
      default:
        return Colors.white;
    }
  }

  @override
  Widget build(BuildContext context) {
    final progress = maxQi > 0 ? (qi / maxQi).clamp(0.0, 1.0) : 0.0;
    final isWinner = rank == 1;

    return TweenAnimationBuilder<double>(
      tween: Tween(begin: 0.0, end: 1.0),
      duration: Duration(milliseconds: 400 + (rank * 100)),
      curve: Curves.easeOutCubic,
      builder: (context, value, child) {
        return Opacity(
          opacity: value,
          child: Transform.translate(
            offset: Offset(0, 20 * (1 - value)),
            child: child,
          ),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        decoration: BoxDecoration(
          color: AppColors.surface,
          borderRadius: BorderRadius.circular(AppRadius.lg),
          border: isWinner
              ? Border.all(color: AppColors.gold.withValues(alpha: 0.5), width: 2)
              : Border.all(color: AppColors.divider, width: 1),
          boxShadow: [
            if (isWinner)
              BoxShadow(
                color: AppColors.gold.withValues(alpha: 0.15),
                blurRadius: 16,
                offset: const Offset(0, 4),
              ),
            BoxShadow(
              color: AppColors.cardShadow,
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              Row(
                children: [
                  // Rank Badge
                  Container(
                    width: 42,
                    height: 42,
                    decoration: BoxDecoration(
                      color: _badgeColor,
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(
                          color: _badgeColor.withValues(alpha: 0.4),
                          blurRadius: 8,
                          offset: const Offset(0, 2),
                        ),
                      ],
                    ),
                    child: Center(
                      child: Text(
                        '#$rank',
                        style: GoogleFonts.inter(
                          fontSize: 15,
                          fontWeight: FontWeight.w800,
                          color: _badgeTextColor,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 14),
                  // Name & winner crown
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            if (isWinner) ...[
                              const Icon(
                                Icons.emoji_events_rounded,
                                color: AppColors.gold,
                                size: 18,
                              ),
                              const SizedBox(width: 6),
                            ],
                            Expanded(
                              child: Text(
                                name,
                                style: GoogleFonts.inter(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w700,
                                  color: AppColors.textPrimary,
                                ),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 2),
                        if (isWinner)
                          Text(
                            'Alternatif Terbaik',
                            style: GoogleFonts.inter(
                              fontSize: 11,
                              fontWeight: FontWeight.w600,
                              color: AppColors.gold,
                              letterSpacing: 0.5,
                            ),
                          ),
                      ],
                    ),
                  ),
                  // Qi Score
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(
                        'Qi',
                        style: GoogleFonts.inter(
                          fontSize: 10,
                          fontWeight: FontWeight.w500,
                          color: AppColors.textHint,
                          letterSpacing: 0.5,
                        ),
                      ),
                      Text(
                        qi.toStringAsFixed(4),
                        style: GoogleFonts.inter(
                          fontSize: 18,
                          fontWeight: FontWeight.w800,
                          color: isWinner ? AppColors.teal : AppColors.textPrimary,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
              const SizedBox(height: 14),
              // Q1 & Q2 row
              Row(
                children: [
                  _ScoreChip(label: 'Q1 (WSM)', value: q1),
                  const SizedBox(width: 8),
                  _ScoreChip(label: 'Q2 (WPM)', value: q2),
                ],
              ),
              const SizedBox(height: 12),
              // Progress bar
              _AnimatedProgressBar(
                progress: progress,
                color: _badgeColor,
                isWinner: isWinner,
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _ScoreChip extends StatelessWidget {
  final String label;
  final double value;

  const _ScoreChip({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
        decoration: BoxDecoration(
          color: AppColors.background,
          borderRadius: BorderRadius.circular(8),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              label,
              style: GoogleFonts.inter(
                fontSize: 10,
                fontWeight: FontWeight.w500,
                color: AppColors.textHint,
              ),
            ),
            Text(
              value.toStringAsFixed(4),
              style: GoogleFonts.inter(
                fontSize: 12,
                fontWeight: FontWeight.w700,
                color: AppColors.textPrimary,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _AnimatedProgressBar extends StatelessWidget {
  final double progress;
  final Color color;
  final bool isWinner;

  const _AnimatedProgressBar({
    required this.progress,
    required this.color,
    required this.isWinner,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Text(
          '${(progress * 100).toStringAsFixed(1)}%',
          style: GoogleFonts.inter(
            fontSize: 10,
            fontWeight: FontWeight.w600,
            color: AppColors.textHint,
          ),
        ),
        const SizedBox(height: 4),
        ClipRRect(
          borderRadius: BorderRadius.circular(4),
          child: TweenAnimationBuilder<double>(
            tween: Tween(begin: 0, end: progress),
            duration: const Duration(milliseconds: 800),
            curve: Curves.easeOutCubic,
            builder: (context, value, _) {
              return Stack(
                children: [
                  Container(
                    height: 6,
                    decoration: BoxDecoration(
                      color: AppColors.divider,
                      borderRadius: BorderRadius.circular(4),
                    ),
                  ),
                  FractionallySizedBox(
                    widthFactor: value,
                    child: Container(
                      height: 6,
                      decoration: BoxDecoration(
                        gradient: isWinner
                            ? AppColors.accentGradient
                            : LinearGradient(
                                colors: [color, color.withValues(alpha: 0.5)],
                              ),
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                  ),
                ],
              );
            },
          ),
        ),
      ],
    );
  }
}
