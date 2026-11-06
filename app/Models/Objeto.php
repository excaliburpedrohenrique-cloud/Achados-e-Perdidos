<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Objeto extends Model
{
    protected $fillable = [
        'categoria_id',
        'titulo',
        'local',
        'turno',
        'descricao',
        'foto',
        'status',
    ];
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function solicitacoes(): HasMany
    {
        return $this->hasMany(Solicitacao::class);
    }
}
