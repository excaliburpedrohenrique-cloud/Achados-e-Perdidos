<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Objeto;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SolicitacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_a_request_and_contact_details_are_saved(): void
    {
        $objeto = $this->createAvailableObject();

        $response = $this->post(route('objetos.solicitacoes.store', $objeto), [
            'nome' => 'Estudante Exemplo',
            'cpf' => '123.456.789-00',
            'email' => 'estudante@example.com',
            'telefone' => '(11) 99999-1234',
        ]);

        $response->assertRedirect(route('objetos.solicitar', $objeto))
            ->assertSessionHas('sucesso');

        $solicitacao = Solicitacao::firstOrFail();

        $this->assertSame($objeto->id, $solicitacao->objeto_id);
        $this->assertNull($solicitacao->usuario_id);
        $this->assertSame('Estudante Exemplo', $solicitacao->nome);
        $this->assertSame('12345678900', $solicitacao->cpf);
        $this->assertSame('estudante@example.com', $solicitacao->email);
        $this->assertSame('(11) 99999-1234', $solicitacao->telefone);
        $this->assertSame('pendente', $solicitacao->status);

        $storedCpf = DB::table('solicitacaos')->value('cpf');
        $this->assertNotSame('12345678900', $storedCpf);
    }

    public function test_request_rejects_a_cpf_without_eleven_digits(): void
    {
        $objeto = $this->createAvailableObject();

        $this->from(route('objetos.solicitar', $objeto))
            ->post(route('objetos.solicitacoes.store', $objeto), [
                'nome' => 'Estudante Exemplo',
                'cpf' => '12345',
                'email' => 'estudante@example.com',
                'telefone' => '11999991234',
            ])
            ->assertRedirect(route('objetos.solicitar', $objeto))
            ->assertSessionHasErrors('cpf')
            ->assertSessionMissing('_old_input');

        $this->assertDatabaseCount('solicitacaos', 0);
    }

    public function test_secretaria_cannot_submit_a_student_request(): void
    {
        $objeto = $this->createAvailableObject();
        $secretaria = User::factory()->create();
        $secretaria->forceFill(['tipo' => 'Secretaria'])->save();

        $this->actingAs($secretaria)
            ->post(route('objetos.solicitacoes.store', $objeto), [
                'nome' => 'Pessoa da Secretaria',
                'cpf' => '12345678900',
                'email' => 'secretaria@example.com',
                'telefone' => '11999991234',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('solicitacaos', 0);
    }

    public function test_request_cannot_be_submitted_for_a_returned_object(): void
    {
        $objeto = $this->createAvailableObject();
        $objeto->update(['status' => 'devolvido']);

        $this->post(route('objetos.solicitacoes.store', $objeto), [
            'nome' => 'Estudante Exemplo',
            'cpf' => '12345678900',
            'email' => 'estudante@example.com',
            'telefone' => '11999991234',
        ])->assertNotFound();

        $this->assertDatabaseCount('solicitacaos', 0);
    }

    public function test_only_secretaria_can_view_the_requests_page(): void
    {
        $this->get(route('solicitacoes.index'))
            ->assertRedirect(route('login'));

        $student = User::factory()->create();

        $this->actingAs($student)
            ->get(route('solicitacoes.index'))
            ->assertForbidden();

        $secretaria = User::factory()->create();
        $secretaria->forceFill(['tipo' => 'Secretaria'])->save();

        $this->actingAs($secretaria)
            ->get(route('solicitacoes.index'))
            ->assertOk()
            ->assertSee('Pedidos recebidos');
    }

    public function test_secretaria_can_approve_request_and_remove_object_from_mural(): void
    {
        $objeto = $this->createAvailableObject();
        $solicitacao = $this->createPendingRequest($objeto);
        $outraSolicitacao = $this->createPendingRequest($objeto, 'outro@example.com');
        $secretaria = User::factory()->create();
        $secretaria->forceFill(['tipo' => 'Secretaria'])->save();

        $this->actingAs($secretaria)
            ->get(route('solicitacoes.index'))
            ->assertOk()
            ->assertSee('Estudante Exemplo')
            ->assertSee('12345678900')
            ->assertSee('estudante@example.com')
            ->assertSee('Aprovar retirada');

        $this->actingAs($secretaria)
            ->patch(route('solicitacoes.aprovar', $solicitacao))
            ->assertRedirect()
            ->assertSessionHas('sucesso');

        $this->assertSame('aprovada', $solicitacao->fresh()->status);
        $this->assertSame('recusada', $outraSolicitacao->fresh()->status);
        $this->assertSame('devolvido', $objeto->fresh()->status);

        $this->get(route('objetos.index'))
            ->assertOk()
            ->assertDontSee('Garrafa azul');

        $this->actingAs($secretaria)
            ->get(route('solicitacoes.index'))
            ->assertOk()
            ->assertSee('Garrafa azul')
            ->assertSee('Aprovada')
            ->assertSee('Objeto retirado e removido do mural.');
    }

    public function test_secretaria_can_reject_a_pending_request(): void
    {
        $solicitacao = $this->createPendingRequest($this->createAvailableObject());
        $secretaria = User::factory()->create();
        $secretaria->forceFill(['tipo' => 'Secretaria'])->save();

        $this->actingAs($secretaria)
            ->patch(route('solicitacoes.recusar', $solicitacao))
            ->assertRedirect()
            ->assertSessionHas('sucesso');

        $this->assertSame('recusada', $solicitacao->fresh()->status);
    }

    public function test_approved_object_no_longer_accepts_requests(): void
    {
        $objeto = $this->createAvailableObject();
        $objeto->update(['status' => 'devolvido']);
        $this->createPendingRequest($objeto, 'aprovada@example.com', 'aprovada');

        $this->get(route('objetos.solicitar', $objeto))
            ->assertNotFound();

        $this->post(route('objetos.solicitacoes.store', $objeto), [
            'nome' => 'Estudante Exemplo',
            'cpf' => '12345678900',
            'email' => 'estudante@example.com',
            'telefone' => '11999991234',
        ])->assertNotFound();
    }

    private function createAvailableObject(): Objeto
    {
        $categoria = Categoria::create([
            'nome' => 'Objeto pessoal',
            'descricao' => 'Objetos pessoais encontrados ou perdidos.',
        ]);

        return Objeto::create([
            'categoria_id' => $categoria->id,
            'titulo' => 'Garrafa azul',
            'local' => 'Pátio',
            'turno' => 'Tarde',
        ]);
    }

    private function createPendingRequest(Objeto $objeto, string $email = 'estudante@example.com', string $status = 'pendente'): Solicitacao
    {
        return Solicitacao::create([
            'objeto_id' => $objeto->id,
            'nome' => 'Estudante Exemplo',
            'cpf' => '12345678900',
            'email' => $email,
            'telefone' => '11999991234',
            'status' => $status,
        ]);
    }
}
