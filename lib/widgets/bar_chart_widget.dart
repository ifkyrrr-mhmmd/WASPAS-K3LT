import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../config/theme.dart';

/// Entry untuk data chart batang.
class BarChartEntry {
  final String name;
  final double value;

  const BarChartEntry({required this.name, required this.value});
}

/// Widget chart batang horizontal kustom tanpa library eksternal.
/// Menampilkan bar gradient dengan animasi pertumbuhan.
class BarChartWidget extends StatelessWidget {
  final List<BarChartEntry> entries;
  final String? title;
  final double barHeight;
  final Duration animationDuration;

  const BarChartWidget({
    super.key,
    required this.entries,
    this.title,
    this.barHeight = 28,
    this.animationDuration = const Duration(milliseconds: 800),
  });

  @override
  Widget build(BuildContext context) {
    if (entries.isEmpty) {
      return Center(
        child: Text(
          'Tidak ada data',
          style: TextStyle(color: AppColors.textSecondary, fontSize: 12),
        ),
      );
    }

    final maxValue =
        entries.map((e) => e.value).reduce((a, b) => a > b ? a : b);

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(AppRadius.lg),
        boxShadow: [
          BoxShadow(
            color: AppColors.cardShadow,
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (title != null) ...[
            Text(
              title!,
              style: GoogleFonts.inter(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                color: AppColors.textPrimary,
              ),
            ),
            const SizedBox(height: 16),
          ],
          ...List.generate(entries.length, (index) {
            final entry = entries[index];
            final fraction = maxValue > 0 ? entry.value / maxValue : 0.0;

            return _AnimatedBar(
              entry: entry,
              fraction: fraction,
              barHeight: barHeight,
              index: index,
              animationDuration: animationDuration,
            );
          }),
        ],
      ),
    );
  }
}

class _AnimatedBar extends StatelessWidget {
  final BarChartEntry entry;
  final double fraction;
  final double barHeight;
  final int index;
  final Duration animationDuration;

  const _AnimatedBar({
    required this.entry,
    required this.fraction,
    required this.barHeight,
    required this.index,
    required this.animationDuration,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Label & Value row
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  entry.name,
                  style: GoogleFonts.inter(
                    fontSize: 12,
                    fontWeight: FontWeight.w500,
                    color: AppColors.textPrimary,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              const SizedBox(width: 8),
              Text(
                entry.value.toStringAsFixed(4),
                style: GoogleFonts.inter(
                  fontSize: 12,
                  fontWeight: FontWeight.w700,
                  color: AppColors.teal,
                ),
              ),
            ],
          ),
          const SizedBox(height: 6),
          // Bar
          ClipRRect(
            borderRadius: BorderRadius.circular(barHeight / 2),
            child: Stack(
              children: [
                // Background
                Container(
                  height: barHeight,
                  width: double.infinity,
                  decoration: BoxDecoration(
                    color: AppColors.background,
                    borderRadius: BorderRadius.circular(barHeight / 2),
                  ),
                ),
                // Animated fill
                TweenAnimationBuilder<double>(
                  tween: Tween(begin: 0.0, end: fraction),
                  duration: Duration(
                    milliseconds:
                        animationDuration.inMilliseconds + (index * 150),
                  ),
                  curve: Curves.easeOutCubic,
                  builder: (context, value, _) {
                    return FractionallySizedBox(
                      widthFactor: value.clamp(0.0, 1.0),
                      child: Container(
                        height: barHeight,
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            colors: [
                              AppColors.teal,
                              AppColors.mint,
                            ],
                          ),
                          borderRadius:
                              BorderRadius.circular(barHeight / 2),
                          boxShadow: [
                            BoxShadow(
                              color: AppColors.teal.withValues(alpha: 0.3),
                              blurRadius: 4,
                              offset: const Offset(0, 2),
                            ),
                          ],
                        ),
                        // Rank indicator inside bar
                        child: value > 0.15
                            ? Align(
                                alignment: Alignment.centerRight,
                                child: Padding(
                                  padding:
                                      const EdgeInsets.only(right: 10),
                                  child: Text(
                                    '#${index + 1}',
                                    style: GoogleFonts.inter(
                                      fontSize: 10,
                                      fontWeight: FontWeight.w700,
                                      color: Colors.white,
                                    ),
                                  ),
                                ),
                              )
                            : null,
                      ),
                    );
                  },
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
