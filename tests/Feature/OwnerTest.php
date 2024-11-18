<?php

namespace Tests\Feature;

use App\Models\Owner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_all_owners()
    {
        Owner::factory()->count(10)->create();

        $response = $this->getJson('/api/owners');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'cpf',
                        'birth_date',
                        'email',
                        'tower',
                        'apartment_number',
                        'garage',
                        'rented',
                        'paid',
                    ],
                ],
            ]);
    }

    public function test_create_a_new_owner()
    {
        $ownerData = [
            'name' => 'João Silva',
            'cpf' => '12345678900',
            'birth_date' => '1990-01-01',
            'email' => 'joao@exemplo.com',
            'tower' => 'A',
            'apartment_number' => '101',
            'garage' => '1',
            'rented' => true,
            'paid' => true,
        ];

        $response = $this->postJson('/api/owners', $ownerData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Morador cadastrado com sucesso!',
                'data' => $ownerData,
            ]);

        $this->assertDatabaseHas('owners', $ownerData);
    }

    public function test_validate_cpf_when_creating_owner()
    {
        $ownerData = [
            'name' => 'João Silva',
            'cpf' => 'invalid_cpf',
            'birth_date' => '1990-01-01',
            'email' => 'joao@exemplo.com',
            'tower' => 'A',
            'apartment_number' => '101',
            'garage' => '1',
            'rented' => true,
            'paid' => true,
        ];

        $response = $this->postJson('/api/owners', $ownerData);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'CPF informado inválido!',
            ]);

        $this->assertDatabaseMissing('owners', ['cpf' => 'invalid_cpf']);
    }

    public function test_show_a_specific_owner()
    {
        $owner = Owner::factory()->create();

        $response = $this->getJson("/api/owners/{$owner->id}");

        $response->assertStatus(200)
            ->assertJson($owner->toArray());
    }

    public function test_return_404_if_owner_not_found()
    {
        $response = $this->getJson('/api/owners/invalid-id');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Morador não encontrado!',
            ]);
    }

    /** @test */
    public function test_update_an_owner()
    {
        $owner = Owner::factory()->create();

        $updatedData = [
            'name' => 'João Atualizado',
            'cpf' => '98765432100',
            'birth_date' => '1985-05-05',
            'email' => 'joao.atualizado@exemplo.com',
            'tower' => 'B',
            'apartment_number' => '202',
            'garage' => '2',
            'rented' => false,
            'paid' => false,
        ];

        $response = $this->putJson("/api/owners/{$owner->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Dados do Morador atualizado com sucesso',
            ]);

        $this->assertDatabaseHas('owners', $updatedData);
    }

    /** @test */
    public function test_delete_an_owner()
    {
        $owner = Owner::factory()->create();

        $response = $this->deleteJson("/api/owners/{$owner->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Morador deletado com sucesso!',
            ]);

        $this->assertDatabaseMissing('owners', ['id' => $owner->id]);
    }
}