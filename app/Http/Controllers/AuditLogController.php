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
}
