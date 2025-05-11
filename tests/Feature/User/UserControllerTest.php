<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_permissions()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $unauthorizedUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($admin);

        $response = $this->getJson("/users/{$unauthorizedUser->id}/edit");
        $response->assertStatus(200);

        $this->actingAs($unauthorizedUser);

        $response = $this->getJson("/users/{$unauthorizedUser->id}/edit");
        $response->assertStatus(403);
    }

    public function test_datatable_structure()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        $response = $this->getJson('/users/datatable');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'draw',
                'recordsTotal',
                'recordsFiltered',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'status_label',
                        'actions',
                    ]
                ]
            ]);
    }

    /**
     * Teste de criação de usuário.
     *
     * @return void
     */
    public function test_create_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        $data = [
            'name' => 'Teste Usuário',
            'email' => 'teste@usuario.com',
            'password' => 'Senha@1234',
            'status' => true
        ];

        $response = $this->postJson('/users', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Usuário criado com sucesso!'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'teste@usuario.com'
        ]);
    }


    /**
     * Teste de atualização de usuário.
     *
     * @return void
     */
    public function test_update_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $user = User::factory()->create();

        $data = [
            'name' => 'Nome Atualizado',
            'email' => 'novoemail@usuario.com',
            'password' => 'NovaSenha@123',
            'status' => false
        ];

        $this->actingAs($admin);

        $response = $this->putJson("/users/{$user->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Usuário atualizado com sucesso!'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Atualizado',
            'email' => 'novoemail@usuario.com',
            'status' => false
        ]);
    }


    /**
     * Teste de deleção de usuário.
     *
     * @return void
     */
    public function test_delete_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $user = User::factory()->create();

        $this->actingAs($admin);

        $response = $this->deleteJson("/users/{$user->id}");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Usuário excluído com sucesso!'
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
