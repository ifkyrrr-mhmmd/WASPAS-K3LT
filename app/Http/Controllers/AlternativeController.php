<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeScore;
use App\Models\AuditLog;
use App\Models\Criteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AlternativeController extends Controller
{
    /**
     * Menampilkan daftar semua alternatif milik user yang sedang login.
     */
    public function index(): View
    {
        $alternatives = Auth::user()
            ->alternatives()
            ->with('scores.criteria')
            ->orderBy('created_at')
            ->get();

        $criteria = Auth::user()->criteria()->orderBy('created_at')->get();

        return view('alternatives.index', compact('alternatives', 'criteria'));
    }

    /**
     * Menampilkan form untuk membuat alternatif baru.
     */
    public function create(): View
    {
        $criteria = Auth::user()->criteria()->orderBy('created_at')->get();

        return view('alternatives.create', compact('criteria'));
    }

    /**
     * Menyimpan alternatif baru beserta skornya ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $criteria = Auth::user()->criteria()->get();

        // Bangun aturan validasi dinamis berdasarkan kriteria yang ada
        $rules = [
            'name' => 'required|string|max:255',
        ];

        foreach ($criteria as $criterion) {
            $rules["scores.{$criterion->id}"] = 'required|numeric|gt:0';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $criteria, $request) {
            // Simpan alternatif
            $alternative = Auth::user()->alternatives()->create([
                'name' => $validated['name'],
            ]);

            // Simpan skor untuk setiap kriteria
            $scores = $request->input('scores', []);
            foreach ($criteria as $criterion) {
                if (isset($scores[$criterion->id])) {
                    AlternativeScore::create([
                        'alternative_id' => $alternative->id,
                        'criteria_id'    => $criterion->id,
                        'score'          => $scores[$criterion->id],
                    ]);
                }
            }

            // Mencatat log audit
            AuditLog::create([
                'user_id'     => Auth::id(),
                'user_name'   => Auth::user()->name,
                'action'      => 'create',
                'description' => "Menambahkan alternatif baru: {$alternative->name}",
                'details'     => [
                    'alternatif' => $alternative->toArray(),
                    'skor'       => $scores,
                ],
            ]);
        });

        return redirect()->route('alternatives.index')
            ->with('success', 'Alternatif berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit alternatif yang sudah ada.
     */
    public function edit(Alternative $alternative): View
    {
        $this->authorizeAlternative($alternative);

        $alternative->load('scores');
        $criteria = Auth::user()->criteria()->orderBy('created_at')->get();

        return view('alternatives.edit', compact('alternative', 'criteria'));
    }

    /**
     * Memperbarui data alternatif beserta skornya di database.
     */
    public function update(Request $request, Alternative $alternative): RedirectResponse
    {
        $this->authorizeAlternative($alternative);

        $criteria = Auth::user()->criteria()->get();

        // Bangun aturan validasi dinamis berdasarkan kriteria yang ada
        $rules = [
            'name' => 'required|string|max:255',
        ];

        foreach ($criteria as $criterion) {
            $rules["scores.{$criterion->id}"] = 'required|numeric|gt:0';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $criteria, $request, $alternative) {
            $oldData = $alternative->load('scores')->toArray();

            // Perbarui nama alternatif
            $alternative->update([
                'name' => $validated['name'],
            ]);

            // Perbarui atau buat skor untuk setiap kriteria
            $scores = $request->input('scores', []);
            foreach ($criteria as $criterion) {
                if (isset($scores[$criterion->id])) {
                    AlternativeScore::updateOrCreate(
                        [
                            'alternative_id' => $alternative->id,
                            'criteria_id'    => $criterion->id,
                        ],
                        [
                            'score' => $scores[$criterion->id],
                        ]
                    );
                }
            }

            // Mencatat log audit
            AuditLog::create([
                'user_id'     => Auth::id(),
                'user_name'   => Auth::user()->name,
                'action'      => 'update',
                'description' => "Memperbarui alternatif: {$alternative->name}",
                'details'     => [
                    'sebelum' => $oldData,
                    'sesudah' => $alternative->fresh()->load('scores')->toArray(),
                ],
            ]);
        });

        return redirect()->route('alternatives.index')
            ->with('success', 'Alternatif berhasil diperbarui.');
    }

    /**
     * Menghapus alternatif dari database (skor otomatis terhapus via cascade).
     */
    public function destroy(Alternative $alternative): RedirectResponse
    {
        $this->authorizeAlternative($alternative);

        $alternativeName = $alternative->name;
        $alternativeData = $alternative->load('scores')->toArray();

        $alternative->delete();

        // Mencatat log audit
        AuditLog::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user()->name,
            'action'      => 'delete',
            'description' => "Menghapus alternatif: {$alternativeName}",
            'details'     => $alternativeData,
        ]);

        return redirect()->route('alternatives.index')
            ->with('success', 'Alternatif berhasil dihapus.');
    }

    /**
     * Memastikan alternatif milik user yang sedang login.
     */
    private function authorizeAlternative(Alternative $alternative): void
    {
        abort_if($alternative->user_id !== Auth::id(), 403, 'Akses ditolak.');
    }
}
