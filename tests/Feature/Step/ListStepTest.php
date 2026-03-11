<?php

namespace Tests\Feature\Step;

use App\Enums\UserProjectRole;
use App\Models\Feature;
use App\Models\Project;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class ListStepTest extends TestCase
{
    public function testOwnerCanListSteps()
    {
        $owner = User::factory()->create();

        $project = Project::factory()
            ->hasAttached($owner, [
                'role' => UserProjectRole::OWNER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);
        Step::factory()->create(['feature_id' => $feature->id]);

        $token = JWTAuth::fromUser($owner);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/features/{$feature->id}/steps");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'completed',
                    'feature_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testLeaderCanListStep()
    {
        $leader = User::factory()->create();

        $project = Project::factory()
            ->hasAttached(User::factory(), [
                'role' => UserProjectRole::OWNER
            ])
            ->hasAttached($leader, [
                'role' => UserProjectRole::LEADER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);
        Step::factory()->create(['feature_id' => $feature->id]);

        $token = JWTAuth::fromUser($leader);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/features/{$feature->id}/steps");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'completed',
                    'feature_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testMemberCanListSteps()
    {
        $member = User::factory()->create();

        $project = Project::factory()
            ->hasAttached(User::factory(), [
                'role' => UserProjectRole::OWNER
            ])
            ->hasAttached($member, [
                'role' => UserProjectRole::MEMBER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);
        Step::factory()->create(['feature_id' => $feature->id]);

        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/features/{$feature->id}/steps");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'completed',
                    'feature_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testObserverCanListSteps()
    {
        $observer = User::factory()->create();

        $project = Project::factory()
            ->hasAttached(User::factory(), [
                'role' => UserProjectRole::OWNER
            ])
            ->hasAttached($observer, [
                'role' => UserProjectRole::OBSERVER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);
        Step::factory()->create(['feature_id' => $feature->id]);

        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/features/{$feature->id}/steps");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'completed',
                    'feature_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testNonProjectUserCannotListSteps()
    {
        $nonProjectUser = User::factory()->create();

        $project = Project::factory()
            ->hasAttached(User::factory(), [
                'role' => UserProjectRole::OWNER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);
        Step::factory()->create(['feature_id' => $feature->id]);

        $token = JWTAuth::fromUser($nonProjectUser);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/features/{$feature->id}/steps");

        $response->assertStatus(403);
    }
}