<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel alternatif (kandidat) untuk SPK WASPAS.
     */
    public function up(): void
    {
        Schema::create('alternatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel alternatif.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternatives');
    }
};
