<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel riwayat perhitungan WASPAS.
     */
    public function up(): void
    {
        Schema::create('calculation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->decimal('lambda', 3, 2)->default(0.50);
            $table->longText('result_data'); // Menyimpan snapshot perhitungan dalam format JSON
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel riwayat perhitungan.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_histories');
    }
};
