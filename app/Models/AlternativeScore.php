<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlternativeScore extends Model
{
    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'alternative_id',
        'criteria_id',
        'score',
    ];

    /**
     * Definisi cast atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'decimal:4',
        ];
    }

    /**
     * Relasi: Skor dimiliki oleh satu Alternatif.
     */
    public function alternative(): BelongsTo
    {
        return $this->belongsTo(Alternative::class);
    }

    /**
     * Relasi: Skor dimiliki oleh satu Kriteria.
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}
