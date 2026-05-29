<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensitivityController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kriteria & Alternatif (CRUD terpisah, dipertahankan sebagai opsi tambahan)
    Route::resource('criteria', CriteriaController::class);
    Route::resource('alternatives', AlternativeController::class);

    // Kalkulator WASPAS (Step-by-Step)
    Route::get('/calculation', [CalculationController::class, 'index'])->name('calculation.index');
    Route::get('/calculation/print', [CalculationController::class, 'print'])->name('calculation.print');
    Route::get('/calculation/export-pdf', [CalculationController::class, 'exportPdf'])->name('calculation.export-pdf');
    Route::post('/calculation/save', [CalculationController::class, 'saveHistory'])->name('calculation.save');
    Route::post('/calculation/store-all', [CalculationController::class, 'storeAll'])->name('calculation.store-all');

    // Riwayat Perhitungan
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{history}', [HistoryController::class, 'show'])->name('history.show');
    Route::get('/history/{history}/print', [HistoryController::class, 'print'])->name('history.print');
    Route::get('/history/{history}/export-pdf', [HistoryController::class, 'exportPdf'])->name('history.export-pdf');
    Route::delete('/history/{history}', [HistoryController::class, 'destroy'])->name('history.destroy');

    // Analisis Sensitivitas Kriteria
    Route::get('/sensitivity', [SensitivityController::class, 'index'])->name('sensitivity.index');
    Route::post('/sensitivity/analyze', [SensitivityController::class, 'analyze'])->name('sensitivity.analyze');

    // Jejak Audit (Audit Trail Log)
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');

    // Template Preset K3LT
    Route::post('/template/load', [TemplateController::class, 'load'])->name('template.load');
});

require __DIR__.'/auth.php';
