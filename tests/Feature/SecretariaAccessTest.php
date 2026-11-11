<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecretariaAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_login_when_trying_to_open_the_object_form(): void
    {
        $this->get(route('objetos.create'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_submit_an_object(): void
    {
        $this->post(route('objetos.store'), [])
            ->assertRedirect(route('login'));
    }

    public function test_user_without_secretaria_type_cannot_open_the_object_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('objetos.create'))
            ->assertForbidden();
    }

    public function test_secretaria_user_can_open_the_object_form(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['tipo' => 'Secretaria'])->save();

        $this->actingAs($user)
            ->get(route('objetos.create'))
            ->assertOk()
            ->assertSee('Cadastrar objeto')
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="foto"', false);
    }

    public function test_login_rejects_users_without_secretaria_type(): void
    {
        $user = User::factory()->create([
            'email' => 'aluno@example.com',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_secretaria_user_can_log_in(): void
    {
        $user = User::factory()->create([
            'email' => 'secretaria@example.com',
            'password' => 'password',
        ]);
        $user->forceFill(['tipo' => 'Secretaria'])->save();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('objetos.create'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_page_is_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Acesso da Secretaria');
    }
}
