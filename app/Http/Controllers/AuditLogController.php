<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Menampilkan daftar log audit milik pengguna yang sedang login.
     */
    public function index()
    {
        $logs = AuditLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('audit.index', compact('logs'));
    }

    /**
     * Menghapus semua log audit milik pengguna yang sedang login.
     */
    public function clear()
    {
        $userId = Auth::id();
        
        // Hapus semua log audit milik user yang sedang login
        AuditLog::where('user_id', $userId)->delete();

        // Buat log baru untuk mencatat aktivitas pembersihan log ini
        AuditLog::create([
            'user_id' => $userId,
            'user_name' => Auth::user()->name,
            'action' => 'delete',
            'description' => 'Membersihkan seluruh riwayat log audit.',
        ]);

        return redirect()->route('audit.index')->with('success', 'Semua data log audit Anda berhasil dihapus.');
    }
}
