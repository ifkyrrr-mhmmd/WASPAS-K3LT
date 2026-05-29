<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'last_login_at',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Definisi cast atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: User memiliki banyak Kriteria.
     */
    public function criteria(): HasMany
    {
        return $this->hasMany(Criteria::class);
    }

    /**
     * Relasi: User memiliki banyak Alternatif.
     */
    public function alternatives(): HasMany
    {
        return $this->hasMany(Alternative::class);
    }

    /**
     * Relasi: User memiliki banyak Riwayat Perhitungan.
     */
    public function calculations(): HasMany
    {
        return $this->hasMany(Calculation::class);
    }

    /**
     * Relasi: User memiliki banyak Log Audit.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Periksa apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
