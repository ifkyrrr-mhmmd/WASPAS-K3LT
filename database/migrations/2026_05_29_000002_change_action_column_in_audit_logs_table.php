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
        // Untuk MySQL, ubah enum menjadi varchar(50)
        DB::statement("ALTER TABLE audit_logs MODIFY COLUMN `action` VARCHAR(50) NOT NULL");
    }

    /**
     * Mengembalikan kolom action ke enum.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE audit_logs MODIFY COLUMN `action` ENUM('create','update','delete','calculate','export') NOT NULL");
    }
};
