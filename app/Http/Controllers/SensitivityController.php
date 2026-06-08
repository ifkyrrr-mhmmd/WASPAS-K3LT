<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\Criteria;
use App\Models\Alternative;
use App\Models\AuditLog;
use App\Services\WaspasCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensitivityController extends Controller
{
    protected WaspasCalculatorService $calculator;

    public function __construct(WaspasCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Tampilkan halaman Analisis Sensitivitas.
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil daftar riwayat perhitungan milik user untuk dropdown sumber data
        $histories = Calculation::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'created_at']);

        // Ambil kriteria aktif saat ini
        $currentCriteria = Criteria::where('user_id', $userId)->orderBy('id')->get();

        // Cek apakah user punya data aktif saat ini (kriteria + alternatif)
        $hasCurrentData = $currentCriteria->isNotEmpty() &&
            Alternative::where('user_id', $userId)->exists();

        return view('sensitivity.index', compact('histories', 'currentCriteria', 'hasCurrentData'));
    }

    /**
     * Eksekusi analisis sensitivitas bobot kriteria.
     * Memvariasikan bobot kriteria terpilih dari 0% hingga 95% dalam 10 langkah.
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'source'      => 'required|in:current,history',
            'history_id'  => 'required_if:source,history|nullable|integer',
            'criteria_id' => 'required|integer',
        ]);

        $userId = Auth::id();

        // 1. Ambil data sumber (kriteria dan alternatif)
        if ($request->source === 'history') {
            $history = Calculation::where('user_id', $userId)
                ->findOrFail($request->history_id);
            $resultData = $history->result_data;

            // Rekonstruksi kriteria dan alternatif dari snapshot
            $criteria = $resultData['criteria'] ?? [];
            $alternatives = $resultData['alternatives'] ?? [];

            // Konversi ke format yang dibutuhkan calculateWithCustomWeights
            $criteriaArray = collect($criteria)->map(function ($c) {
                return [
                    'id'     => $c['id'],
                    'name'   => $c['name'],
                    'type'   => $c['type'],
                    'weight' => (float) $c['weight'],
                ];
            })->toArray();

            // Rebuild alternatives with scores
            $alternativesArray = [];
            foreach ($alternatives as $alt) {
                $scores = [];
                if (isset($alt['scores']) && !empty($alt['scores'])) {
                    // Check if scores is a list of relationship arrays
                    if (isset($alt['scores'][0]) && is_array($alt['scores'][0])) {
                        foreach ($alt['scores'] as $s) {
                            $cId = $s['criteria_id'] ?? null;
                            if ($cId !== null) {
                                $scores[$cId] = (float)($s['score'] ?? 0);
                            }
                        }
                    } else {
                        // Already associative [criteria_id => score]
                        foreach ($alt['scores'] as $cId => $val) {
                            $scores[$cId] = (float)$val;
                        }
                    }
                }
                
                // Fallback: If empty, try to construct with 1 to avoid Zero-Score bug
                if (empty($scores)) {
                    foreach ($criteria as $c) {
                        $scores[$c['id']] = 1;
                    }
                }

                $alternativesArray[] = [
                    'id'     => $alt['id'],
                    'name'   => $alt['name'],
                    'scores' => $scores,
                ];
            }

            $sourceName = $history->title;
        } else {
            // Data aktif saat ini dari database
            $dbCriteria = Criteria::where('user_id', $userId)->orderBy('id')->get();
            $dbAlternatives = Alternative::with('scores')->where('user_id', $userId)->orderBy('id')->get();

            if ($dbCriteria->isEmpty() || $dbAlternatives->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kriteria atau alternatif aktif belum lengkap.',
                ], 422);
            }

            $criteriaArray = $dbCriteria->map(function ($c) {
                return [
                    'id'     => $c->id,
                    'name'   => $c->name,
                    'type'   => $c->type,
                    'weight' => (float) $c->weight,
                ];
            })->toArray();

            $alternativesArray = $dbAlternatives->map(function ($alt) use ($dbCriteria) {
                $scores = [];
                foreach ($dbCriteria as $c) {
                    $scores[$c->id] = $alt->scoreFor($c->id) ?? 1;
                }
                return [
                    'id'     => $alt->id,
                    'name'   => $alt->name,
                    'scores' => $scores,
                ];
            })->toArray();

            $sourceName = 'Data Aktif Saat Ini';
        }

        // 2. Validasi kriteria terpilih ada dalam dataset
        $selectedCriteriaId = (int) $request->criteria_id;
        $selectedCriteria = collect($criteriaArray)->firstWhere('id', $selectedCriteriaId);

        if (!$selectedCriteria) {
            return response()->json([
                'success' => false,
                'message' => 'Kriteria yang dipilih tidak ditemukan dalam dataset.',
            ], 422);
        }

        // 3. Simulasi 10 langkah: variasikan bobot 0% hingga 95%
        $steps = [0, 0.105, 0.21, 0.315, 0.42, 0.525, 0.63, 0.735, 0.84, 0.95];
        $simulationResults = [];
        $originalWinner = null;
        $shiftDetected = false;
        $shiftInfo = null;

        // Hitung total bobot kriteria lain (selain yang dipilih) untuk redistribusi proporsional
        $otherCriteriaTotalWeight = 0;
        foreach ($criteriaArray as $c) {
            if ($c['id'] !== $selectedCriteriaId) {
                $otherCriteriaTotalWeight += $c['weight'];
            }
        }

        foreach ($steps as $stepIndex => $targetWeight) {
            // Redistribusi bobot proporsional
            $modifiedCriteria = [];
            foreach ($criteriaArray as $c) {
                $modified = $c;
                if ($c['id'] === $selectedCriteriaId) {
                    $modified['weight'] = $targetWeight;
                } else {
                    // Redistribusi proporsional agar total tetap 1.0
                    $remainingWeight = 1.0 - $targetWeight;
                    if ($otherCriteriaTotalWeight > 0) {
                        $proportion = $c['weight'] / $otherCriteriaTotalWeight;
                        $modified['weight'] = $remainingWeight * $proportion;
                    } else {
                        $modified['weight'] = 0;
                    }
                }
                $modifiedCriteria[] = $modified;
            }

            // Jalankan WASPAS dengan bobot yang dimodifikasi
            try {
                $result = $this->calculator->calculateWithCustomWeights($modifiedCriteria, $alternativesArray, 0.5);
                $rankings = $result['rankings'];
                $winner = $rankings[0] ?? null;

                if ($stepIndex === 0 && $winner) {
                    // Anggap step pertama (bobot 0%) sebagai baseline
                }

                // Pada kalkulasi pertama atau original (step yang paling mendekati bobot asli), simpan pemenang original
                if ($originalWinner === null && $winner) {
                    $originalWinner = $winner;
                }

                $simulationResults[] = [
                    'step'             => $stepIndex + 1,
                    'weight_percent'   => round($targetWeight * 100, 1),
                    'winner_name'      => $winner ? $winner['alternative_name'] : '-',
                    'winner_qi'        => $winner ? round($winner['qi'], 6) : 0,
                    'rankings'         => array_map(function ($r) {
                        return [
                            'rank'             => $r['rank'],
                            'alternative_name' => $r['alternative_name'],
                            'qi'               => round($r['qi'], 6),
                        ];
                    }, $rankings),
                    'modified_weights' => array_map(function ($c) {
                        return [
                            'id'     => $c['id'],
                            'name'   => $c['name'],
                            'weight' => round($c['weight'], 4),
                        ];
                    }, $modifiedCriteria),
                ];
            } catch (\Exception $e) {
                $simulationResults[] = [
                    'step'           => $stepIndex + 1,
                    'weight_percent' => round($targetWeight * 100, 1),
                    'error'          => $e->getMessage(),
                ];
            }
        }

        // 4. Deteksi pergeseran pemenang
        // Ambil pemenang dari kalkulasi dengan bobot original (bukan step 0%)
        // Cari step yang paling dekat dengan bobot asli
        $originalWeight = $selectedCriteria['weight'];
        $baselineResult = null;
        $baselineWinner = null;

        // Hitung ulang dengan bobot original sebagai baseline
        try {
            $baselineCalc = $this->calculator->calculateWithCustomWeights($criteriaArray, $alternativesArray, 0.5);
            $baselineWinner = $baselineCalc['rankings'][0] ?? null;
        } catch (\Exception $e) {
            // Fallback ke pemenang pertama dari simulasi
            $baselineWinner = $originalWinner;
        }

        foreach ($simulationResults as &$simResult) {
            if (isset($simResult['error'])) continue;

            if ($baselineWinner && $simResult['winner_name'] !== $baselineWinner['alternative_name']) {
                $simResult['winner_shifted'] = true;
                if (!$shiftDetected) {
                    $shiftDetected = true;
                    $shiftInfo = [
                        'threshold_percent' => $simResult['weight_percent'],
                        'new_winner'        => $simResult['winner_name'],
                        'original_winner'   => $baselineWinner['alternative_name'],
                    ];
                }
            } else {
                $simResult['winner_shifted'] = false;
            }
        }
        unset($simResult);

        // 5. Catat audit log
        AuditLog::create([
            'user_id'     => $userId,
            'user_name'   => Auth::user()->name,
            'action'      => 'sensitivity_analysis',
            'description' => "Menjalankan analisis sensitivitas pada kriteria: {$selectedCriteria['name']} (Sumber: {$sourceName})",
            'details'     => [
                'source'              => $request->source,
                'criteria_name'       => $selectedCriteria['name'],
                'shift_detected'      => $shiftDetected,
                'shift_info'          => $shiftInfo,
            ],
        ]);

        return response()->json([
            'success'           => true,
            'source_name'       => $sourceName,
            'criteria_name'     => $selectedCriteria['name'],
            'original_weight'   => round($originalWeight * 100, 1),
            'baseline_winner'   => $baselineWinner ? $baselineWinner['alternative_name'] : '-',
            'is_stable'         => !$shiftDetected,
            'shift_info'        => $shiftInfo,
            'simulation_results' => $simulationResults,
        ]);
    }
}
