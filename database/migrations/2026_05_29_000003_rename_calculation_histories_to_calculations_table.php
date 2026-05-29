<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename tabel calculation_histories menjadi calculations sesuai spesifikasi.
     */
    public function up(): void
    {
        Schema::rename('calculation_histories', 'calculations');
    }

    /**
     * Mengembalikan nama tabel ke calculation_histories.
     */
    public function down(): void
    {
        Schema::rename('calculations', 'calculation_histories');
    }
};
