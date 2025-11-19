<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'nome',
        'telefone',
        'status',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
