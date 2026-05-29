<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama dengan statistik eksekutif.
     */
    public function index(): View
    {
        $userId = Auth::id();

        // Total keputusan tersimpan
        $totalSeleksi = Calculation::where('user_id', $userId)->count();

        // Kandidat terbaik dari kalkulasi teranyar
        $latestHistory = Calculation::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        $alternatifTerbaik = '-';
        if ($latestHistory && isset($latestHistory->result_data['rankings'][0]['alternative_name'])) {
            $alternatifTerbaik = $latestHistory->result_data['rankings'][0]['alternative_name'];
        }

        // 5 riwayat terbaru
        $recentHistories = Calculation::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Safety Quote / Tips K3 Hari Ini via REST API + Fallback lokal
        $safetyTip = $this->fetchSafetyTip();

        return view('dashboard', compact(
            'totalSeleksi',
            'alternatifTerbaik',
            'recentHistories',
            'safetyTip'
        ));
    }

    /**
     * Mengambil kutipan keselamatan kerja dari API pihak ketiga dengan fallback lokal.
     */
    private function fetchSafetyTip(): array
    {
        try {
            $response = Http::timeout(2)->get('https://dummyjson.com/quotes/random');
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'quote'  => $data['quote'] ?? 'Utamakan Keselamatan dan Kesehatan Kerja (K3)!',
                    'author' => $data['author'] ?? 'K3LT Indonesia',
                ];
            }
        } catch (\Exception $e) {
            // Jaringan offline atau timeout, gunakan fallback
        }

        $localTips = [
            [
                'quote'  => 'Keselamatan bukanlah tentang keberuntungan, melainkan tentang kesadaran dan tindakan nyata.',
                'author' => 'Pakar K3',
            ],
            [
                'quote'  => 'Gunakan selalu Alat Pelindung Diri (APD) yang sesuai secara lengkap sebelum memulai pekerjaan Anda.',
                'author' => 'Standardisasi K3LT',
            ],
            [
                'quote'  => 'Kecelakaan kerja dapat dicegah dengan mematuhi Prosedur Operasional Standar (SOP) secara disiplin.',
                'author' => 'Pengawas K3LT',
            ],
            [
                'quote'  => 'Pikirkan keselamatan sebelum bekerja, karena ada keluarga tercinta yang menanti Anda di rumah.',
                'author' => 'K3LT Peduli',
            ],
            [
                'quote'  => 'Kebersihan dan kerapian tempat kerja adalah langkah pertama menuju area kerja yang aman dan produktif.',
                'author' => 'Prinsip 5R K3LT',
            ],
            [
                'quote'  => 'Jangan ragu untuk melaporkan kondisi tidak aman (unsafe condition) atau tindakan tidak aman (unsafe action) kepada pengawas.',
                'author' => 'Manajemen K3LT',
            ],
        ];

        shuffle($localTips);
        return $localTips[0];
    }
}
