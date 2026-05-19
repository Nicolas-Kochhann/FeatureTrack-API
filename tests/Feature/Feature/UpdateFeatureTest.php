<?php

namespace Tests\Feature\Feature;

use App\Models\Feature;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class UpdateFeatureTest extends TestCase
{
    public function testOwnerCanUpdateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $updatedData = [
            'name' => 'Updated Feature',
            'description' => 'Updated Description',
            'links' => 'http://updated.com'
        ];
        $token = JWTAuth::fromUser($owner);

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

    public function testLeaderCanUpdateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $leader = User::factory()->create();
        $project->users()->attach($leader->id, ['role' => 'leader']);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $updatedData = [
            'name' => 'Updated Feature',
            'description' => 'Updated Description',
            'links' => 'http://updated.com'
        ];
        $token = JWTAuth::fromUser($leader);

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

    public function testMemberCannotUpdateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $member = User::factory()->create();
        $project->users()->attach($member->id, ['role' => 'member']);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $updatedData = [
            'name' => 'Updated Feature',
            'description' => 'Updated Description',
            'links' => 'http://updated.com'
        ];
        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/features/{$feature->id}", $updatedData);

        $response->assertStatus(403);
    }

    public function testObserverCannotUpdateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $observer = User::factory()->create();
        $project->users()->attach($observer->id, ['role' => 'observer']);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $updatedData = [
            'name' => 'Updated Feature',
            'description' => 'Updated Description',
            'links' => 'http://updated.com'
        ];
        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/features/{$feature->id}", $updatedData);

        $response->assertStatus(403);
    }
}