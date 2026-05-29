<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Criteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CriteriaController extends Controller
{
    /**
     * Menampilkan daftar semua kriteria milik user yang sedang login.
     */
    public function index(): View
    {
        $criteria = Auth::user()->criteria()->orderBy('created_at')->get();

        return view('criteria.index', compact('criteria'));
    }

    /**
     * Menampilkan form untuk membuat kriteria baru.
     */
    public function create(): View
    {
        return view('criteria.create');
    }

    /**
     * Menyimpan kriteria baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:Benefit,Cost',
            'weight' => 'required|numeric|min:0|max:1',
        ]);

        $criteria = Auth::user()->criteria()->create($validated);

        // Mencatat log audit
        AuditLog::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user()->name,
            'action'      => 'create',
            'description' => "Menambahkan kriteria baru: {$criteria->name}",
            'details'     => $criteria->toArray(),
        ]);

        return redirect()->route('criteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit kriteria yang sudah ada.
     */
    public function edit(Criteria $criterium): View
    {
        // Pastikan kriteria milik user yang sedang login
        $this->authorizeCriteria($criterium);

        return view('criteria.edit', ['criteria' => $criterium]);
    }

    /**
     * Memperbarui data kriteria di database.
     */
    public function update(Request $request, Criteria $criterium): RedirectResponse
    {
        $this->authorizeCriteria($criterium);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:Benefit,Cost',
            'weight' => 'required|numeric|min:0|max:1',
        ]);

        $oldData = $criterium->toArray();
        $criterium->update($validated);

        // Mencatat log audit
        AuditLog::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user()->name,
            'action'      => 'update',
            'description' => "Memperbarui kriteria: {$criterium->name}",
            'details'     => [
                'sebelum' => $oldData,
                'sesudah' => $criterium->fresh()->toArray(),
            ],
        ]);

        return redirect()->route('criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Menghapus kriteria dari database.
     */
    public function destroy(Criteria $criterium): RedirectResponse
    {
        $this->authorizeCriteria($criterium);

        $criteriaName = $criterium->name;
        $criteriaData = $criterium->toArray();

        $criterium->delete();

        // Mencatat log audit
        AuditLog::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user()->name,
            'action'      => 'delete',
            'description' => "Menghapus kriteria: {$criteriaName}",
            'details'     => $criteriaData,
        ]);

        return redirect()->route('criteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }

    /**
     * Memastikan kriteria milik user yang sedang login.
     */
    private function authorizeCriteria(Criteria $criteria): void
    {
        abort_if($criteria->user_id !== Auth::id(), 403, 'Akses ditolak.');
    }
}
