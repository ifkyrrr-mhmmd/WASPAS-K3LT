<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel kriteria untuk SPK WASPAS.
     */
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['Benefit', 'Cost'])->default('Benefit');
            $table->decimal('weight', 8, 4);
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel kriteria.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};
