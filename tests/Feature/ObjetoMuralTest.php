<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Objeto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ObjetoMuralTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_the_object_mural(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Mural de objetos');
    }

    public function test_mural_shows_objects_with_their_categories(): void
    {
        $categoria = Categoria::create([
            'nome' => 'Eletrônicos',
            'descricao' => 'Aparelhos e acessórios',
        ]);

        Objeto::create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Fone de ouvido',
            'local' => 'Biblioteca',
            'turno' => 'Manhã',
            'descricao' => 'Fone preto encontrado na mesa.',
        ]);

        $this->get('/objetos')
            ->assertOk()
            ->assertSee('Fone de ouvido')
            ->assertSee('Eletrônicos')
            ->assertSee('Biblioteca')
            ->assertSee('Manhã')
            ->assertSee('Fone preto encontrado na mesa.')
            ->assertSee('Disponível');
    }

    public function test_mural_shows_an_empty_state_when_there_are_no_objects(): void
    {
        $this->get('/objetos')
            ->assertOk()
            ->assertSee('Ainda não há objetos no mural')
            ->assertSee('Acesso da Secretaria');
    }

    public function test_mural_does_not_show_returned_objects(): void
    {
        $categoria = Categoria::create([
            'nome' => 'Objeto pessoal',
            'descricao' => 'Objetos pessoais encontrados ou perdidos.',
        ]);

        Objeto::create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Objeto retirado',
            'local' => 'Pátio',
            'turno' => 'Tarde',
            'status' => 'devolvido',
        ]);

        $this->get(route('objetos.index'))
            ->assertOk()
            ->assertSee('Ainda não há objetos no mural')
            ->assertDontSee('Objeto retirado');
    }

    public function test_mural_displays_an_uploaded_object_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('objetos/fone.png', 'fake image contents');

        $categoria = Categoria::create([
            'nome' => 'Eletrônicos',
            'descricao' => 'Aparelhos e acessórios',
        ]);

        Objeto::create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Fone de ouvido',
            'local' => 'Biblioteca',
            'turno' => 'Manhã',
            'foto' => 'objetos/fone.png',
        ]);

        $this->get(route('objetos.index'))
            ->assertOk()
            ->assertSee('src="'.Storage::disk('public')->url('objetos/fone.png').'"', false)
            ->assertSee('aria-label="Ampliar foto de Fone de ouvido"', false)
            ->assertSee('aria-labelledby="image-dialog-title"', false)
            ->assertSee('Ampliar foto de Fone de ouvido');
    }

    public function test_guest_can_open_the_request_page_for_an_object(): void
    {
        $categoria = Categoria::create([
            'nome' => 'Objeto pessoal',
            'descricao' => 'Objetos pessoais encontrados ou perdidos.',
        ]);

        $objeto = Objeto::create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Garrafa azul',
            'local' => 'Pátio',
            'turno' => 'Tarde',
            'descricao' => 'Garrafa com adesivo.',
        ]);

        $this->get(route('objetos.solicitar', $objeto))
            ->assertOk()
            ->assertSee('Solicitar objeto')
            ->assertSee('Garrafa azul')
            ->assertSee('Objeto pessoal')
            ->assertSee('Garrafa com adesivo.')
            ->assertSee('Seus dados serão usados pela Secretaria')
            ->assertSee('name="nome"', false)
            ->assertSee('name="cpf"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="telefone"', false)
            ->assertSee('Enviar requerimento')
            ->assertSee('method="POST"', false);
    }

    public function test_mural_has_a_request_button_for_each_object(): void
    {
        $categoria = Categoria::create([
            'nome' => 'Material Escolar',
            'descricao' => 'Materiais escolares encontrados ou perdidos.',
        ]);

        $objeto = Objeto::create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Caderno',
            'local' => 'Sala 1',
            'turno' => 'Manhã',
        ]);

        $this->get(route('objetos.index'))
            ->assertOk()
            ->assertSee(route('objetos.solicitar', $objeto), false)
            ->assertSee('Solicitar');
    }

    public function test_mural_hides_the_request_button_from_secretaria(): void
    {
        $categoria = Categoria::create([
            'nome' => 'Material Escolar',
            'descricao' => 'Materiais escolares encontrados ou perdidos.',
        ]);

        Objeto::create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Caderno',
            'local' => 'Sala 1',
            'turno' => 'Manhã',
        ]);

        $secretaria = User::factory()->create();
        $secretaria->forceFill(['tipo' => 'Secretaria'])->save();

        $this->actingAs($secretaria)
            ->get(route('objetos.index'))
            ->assertOk()
            ->assertDontSee('Solicitar')
            ->assertSee(route('solicitacoes.index'), false)
            ->assertSee('Solicitações');
    }
}
