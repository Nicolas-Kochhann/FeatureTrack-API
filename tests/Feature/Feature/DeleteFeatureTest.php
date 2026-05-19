<?php

namespace Tests\Feature\Feature;

use App\Models\Feature;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class DeleteFeatureTest extends TestCase
{
    public function testOwnerCanDeleteFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $token = JWTAuth::fromUser($owner);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/features/{$feature->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    public function testLeaderCanDeleteFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $leader = User::factory()->create();
        $project->users()->attach($leader->id, ['role' => 'leader']);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $token = JWTAuth::fromUser($leader);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/features/{$feature->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    public function testMemberCannotDeleteFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $member = User::factory()->create();
        $project->users()->attach($member->id, ['role' => 'member']);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/features/{$feature->id}");

        $response->assertStatus(403);
    }

    public function testObserverCannotDeleteFeature()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $observer = User::factory()->create();
        $project->users()->attach($observer->id, ['role' => 'observer']);
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/features/{$feature->id}");

        $response->assertStatus(403);
    }
}