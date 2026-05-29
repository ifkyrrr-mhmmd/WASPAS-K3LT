<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel log audit untuk mencatat aktivitas pengguna.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name');
            $table->enum('action', ['create', 'update', 'delete', 'calculate', 'export']);
            $table->text('description');
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel log audit.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
