import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../providers/auth_provider.dart';
import '../config/theme.dart';
import '../models/user_model.dart';
import '../widgets/user_avatar.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({Key? key}) : super(key: key);

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final _nameController = TextEditingController();
  final _photoUrlController = TextEditingController();
  String? _selectedPreset;

  @override
  void dispose() {
    _nameController.dispose();
    _photoUrlController.dispose();
    super.dispose();
  }

  // ---------------------------------------------------------------------------
  // Avatar Widget Builder
  // ---------------------------------------------------------------------------
  Widget _buildAvatar(UserModel user, {double radius = 50, double iconSize = 40}) {
    return UserAvatar(user: user, radius: radius, iconSize: iconSize);
  }

  // ---------------------------------------------------------------------------
  // Edit Profile Dialog
  // ---------------------------------------------------------------------------
  void _showEditProfileDialog(BuildContext context, UserModel user) {
    _nameController.text = user.displayName;
    _photoUrlController.text = (user.photoUrl != null && !user.photoUrl!.startsWith('preset:'))
        ? user.photoUrl!
        : '';
    
    _selectedPreset = (user.photoUrl != null && user.photoUrl!.startsWith('preset:'))
        ? user.photoUrl
        : null;

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            final auth = context.watch<AuthProvider>();

            final presets = [
              {'id': 'preset:engineering', 'name': 'K3 Engineering', 'icon': Icons.engineering_rounded, 'color': Colors.orange.shade700},
              {'id': 'preset:construction', 'name': 'K3 Konstruksi', 'icon': Icons.construction_rounded, 'color': Colors.green.shade700},
              {'id': 'preset:security', 'name': 'K3 Security', 'icon': Icons.security_rounded, 'color': Colors.blue.shade700},
              {'id': 'preset:leader', 'name': 'K3 Leader', 'icon': Icons.admin_panel_settings_rounded, 'color': AppColors.teal},
              {'id': 'preset:hazard', 'name': 'Safety Hazard', 'icon': Icons.warning_amber_rounded, 'color': Colors.amber.shade800},
              {'id': 'preset:transport', 'name': 'Logistik K3LT', 'icon': Icons.local_shipping_rounded, 'color': Colors.purple.shade700},
            ];

            return AlertDialog(
              title: const Text('Edit Profil & Foto'),
              content: SizedBox(
                width: 420,
                child: SingleChildScrollView(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      // Name Input
                      TextField(
                        controller: _nameController,
                        decoration: const InputDecoration(
                          labelText: 'Nama Lengkap',
                          prefixIcon: Icon(Icons.person_outline),
                        ),
                      ),
                      const SizedBox(height: 20),

                      // Preset Title
                      const Text(
                        'Pilih Avatar Tema K3LT Keren:',
                        style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppColors.deep),
                      ),
                      const SizedBox(height: 8),

                      // Presets Grid
                      Wrap(
                        spacing: 8,
                        runSpacing: 8,
                        children: presets.map((p) {
                          final isSelected = _selectedPreset == p['id'];
                          return GestureDetector(
                            onTap: () {
                              setDialogState(() {
                                _selectedPreset = p['id'] as String;
                                _photoUrlController.clear(); // Clear custom url when choosing preset
                              });
                            },
                            child: AnimatedContainer(
                              duration: const Duration(milliseconds: 200),
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
                              decoration: BoxDecoration(
                                color: isSelected 
                                    ? (p['color'] as Color).withValues(alpha: 0.15) 
                                    : Colors.grey.shade50,
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(
                                  color: isSelected ? (p['color'] as Color) : Colors.grey.shade300,
                                  width: isSelected ? 2 : 1,
                                ),
                              ),
                              child: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(p['icon'] as IconData, color: p['color'] as Color, size: 18),
                                  const SizedBox(width: 6),
                                  Text(
                                    p['name'] as String,
                                    style: TextStyle(
                                      fontSize: 11,
                                      fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                                      color: isSelected ? AppColors.deep : AppColors.textSecondary,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          );
                        }).toList(),
                      ),
                      const SizedBox(height: 20),

                      // Divider
                      const Row(
                        children: [
                          Expanded(child: Divider()),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 8.0),
                            child: Text('atau gunakan custom URL', style: TextStyle(fontSize: 10, color: Colors.grey)),
                          ),
                          Expanded(child: Divider()),
                        ],
                      ),
                      const SizedBox(height: 12),

                      // Custom URL Input
                      TextField(
                        controller: _photoUrlController,
                        decoration: const InputDecoration(
                          labelText: 'Custom Foto URL (Opsional)',
                          prefixIcon: Icon(Icons.link_rounded),
                          hintText: 'https://images.unsplash.com/...',
                        ),
                        onChanged: (val) {
                          if (val.trim().isNotEmpty) {
                            setDialogState(() {
                              _selectedPreset = null; // Clear preset when typing custom URL
                            });
                          }
                        },
                      ),
                    ],
                  ),
                ),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Batal'),
                ),
                ElevatedButton(
                  onPressed: auth.isLoading
                      ? null
                      : () async {
                          final newName = _nameController.text.trim();
                          final newPhoto = _selectedPreset ?? _photoUrlController.text.trim();

                          if (newName.isEmpty) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('Nama tidak boleh kosong!'), backgroundColor: Colors.orange),
                            );
                            return;
                          }

                          final success = await auth.updateProfile(
                            displayName: newName,
                            photoUrl: newPhoto.isEmpty ? '' : newPhoto,
                          );

                          if (success && context.mounted) {
                            Navigator.pop(context);
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(content: Text('Profil berhasil diperbarui!'), backgroundColor: AppColors.mintDark),
                            );
                          } else if (context.mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(content: Text(auth.errorMessage ?? 'Gagal memperbarui profil'), backgroundColor: Colors.red),
                            );
                          }
                        },
                  child: auth.isLoading
                      ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                      : const Text('Simpan'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final user = authProvider.currentUser;

    if (user == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Profil')),
        body: const Center(child: Text('Harap login terlebih dahulu')),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Profil Pengguna'),
        backgroundColor: AppColors.deep,
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // Profile Card
          Center(
            child: Column(
              children: [
                Stack(
                  alignment: Alignment.bottomRight,
                  children: [
                    _buildAvatar(user, radius: 52, iconSize: 42),
                    GestureDetector(
                      onTap: () => _showEditProfileDialog(context, user),
                      child: Container(
                        padding: const EdgeInsets.all(6),
                        decoration: const BoxDecoration(
                          shape: BoxShape.circle,
                          color: AppColors.teal,
                          boxShadow: [
                            BoxShadow(color: Colors.black26, blurRadius: 4, offset: Offset(0, 2)),
                          ],
                        ),
                        child: const Icon(Icons.edit_rounded, size: 16, color: Colors.white),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                Text(
                  user.displayName,
                  style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: AppColors.deep),
                ),
                Text(
                  user.email,
                  style: const TextStyle(fontSize: 16, color: AppColors.textSecondary),
                ),
                const SizedBox(height: 8),
                Chip(
                  label: Text(user.role.toUpperCase()),
                  backgroundColor: AppColors.mint.withValues(alpha: 0.2),
                  labelStyle: const TextStyle(color: AppColors.mintDark, fontWeight: FontWeight.bold),
                ),
              ],
            ),
          ),
          const SizedBox(height: 32),
          
          const Divider(),
          const SizedBox(height: 16),
          const Text('Informasi Akun', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.deep)),
          const SizedBox(height: 16),
          ListTile(
            leading: const Icon(Icons.calendar_today, color: AppColors.teal),
            title: const Text('Bergabung Sejak'),
            subtitle: Text(DateFormat('dd MMMM yyyy', 'id_ID').format(user.createdAt)),
          ),
          ListTile(
            leading: const Icon(Icons.access_time, color: AppColors.teal),
            title: const Text('Login Terakhir'),
            subtitle: Text(user.lastLoginAt != null ? DateFormat('dd MMMM yyyy, HH:mm', 'id_ID').format(user.lastLoginAt!) : '-'),
          ),
          const SizedBox(height: 24),
          
          const Divider(),
          const SizedBox(height: 16),
          const Text('Tentang Aplikasi', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.deep)),
          const SizedBox(height: 16),
          const ListTile(
            leading: Icon(Icons.info_outline, color: AppColors.teal),
            title: Text('SPK WASPAS K3LT'),
            subtitle: Text('Versi 1.0.0'),
          ),
          const ListTile(
            leading: Icon(Icons.group, color: AppColors.teal),
            title: Text('Pengembang'),
            subtitle: Text('Kelompok 2 — Teknik Informatika'),
          ),
          const SizedBox(height: 32),
          
          ElevatedButton.icon(
            onPressed: () async {
              await authProvider.logout();
              if (context.mounted) {
                Navigator.pushReplacementNamed(context, '/login');
              }
            },
            icon: const Icon(Icons.logout),
            label: const Text('Keluar dari Akun'),
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red.shade700,
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(vertical: 16),
            ),
          ),
        ],
      ),
    );
  }
}
