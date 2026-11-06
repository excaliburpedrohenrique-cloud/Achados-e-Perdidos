<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];
    public function objetos(): HasMany
    {
        return $this->hasMany(Objeto::class);
    }
}
