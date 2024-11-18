<?php

namespace Tests\Feature;

use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PositionTest extends TestCase
{

    public function test_get_positions(): void
    {
        $response = $this->get('/api/positions');

        $response->assertStatus(200);
    }

    public function test_post_positions(): void
    {
        $data = ["title" => "Novo cargo"];

        $response = $this->postJson('api/positions', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Cargo criado com sucesso!',
                'data' => [
                    'title' => 'Novo Cargo'
                ]
            ]);
    }

    public function test_show_position(): void
    {
        $position = Position::factory()->create();

        $response = $this->getJson("/api/positions/{$position->id}");

        $response->assertStatus(200)
            ->assertJson(['title' => $position->title]);
    }

    public function test_update_position(): void
    {
        $position = Position::factory()->create(['title' => 'Cargo Antigo']);
        $data = ['title' => 'Cargo Atualizado'];

        $response = $this->putJson("/api/positions/{$position->id}", $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cargo atualizado com sucesso!']);

        $this->assertDatabaseHas('positions', ['id' => $position->id, 'title' => 'Cargo Atualizado']);
    }

    public function test_delete_position(): void
    {
        $position = Position::factory()->create();

        $response = $this->deleteJson("/api/positions/{$position->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cargo deletado com sucesso!']);

        $this->assertDatabaseMissing('positions', ['id' => $position->id]);
    }
}
