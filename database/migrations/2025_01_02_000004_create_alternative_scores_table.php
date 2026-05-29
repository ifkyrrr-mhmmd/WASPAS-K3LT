<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel skor alternatif (nilai tiap alternatif per kriteria).
     */
    public function up(): void
    {
        Schema::create('alternative_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternative_id')->constrained('alternatives')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('score', 8, 4);
            $table->timestamps();

            // Setiap alternatif hanya boleh punya satu skor per kriteria
            $table->unique(['alternative_id', 'criteria_id']);
        });
    }

    /**
     * Menghapus tabel skor alternatif.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternative_scores');
    }
};
