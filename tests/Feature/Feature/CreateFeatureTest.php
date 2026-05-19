<?php

namespace Tests\Feature\Feature;

use App\Models\Feature;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class CreateFeatureTest extends TestCase
{
    private function authenticate($user)
    {
        return JWTAuth::fromUser($user);
    }

    public function testOwnerCanCreateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $featureData = [
            'name' => 'Test Feature',
            'description' => 'Test Description',
            'links' => 'http://example.com'
        ];
        $token = JWTAuth::fromUser($owner);

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

    public function testLeaderCanCreateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $leader = User::factory()->create();
        $project->users()->attach($leader->id, ['role' => 'leader']);
        $featureData = [
            'name' => 'Test Feature',
            'description' => 'Test Description',
            'links' => 'http://example.com'
        ];
        $token = JWTAuth::fromUser($leader);

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

    public function testMemberCannotCreateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $member = User::factory()->create();
        $project->users()->attach($member->id, ['role' => 'member']);
        $featureData = [
            'name' => 'Test Feature',
            'description' => 'Test Description',
            'links' => 'http://example.com'
        ];
        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/projects/{$project->id}/features", $featureData);

        $response->assertStatus(403);
    }

    public function testObserverCannotCreateFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $observer = User::factory()->create();
        $project->users()->attach($observer->id, ['role' => 'observer']);
        $featureData = [
            'name' => 'Test Feature',
            'description' => 'Test Description',
            'links' => 'http://example.com'
        ];
        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/projects/{$project->id}/features", $featureData);

        $response->assertStatus(403);
    }
}