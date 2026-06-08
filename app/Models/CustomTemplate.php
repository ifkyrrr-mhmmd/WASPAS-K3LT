<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'criteria_data',
    ];

    protected $casts = [
        'criteria_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
