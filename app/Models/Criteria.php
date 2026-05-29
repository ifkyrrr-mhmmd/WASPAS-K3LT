<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    /**
     * Nama tabel yang digunakan model ini.
     *
     * @var string
     */
    protected $table = 'criteria';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'weight',
        'user_id',
    ];

    /**
     * Definisi cast atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight' => 'decimal:4',
        ];
    }

    /**
     * Relasi: Kriteria dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Kriteria memiliki banyak Skor Alternatif.
     */
    public function alternativeScores(): HasMany
    {
        return $this->hasMany(AlternativeScore::class);
    }
}
