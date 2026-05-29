<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alternative extends Model
{
    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Relasi: Alternatif dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Alternatif memiliki banyak Skor.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(AlternativeScore::class);
    }

    /**
     * Mendapatkan nilai skor untuk kriteria tertentu.
     *
     * @param int $criteriaId ID kriteria yang dicari
     * @return float|null Nilai skor, atau null jika tidak ditemukan
     */
    public function scoreFor(int $criteriaId): ?float
    {
        $score = $this->scores->firstWhere('criteria_id', $criteriaId);

        return $score ? (float) $score->score : null;
    }
}
