<?php

namespace Tests\Feature\Feature;

use App\Models\Feature;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    public function testUserCanListFeatures()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/projects/{$project->id}/features");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'description',
                    'links',
                    'project_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testUserCanShowFeature()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/features/{$feature->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'links',
                'project_id',
                'created_at',
                'updated_at'
            ]);
    }

    public function testUserCanCreateFeature()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $featureData = [
            'name' => 'Test Feature',
            'description' => 'Test Description',
            'links' => 'http://example.com'
        ];
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/projects/{$project->id}/features", $featureData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'links',
                'project_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('features', $featureData);
    }

    public function testUserCanUpdateFeature()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $updatedData = [
            'name' => 'Updated Feature',
            'description' => 'Updated Description',
            'links' => 'http://updated.com'
        ];
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/features/{$feature->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'links',
                'project_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('features', $updatedData);
    }

    public function testUserCanDeleteFeature()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/features/{$feature->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }
}