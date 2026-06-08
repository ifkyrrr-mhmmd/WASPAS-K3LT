<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WaspasCalculatorService;
use App\Models\Calculation;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalculationController extends Controller
{
    protected $calculator;

    public function __construct(WaspasCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Tampilkan hasil perhitungan beserta tabel normalisasi dan perankingan.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $lambda = 0.5; // Lambda dikunci secara statis sesuai spesifikasi

        // Ambil data mentah saat ini milik user
        $criteria = \App\Models\Criteria::where('user_id', $userId)->orderBy('id')->get();
        $alternatives = \App\Models\Alternative::with('scores')->where('user_id', $userId)->orderBy('id')->get()->map(function($alt) {
            return [
                'id' => $alt->id,
                'name' => $alt->name,
                'scores' => $alt->scores->pluck('score', 'criteria_id')->toArray()
            ];
        });

        $customTemplates = \App\Models\CustomTemplate::where('user_id', $userId)->orderBy('updated_at', 'asc')->get();

        try {
            $result = null;
            if ($criteria->isNotEmpty() && $alternatives->isNotEmpty()) {
                $result = $this->calculator->calculate($userId, (float) $lambda);
            }
            return view('calculation.index', compact('result', 'criteria', 'alternatives', 'lambda', 'customTemplates'));
        } catch (\Exception $e) {
            return view('calculation.index', [
                'result' => null,
                'criteria' => $criteria,
                'alternatives' => $alternatives,
                'lambda' => $lambda,
                'customTemplates' => $customTemplates,
                'error' => $e->getMessage()
            ]);
        }
    }


    /**
     * Cetak hasil perhitungan WASPAS K3LT.
     */
    public function print(Request $request)
    {
        $userId = Auth::id();

        try {
            $result = $this->calculator->calculate($userId, 0.5);
            return view('calculation.print', compact('result'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Export hasil perhitungan saat ini ke PDF menggunakan DomPDF.
     */
    public function exportPdf(Request $request)
    {
        $userId = Auth::id();

        try {
            $result = $this->calculator->calculate($userId, 0.5);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('calculation.print', compact('result'))
                ->setPaper('a4', 'portrait');

            // Catat audit log
            AuditLog::create([
                'user_id'     => $userId,
                'user_name'   => Auth::user()->name,
                'action'      => 'export',
                'description' => 'Mengekspor PDF hasil perhitungan WASPAS aktif.',
            ]);

            return $pdf->stream('Laporan_WASPAS_K3LT_' . date('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('calculation.index')->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }


    /**
     * Simpan hasil kalkulasi saat ini ke history.
     */
    public function saveHistory(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'save_as_template' => 'nullable|boolean',
            'template_name' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();
        
        try {
            DB::transaction(function () use ($request, $userId) {
                // Kalkulasi ulang sesaat sebelum simpan untuk memastikan data valid
                $result = $this->calculator->calculate($userId, 0.5);
                
                // Konversi model ke array agar mudah di-serialize
                $result['criteria'] = $result['criteria']->toArray();
                $result['alternatives'] = $result['alternatives']->toArray();
                
                Calculation::create([
                    'user_id' => $userId,
                    'title' => $request->title,
                    'lambda' => 0.5,
                    'result_data' => $result,
                ]);

                // Simpan sebagai template kustom jika diminta
                if ($request->has('save_as_template') && $request->save_as_template) {
                    $templateName = $request->template_name ?: 'Template Kustom ' . date('Y-m-d H:i');
                    $criteriaData = array_map(function($c) {
                        return [
                            'name' => $c['name'],
                            'type' => $c['type'],
                            'weight' => $c['weight'],
                        ];
                    }, $result['criteria']);

                    $templateCount = \App\Models\CustomTemplate::where('user_id', $userId)->count();
                    
                    if ($templateCount >= 2) {
                        // Timpa template yang paling lama diperbarui
                        $oldestTemplate = \App\Models\CustomTemplate::where('user_id', $userId)
                                            ->orderBy('updated_at', 'asc')->first();
                        $oldestTemplate->update([
                            'name' => $templateName,
                            'criteria_data' => $criteriaData,
                        ]);
                    } else {
                        // Buat baru
                        \App\Models\CustomTemplate::create([
                            'user_id' => $userId,
                            'name' => $templateName,
                            'criteria_data' => $criteriaData,
                        ]);
                    }
                }

                AuditLog::create([
                    'user_id' => $userId,
                    'user_name' => Auth::user()->name,
                    'action' => 'calculate',
                    'description' => "Menyimpan hasil perhitungan: {$request->title}",
                ]);

                // Reset state kalkulator (hapus kriteria dan alternatif aktif milik user)
                $criteriaIds = \App\Models\Criteria::where('user_id', $userId)->pluck('id');
                \App\Models\AlternativeScore::whereIn('criteria_id', $criteriaIds)->delete();
                \App\Models\Criteria::where('user_id', $userId)->delete();
                \App\Models\Alternative::where('user_id', $userId)->delete();
            });

            return redirect()->route('history.index')->with('success', 'Hasil perhitungan berhasil disimpan ke riwayat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan riwayat: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan seluruh kriteria & matriks secara batch dari Stepper Step 2 via AJAX.
     */
    public function storeAll(Request $request)
    {
        $request->validate([
            'criteria' => 'required|array|min:1',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.type' => 'required|in:Benefit,Cost',
            'criteria.*.weight' => 'required|numeric|min:0|max:1',
            'alternatives' => 'required|array|min:1',
            'alternatives.*.name' => 'required|string|max:255',
            'alternatives.*.scores' => 'required|array',
            'alternatives.*.scores.*' => 'required|numeric|gt:0',
        ]);

        $userId = Auth::id();

        try {
            $result = DB::transaction(function () use ($request, $userId) {
                // Hapus data lama milik user agar bersih
                $criteriaIds = \App\Models\Criteria::where('user_id', $userId)->pluck('id');
                \App\Models\AlternativeScore::whereIn('criteria_id', $criteriaIds)->delete();
                \App\Models\Criteria::where('user_id', $userId)->delete();
                \App\Models\Alternative::where('user_id', $userId)->delete();

                // Simpan Kriteria Baru
                $createdCriteria = [];
                foreach ($request->input('criteria') as $cData) {
                    $c = \App\Models\Criteria::create([
                        'user_id' => $userId,
                        'name' => $cData['name'],
                        'type' => $cData['type'],
                        'weight' => $cData['weight']
                    ]);
                    // Simpan mapping dari ID sementara (frontend) ke model kriteria asli
                    if (isset($cData['id'])) {
                        $createdCriteria[$cData['id']] = $c;
                    }
                }

                // Simpan Alternatif & Skor Baru
                foreach ($request->input('alternatives') as $altData) {
                    $alt = \App\Models\Alternative::create([
                        'user_id' => $userId,
                        'name' => $altData['name']
                    ]);

                    if (isset($altData['scores']) && is_array($altData['scores'])) {
                        foreach ($altData['scores'] as $tempCriteriaId => $scoreVal) {
                            if (isset($createdCriteria[$tempCriteriaId])) {
                                \App\Models\AlternativeScore::create([
                                    'alternative_id' => $alt->id,
                                    'criteria_id' => $createdCriteria[$tempCriteriaId]->id,
                                    'score' => (float) $scoreVal
                                ]);
                            }
                        }
                    }
                }

                // Catat audit log
                AuditLog::create([
                    'user_id' => $userId,
                    'user_name' => Auth::user()->name,
                    'action' => 'save_calculation_state',
                    'description' => 'Menyimpan kriteria dan matriks keputusan dari kalkulator terbimbing.'
                ]);

                // Hitung default WASPAS (lambda = 0.5)
                return $this->calculator->calculate($userId, 0.5);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data kriteria & matriks berhasil disimpan!',
                'result' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}

