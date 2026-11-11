<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Objeto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ObjetoFotoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretaria_can_create_an_object_with_a_photo(): void
    {
        Storage::fake('public');

        $categoria = Categoria::create([
            'nome' => 'Eletrônicos',
            'descricao' => null,
        ]);

        $usuario = User::factory()->create();
        $usuario->forceFill(['tipo' => 'Secretaria'])->save();

        $imageContents = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/lS8AAAAASUVORK5CYII=',
            true,
        );

        $response = $this->actingAs($usuario)->post(route('objetos.store'), [
            'categoria_id' => $categoria->id,
            'titulo' => 'Fone de ouvido',
            'local' => 'Biblioteca',
            'turno' => 'Manhã',
            'descricao' => 'Fone preto.',
            'foto' => UploadedFile::fake()->createWithContent('fone.png', $imageContents),
        ]);

        $response->assertRedirect(route('objetos.create'))
            ->assertSessionHas('sucesso');

        $objeto = Objeto::where('titulo', 'Fone de ouvido')->firstOrFail();

        $this->assertNotNull($objeto->foto);
        Storage::disk('public')->assertExists($objeto->foto);
    }

    public function test_invalid_photo_is_rejected_without_creating_the_object(): void
    {
        Storage::fake('public');

        $categoria = Categoria::create([
            'nome' => 'Eletrônicos',
            'descricao' => null,
        ]);

        $usuario = User::factory()->create();
        $usuario->forceFill(['tipo' => 'Secretaria'])->save();

        $this->actingAs($usuario)->from(route('objetos.create'))->post(route('objetos.store'), [
            'categoria_id' => $categoria->id,
            'titulo' => 'Fone de ouvido',
            'local' => 'Biblioteca',
            'turno' => 'Manhã',
            'foto' => UploadedFile::fake()->create('arquivo.pdf', 100, 'application/pdf'),
        ])
            ->assertRedirect(route('objetos.create'))
            ->assertSessionHasErrors('foto');

        $this->assertDatabaseMissing('objetos', ['titulo' => 'Fone de ouvido']);
        $this->assertSame([], Storage::disk('public')->allFiles('objetos'));
    }
}
