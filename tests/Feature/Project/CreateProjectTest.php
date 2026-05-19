<?php

namespace Tests\Feature\Project;

use App\Models\User;
use JWTAuth;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    public function testUserCanCreateProject()
    {
        $user = User::factory()->create();

        $projectData = [
            'name' => 'Test Project',
            'description' => 'Test Description'
        ];

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/projects', $projectData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('projects', $projectData);
    }
}