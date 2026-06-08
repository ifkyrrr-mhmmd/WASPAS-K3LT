<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mengubah kolom action dari enum ke string untuk fleksibilitas pencatatan aksi audit.
     */
    public function up(): void
    {
        // Gunakan Schema Builder Laravel agar kompatibel dengan SQLite (saat testing) dan MySQL (saat hosting)
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('action', 50)->change();
        });
    }

    /**
     * Mengembalikan kolom action ke enum.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Kita bisa kembalikan ke enum jika didukung, atau biarkan varchar jika down tidak terlalu kritis
            // Untuk MySQL/SQLite, change kembali ke enum:
            $table->enum('action', ['create', 'update', 'delete', 'calculate', 'export'])->change();
        });
    }
};
