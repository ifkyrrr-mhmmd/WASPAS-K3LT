import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../providers/waspas_provider.dart';
import 'history_detail_screen.dart';
import '../config/theme.dart';
import '../widgets/metric_card.dart';
import '../widgets/loading_overlay.dart';
import '../widgets/user_avatar.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({Key? key}) : super(key: key);

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final user = context.read<AuthProvider>().currentUser;
      if (user != null) {
        context.read<WaspasProvider>().loadHistory(user.uid);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final waspasProvider = context.watch<WaspasProvider>();
    final user = authProvider.currentUser;

    if (user == null) {
      return const LoadingOverlay(message: 'Memuat data...');
    }

    final history = waspasProvider.history;
    final totalSeleksi = history.length;
    final alternatifTerbaik = history.isNotEmpty && history.first.rankings.isNotEmpty
        ? history.first.alternatives[history.first.rankings.first.alternativeIndex].name
        : '-';

    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard'),
        elevation: 0,
        backgroundColor: AppColors.deep,
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            UserAccountsDrawerHeader(
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [AppColors.deep, AppColors.teal],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              accountName: Text(
                user.displayName.isNotEmpty ? user.displayName : 'Pengguna',
                style: const TextStyle(fontWeight: FontWeight.bold),
              ),
              accountEmail: Text(user.email),
              currentAccountPicture: UserAvatar(
                user: user,
                radius: 36,
                iconSize: 24,
              ),
            ),
            ListTile(
              leading: const Icon(Icons.dashboard, color: AppColors.teal),
              title: const Text('Dashboard'),
              onTap: () => Navigator.pop(context),
            ),
            ListTile(
              leading: const Icon(Icons.calculate, color: AppColors.teal),
              title: const Text('Kalkulator WASPAS'),
              onTap: () {
                Navigator.pop(context);
                context.read<WaspasProvider>().reset();
                Navigator.pushNamed(context, '/calculator');
              },
            ),
            ListTile(
              leading: const Icon(Icons.history, color: AppColors.teal),
              title: const Text('Riwayat Seleksi'),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, '/history');
              },
            ),
            ListTile(
              leading: const Icon(Icons.analytics, color: AppColors.teal),
              title: const Text('Analisis Sensitivitas'),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, '/sensitivity');
              },
            ),
            const Divider(),
            ListTile(
              leading: const Icon(Icons.person, color: AppColors.teal),
              title: const Text('Profil'),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, '/profile');
              },
            ),
            ListTile(
              leading: const Icon(Icons.logout, color: Colors.red),
              title: const Text('Keluar', style: TextStyle(color: Colors.red)),
              onTap: () async {
                await authProvider.logout();
                if (mounted) Navigator.pushReplacementNamed(context, '/login');
              },
            ),
          ],
        ),
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await waspasProvider.loadHistory(user.uid);
        },
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Premium Dynamic Header
              Container(
                padding: const EdgeInsets.fromLTRB(24, 28, 24, 44),
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [AppColors.deep, AppColors.teal],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.only(
                    bottomLeft: Radius.circular(32),
                    bottomRight: Radius.circular(32),
                  ),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Halo, ${user.displayName.isNotEmpty ? user.displayName : 'Pengguna'}!',
                                style: const TextStyle(
                                  fontSize: 26,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.white,
                                  letterSpacing: 0.5,
                                ),
                              ),
                              const SizedBox(height: 6),
                              const Text(
                                'Selamat datang di SPK WASPAS K3LT',
                                style: TextStyle(
                                  color: Colors.white70,
                                  fontSize: 14,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            ],
                          ),
                        ),
                        UserAvatar(
                          user: user,
                          radius: 24,
                          iconSize: 20,
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              
              // Body Content
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 20.0, vertical: 16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Metrics Row (Dynamic overlapping using Transform)
                    Transform.translate(
                      offset: const Offset(0, -32),
                      child: IntrinsicHeight(
                        child: Row(
                          crossAxisAlignment: CrossAxisAlignment.stretch,
                          children: [
                            Expanded(
                              child: MetricCard(
                                label: 'Total Seleksi',
                                value: totalSeleksi.toString(),
                                icon: Icons.assessment_rounded,
                                color: AppColors.teal,
                              ),
                            ),
                            const SizedBox(width: 16),
                            Expanded(
                              child: MetricCard(
                                label: 'Terbaik',
                                value: alternatifTerbaik,
                                icon: Icons.emoji_events_rounded,
                                color: AppColors.mint,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    
                    const SizedBox(height: 4),

                    // Aksi Cepat (Quick Actions)
                    const Text(
                      'Aksi Cepat',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: AppColors.deep,
                        letterSpacing: 0.3,
                      ),
                    ),
                    const SizedBox(height: 12),
                    Row(
                      children: [
                        Expanded(
                          child: _buildActionCard(
                            context,
                            'Mulai Seleksi',
                            Icons.add_moderator_rounded,
                            AppColors.teal,
                            () {
                              context.read<WaspasProvider>().reset();
                              Navigator.pushNamed(context, '/calculator');
                            },
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _buildActionCard(
                            context,
                            'Riwayat',
                            Icons.history_rounded,
                            AppColors.mint,
                            () => Navigator.pushNamed(context, '/history'),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _buildActionCard(
                            context,
                            'Sensitivitas',
                            Icons.analytics_rounded,
                            Colors.orange.shade400,
                            () => Navigator.pushNamed(context, '/sensitivity'),
                          ),
                        ),
                      ],
                    ),
                    
                    const SizedBox(height: 28),

                    // Recent History (Riwayat Terakhir)
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text(
                          'Riwayat Terakhir',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: AppColors.deep,
                            letterSpacing: 0.3,
                          ),
                        ),
                        TextButton.icon(
                          onPressed: () => Navigator.pushNamed(context, '/history'),
                          icon: const Icon(Icons.arrow_forward_rounded, size: 16),
                          label: const Text('Lihat Semua'),
                          style: TextButton.styleFrom(
                            foregroundColor: AppColors.teal,
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    
                    if (waspasProvider.isLoading)
                      const Padding(
                        padding: EdgeInsets.symmetric(vertical: 32.0),
                        child: Center(child: CircularProgressIndicator()),
                      )
                    else if (history.isEmpty)
                      Container(
                        padding: const EdgeInsets.symmetric(vertical: 40, horizontal: 20),
                        width: double.infinity,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: AppColors.borderLight),
                          boxShadow: const [
                            BoxShadow(
                              color: AppColors.cardShadow,
                              blurRadius: 10,
                              offset: Offset(0, 4),
                            ),
                          ],
                        ),
                        child: Column(
                          children: [
                            Icon(Icons.folder_open_rounded, size: 48, color: AppColors.textDisabled),
                            const SizedBox(height: 12),
                            const Text(
                              'Belum ada riwayat perhitungan.\nMulai seleksi baru sekarang.',
                              textAlign: TextAlign.center,
                              style: TextStyle(color: AppColors.textMuted, height: 1.4),
                            ),
                          ],
                        ),
                      )
                    else
                      ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: history.length > 5 ? 5 : history.length,
                        itemBuilder: (context, index) {
                          final item = history[index];
                          final topRank = item.rankings.isNotEmpty ? item.rankings.first : null;
                          final topAltName = topRank != null ? item.alternatives[topRank.alternativeIndex].name : 'N/A';
                          
                          return Card(
                            margin: const EdgeInsets.only(bottom: 12),
                            elevation: 2,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: ListTile(
                              leading: CircleAvatar(
                                backgroundColor: AppColors.mint.withValues(alpha: 0.15),
                                child: const Icon(Icons.stars_rounded, color: AppColors.mintDark),
                              ),
                              title: Text(
                                item.title,
                                style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.deep),
                              ),
                              subtitle: Text('Terbaik: $topAltName', style: const TextStyle(color: AppColors.textSecondary)),
                              trailing: const Icon(Icons.chevron_right_rounded, color: AppColors.teal),
                              onTap: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => HistoryDetailScreen(item: item),
                                  ),
                                );
                              },
                            ),
                          );
                        },
                      ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildActionCard(BuildContext context, String title, IconData icon, Color color, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 8),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: color.withValues(alpha: 0.08),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
          border: Border.all(color: color.withValues(alpha: 0.15)),
        ),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: color.withValues(alpha: 0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(icon, color: color, size: 28),
            ),
            const SizedBox(height: 12),
            Text(
              title,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.bold,
                color: AppColors.deep,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
