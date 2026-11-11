<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitacao extends Model
{
    protected $table = 'solicitacaos';

    protected $fillable = [
        'usuario_id',
        'objeto_id',
        'nome',
        'cpf',
        'email',
        'telefone',
        'mensagem',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'cpf' => 'encrypted',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function objeto(): BelongsTo
    {
        return $this->belongsTo(Objeto::class);
    }
}
