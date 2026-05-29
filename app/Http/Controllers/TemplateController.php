<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeScore;
use App\Models\Criteria;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    /**
     * Memuat preset template K3LT secara instan.
     */
    public function load(Request $request)
    {
        $request->validate([
            'template' => 'required|in:ringkas,standar,lengkap',
        ]);

        $userId = Auth::id();
        $template = $request->template;

        try {
            DB::transaction(function () use ($userId, $template) {
                // 1. Bersihkan data kriteria & alternatif lama milik user agar bersih
                $criteriaIds = Criteria::where('user_id', $userId)->pluck('id');
                AlternativeScore::whereIn('criteria_id', $criteriaIds)->delete();
                Criteria::where('user_id', $userId)->delete();
                Alternative::where('user_id', $userId)->delete();

                // 2. Tentukan data preset berdasarkan tipe template
                if ($template === 'ringkas') {
                    $this->loadRingkasTemplate($userId);
                } elseif ($template === 'standar') {
                    $this->loadStandarTemplate($userId);
                } else {
                    $this->loadLengkapTemplate($userId);
                }

                // 3. Catat Audit Log
                AuditLog::create([
                    'user_id' => $userId,
                    'user_name' => Auth::user()->name,
                    'action' => 'load_template',
                    'description' => "Memuat Preset Template K3LT: " . ucfirst($template),
                ]);
            });

            if ($request->expectsJson() || $request->ajax()) {
                $criteria = Criteria::where('user_id', $userId)->orderBy('id')->get();
                $alternatives = Alternative::with('scores')->where('user_id', $userId)->orderBy('id')->get()->map(function($alt) {
                    return [
                        'id' => $alt->id,
                        'name' => $alt->name,
                        'scores' => $alt->scores->pluck('score', 'criteria_id')->toArray()
                    ];
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Preset Template K3LT "' . ucfirst($template) . '" berhasil dimuat!',
                    'criteria' => $criteria,
                    'alternatives' => $alternatives
                ]);
            }

            return redirect()->route('calculation.index')->with('success', 'Preset Template K3LT "' . ucfirst($template) . '" berhasil dimuat ke database!');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat template: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('dashboard')->with('error', 'Gagal memuat template: ' . $e->getMessage());
        }
    }

    /**
     * Template Ringkas: 4 Kriteria, 3 Kandidat
     */
    private function loadRingkasTemplate($userId)
    {
        $c1 = Criteria::create(['user_id' => $userId, 'name' => 'Pemahaman Regulasi K3', 'type' => 'Benefit', 'weight' => 0.30]);
        $c2 = Criteria::create(['user_id' => $userId, 'name' => 'Kepatuhan Alat Pelindung Diri (APD)', 'type' => 'Benefit', 'weight' => 0.30]);
        $c3 = Criteria::create(['user_id' => $userId, 'name' => 'Pengalaman Kerja Lapangan (Tahun)', 'type' => 'Benefit', 'weight' => 0.20]);
        $c4 = Criteria::create(['user_id' => $userId, 'name' => 'Biaya Pelatihan Khusus', 'type' => 'Cost', 'weight' => 0.20]);

        $candidates = [
            ['name' => 'Budi Santoso', 'scores' => [$c1->id => 85, $c2->id => 90, $c3->id => 3, $c4->id => 80]],
            ['name' => 'Siti Rahma', 'scores' => [$c1->id => 90, $c2->id => 80, $c3->id => 5, $c4->id => 60]],
            ['name' => 'Adi Wijaya', 'scores' => [$c1->id => 75, $c2->id => 85, $c3->id => 2, $c4->id => 50]],
        ];

        foreach ($candidates as $cand) {
            $alt = Alternative::create(['user_id' => $userId, 'name' => $cand['name']]);
            foreach ($cand['scores'] as $cId => $scoreVal) {
                AlternativeScore::create(['alternative_id' => $alt->id, 'criteria_id' => $cId, 'score' => $scoreVal]);
            }
        }
    }

    /**
     * Template Standar: 7 Kriteria, 5 Kandidat
     */
    private function loadStandarTemplate($userId)
    {
        $c1 = Criteria::create(['user_id' => $userId, 'name' => 'Sertifikasi Keahlian K3', 'type' => 'Benefit', 'weight' => 0.20]);
        $c2 = Criteria::create(['user_id' => $userId, 'name' => 'Pengalaman Kerja Lapangan (Tahun)', 'type' => 'Benefit', 'weight' => 0.15]);
        $c3 = Criteria::create(['user_id' => $userId, 'name' => 'Penilaian Kedisiplinan Kerja', 'type' => 'Benefit', 'weight' => 0.15]);
        $c4 = Criteria::create(['user_id' => $userId, 'name' => 'Respon Penanganan Insiden', 'type' => 'Benefit', 'weight' => 0.15]);
        $c5 = Criteria::create(['user_id' => $userId, 'name' => 'Kemampuan Kepemimpinan', 'type' => 'Benefit', 'weight' => 0.10]);
        $c6 = Criteria::create(['user_id' => $userId, 'name' => 'Kepatuhan Prosedur SOP', 'type' => 'Benefit', 'weight' => 0.15]);
        $c7 = Criteria::create(['user_id' => $userId, 'name' => 'Tingkat Gaji Yang Diharapkan', 'type' => 'Cost', 'weight' => 0.10]);

        $candidates = [
            ['name' => 'Ahmad Dani', 'scores' => [$c1->id => 80, $c2->id => 3, $c3->id => 85, $c4->id => 90, $c5->id => 78, $c6->id => 82, $c7->id => 60]],
            ['name' => 'Rina Amelia', 'scores' => [$c1->id => 90, $c2->id => 5, $c3->id => 80, $c4->id => 85, $c5->id => 85, $c6->id => 88, $c7->id => 80]],
            ['name' => 'Hendra Saputra', 'scores' => [$c1->id => 70, $c2->id => 2, $c3->id => 90, $c4->id => 75, $c5->id => 72, $c6->id => 80, $c7->id => 50]],
            ['name' => 'Dewi Lestari', 'scores' => [$c1->id => 85, $c2->id => 4, $c3->id => 88, $c4->id => 80, $c5->id => 90, $c6->id => 85, $c7->id => 75]],
            ['name' => 'Yusuf Pratama', 'scores' => [$c1->id => 75, $c2->id => 3, $c3->id => 82, $c4->id => 88, $c5->id => 80, $c6->id => 78, $c7->id => 55]],
        ];

        foreach ($candidates as $cand) {
            $alt = Alternative::create(['user_id' => $userId, 'name' => $cand['name']]);
            foreach ($cand['scores'] as $cId => $scoreVal) {
                AlternativeScore::create(['alternative_id' => $alt->id, 'criteria_id' => $cId, 'score' => $scoreVal]);
            }
        }
    }

    /**
     * Template Lengkap: 10 Kriteria, 6 Kandidat
     */
    private function loadLengkapTemplate($userId)
    {
        $c1  = Criteria::create(['user_id' => $userId, 'name' => 'Sertifikasi K3 Utama', 'type' => 'Benefit', 'weight' => 0.15]);
        $c2  = Criteria::create(['user_id' => $userId, 'name' => 'Pengalaman Audit K3LT', 'type' => 'Benefit', 'weight' => 0.12]);
        $c3  = Criteria::create(['user_id' => $userId, 'name' => 'Nilai Tes Psikologi K3', 'type' => 'Benefit', 'weight' => 0.10]);
        $c4  = Criteria::create(['user_id' => $userId, 'name' => 'Kemampuan Kepemimpinan', 'type' => 'Benefit', 'weight' => 0.10]);
        $c5  = Criteria::create(['user_id' => $userId, 'name' => 'Kepatuhan Prosedur SOP', 'type' => 'Benefit', 'weight' => 0.10]);
        $c6  = Criteria::create(['user_id' => $userId, 'name' => 'Respon Penanganan Darurat', 'type' => 'Benefit', 'weight' => 0.10]);
        $c7  = Criteria::create(['user_id' => $userId, 'name' => 'Kemampuan Komunikasi Tim', 'type' => 'Benefit', 'weight' => 0.08]);
        $c8  = Criteria::create(['user_id' => $userId, 'name' => 'Penguasaan Peralatan K3', 'type' => 'Benefit', 'weight' => 0.10]);
        $c9  = Criteria::create(['user_id' => $userId, 'name' => 'Tingkat Keterlambatan Absensi', 'type' => 'Cost', 'weight' => 0.08]);
        $c10 = Criteria::create(['user_id' => $userId, 'name' => 'Gaji Yang Diharapkan', 'type' => 'Cost', 'weight' => 0.07]);

        $candidates = [
            ['name' => 'Bambang Widjojo', 'scores' => [$c1->id => 85, $c2->id => 4, $c3->id => 80, $c4->id => 85, $c5->id => 90, $c6->id => 88, $c7->id => 82, $c8->id => 85, $c9->id => 2, $c10->id => 70]],
            ['name' => 'Mega Utami', 'scores' => [$c1->id => 90, $c2->id => 5, $c3->id => 85, $c4->id => 80, $c5->id => 85, $c6->id => 90, $c7->id => 88, $c8->id => 82, $c9->id => 1, $c10->id => 85]],
            ['name' => 'Fajar Sidik', 'scores' => [$c1->id => 75, $c2->id => 2, $c3->id => 78, $c4->id => 75, $c5->id => 80, $c6->id => 72, $c7->id => 70, $c8->id => 78, $c9->id => 4, $c10->id => 50]],
            ['name' => 'Eka Handayani', 'scores' => [$c1->id => 80, $c2->id => 3, $c3->id => 88, $c4->id => 90, $c5->id => 85, $c6->id => 85, $c7->id => 90, $c8->id => 80, $c9->id => 1, $c10->id => 65]],
            ['name' => 'Rian Hidayat', 'scores' => [$c1->id => 70, $c2->id => 1, $c3->id => 72, $c4->id => 70, $c5->id => 75, $c6->id => 68, $c7->id => 65, $c8->id => 72, $c9->id => 3, $c10->id => 45]],
            ['name' => 'Fitri Anggraeni', 'scores' => [$c1->id => 88, $c2->id => 4, $c3->id => 84, $c4->id => 82, $c5->id => 88, $c6->id => 86, $c7->id => 85, $c8->id => 88, $c9->id => 1, $c10->id => 75]],
        ];

        foreach ($candidates as $cand) {
            $alt = Alternative::create(['user_id' => $userId, 'name' => $cand['name']]);
            foreach ($cand['scores'] as $cId => $scoreVal) {
                AlternativeScore::create(['alternative_id' => $alt->id, 'criteria_id' => $cId, 'score' => $scoreVal]);
            }
        }
    }
}
