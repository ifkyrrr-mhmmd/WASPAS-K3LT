import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../providers/waspas_provider.dart';
import '../providers/auth_provider.dart';
import '../config/theme.dart';
import 'history_detail_screen.dart';

class HistoryScreen extends StatefulWidget {
  const HistoryScreen({Key? key}) : super(key: key);

  @override
  State<HistoryScreen> createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
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
    final provider = context.watch<WaspasProvider>();
    final user = context.watch<AuthProvider>().currentUser;
    final history = provider.history;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Riwayat Seleksi'),
        backgroundColor: AppColors.deep,
      ),
      body: user == null
          ? const Center(child: Text('Harap login terlebih dahulu'))
          : provider.isLoading
              ? const Center(child: CircularProgressIndicator())
              : history.isEmpty
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.history, size: 80, color: Colors.grey.shade300),
                          const SizedBox(height: 16),
                          const Text(
                            'Belum ada riwayat seleksi.\nHitung WASPAS untuk menyimpannya.',
                            textAlign: TextAlign.center,
                            style: TextStyle(color: AppColors.textMuted),
                          ),
                        ],
                      ),
                    )
                  : RefreshIndicator(
                      onRefresh: () => provider.loadHistory(user.uid),
                      child: ListView.builder(
                        padding: const EdgeInsets.all(16),
                        itemCount: history.length,
                        itemBuilder: (context, index) {
                          final item = history[index];
                          final topAltName = item.rankings.isNotEmpty 
                              ? item.alternatives[item.rankings.first.alternativeIndex].name 
                              : 'N/A';
                          final topQi = item.rankings.isNotEmpty 
                              ? item.rankings.first.qi.toStringAsFixed(4) 
                              : '0.0000';

                          return Card(
                            margin: const EdgeInsets.only(bottom: 12),
                            elevation: 2,
                            child: ListTile(
                              contentPadding: const EdgeInsets.all(16),
                              leading: CircleAvatar(
                                radius: 24,
                                backgroundColor: AppColors.teal.withOpacity(0.1),
                                child: const Icon(Icons.assignment, color: AppColors.teal),
                              ),
                              title: Text(item.title, style: const TextStyle(fontWeight: FontWeight.bold)),
                              subtitle: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const SizedBox(height: 4),
                                  Text(DateFormat('dd MMM yyyy, HH:mm').format(item.createdAt)),
                                  const SizedBox(height: 8),
                                  Text('Terbaik: $topAltName (Qi: $topQi)', 
                                    style: const TextStyle(color: AppColors.deep, fontWeight: FontWeight.w500)),
                                ],
                              ),
                              trailing: const Icon(Icons.chevron_right),
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
                    ),
    );
  }
}
