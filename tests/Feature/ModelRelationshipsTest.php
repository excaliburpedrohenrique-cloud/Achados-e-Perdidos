<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Objeto;
use App\Models\Solicitacao;
use App\Models\User;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    public function test_categoria_has_many_objetos(): void
    {
        $relationship = (new Categoria)->objetos();

        $this->assertSame(Objeto::class, $relationship->getRelated()::class);
        $this->assertSame('categoria_id', $relationship->getForeignKeyName());
    }

    public function test_objeto_belongs_to_categoria_and_has_many_solicitacoes(): void
    {
        $objeto = new Objeto;

        $this->assertSame('categoria_id', $objeto->categoria()->getForeignKeyName());
        $this->assertSame('objeto_id', $objeto->solicitacoes()->getForeignKeyName());
    }

    public function test_solicitacao_belongs_to_usuario_and_objeto(): void
    {
        $solicitacao = new Solicitacao;

        $this->assertSame('solicitacaos', $solicitacao->getTable());
        $this->assertSame('usuario_id', $solicitacao->usuario()->getForeignKeyName());
        $this->assertSame('objeto_id', $solicitacao->objeto()->getForeignKeyName());
    }

    public function test_usuario_has_many_solicitacoes(): void
    {
        $relationship = (new User)->solicitacoes();

        $this->assertSame(Solicitacao::class, $relationship->getRelated()::class);
        $this->assertSame('usuario_id', $relationship->getForeignKeyName());
    }
}
