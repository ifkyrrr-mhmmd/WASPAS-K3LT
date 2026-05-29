<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    /**
     * Tampilkan daftar riwayat perhitungan.
     */
    public function index()
    {
        $histories = Calculation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('history.index', compact('histories'));
    }

    /**
     * Tampilkan detail riwayat perhitungan spesifik.
     */
    public function show(Calculation $history)
    {
        // Pastikan history milik user yang sedang login
        if ($history->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Data hasil perhitungan sudah otomatis di-cast sebagai array di model
        $result = $history->result_data;

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'result'  => $result,
            ]);
        }

        return view('history.show', compact('history', 'result'));
    }

    /**
     * Cetak riwayat hasil perhitungan WASPAS K3LT.
     */
    public function print(Calculation $history)
    {
        // Pastikan history milik user yang sedang login
        if ($history->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $result = $history->result_data;

        return view('history.print', compact('history', 'result'));
    }

    /**
     * Export riwayat hasil perhitungan ke PDF menggunakan DomPDF.
     */
    public function exportPdf(Calculation $history)
    {
        // Pastikan history milik user yang sedang login
        if ($history->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $result = $history->result_data;

        $pdf = Pdf::loadView('history.print', compact('history', 'result'))
            ->setPaper('a4', 'portrait');

        // Catat audit log
        AuditLog::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user()->name,
            'action'      => 'export',
            'description' => "Mengekspor PDF riwayat: {$history->title}",
        ]);

        return $pdf->stream('Laporan_WASPAS_' . str_replace(' ', '_', $history->title) . '.pdf');
    }

    /**
     * Hapus riwayat perhitungan spesifik.
     */
    public function destroy(Calculation $history)
    {
        // Pastikan history milik user yang sedang login
        if ($history->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $title = $history->title;
        $history->delete();

        // Catat ke audit log
        AuditLog::create([
            'user_id'   => Auth::id(),
            'user_name' => Auth::user()->name,
            'action'    => 'delete',
            'description' => "Menghapus riwayat perhitungan: {$title}",
        ]);

        return redirect()->route('history.index')
            ->with('success', 'Riwayat perhitungan berhasil dihapus.');
    }
}
