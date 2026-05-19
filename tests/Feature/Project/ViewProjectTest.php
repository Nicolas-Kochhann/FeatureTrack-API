<?php

namespace Tests\Feature\Project;

use App\Models\User;
use App\Models\Project;
use App\Enums\UserProjectRole;
use JWTAuth;
use Tests\TestCase;

class ViewProjectTest extends TestCase
{
    public function testUserCanViewProject()
    {
        $user = User::factory()->create();

        $project = Project::factory()
            ->hasAttached(User::factory(), [
                'role' => UserProjectRole::OWNER
            ])
            ->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/project/'.$project->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'created_at',
                'updated_at'
            ]);
    }
}