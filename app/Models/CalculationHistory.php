<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationHistory extends Model
{
    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'lambda',
        'result_data',
    ];

    /**
     * Definisi cast atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'result_data' => 'array',
            'lambda' => 'decimal:2',
        ];
    }

    /**
     * Relasi: Riwayat perhitungan dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
