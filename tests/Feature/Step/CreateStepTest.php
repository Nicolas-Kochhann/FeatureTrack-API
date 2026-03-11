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

class CreateStepTest extends TestCase
{
    public function testOwnerCanCreateStep()
    {
        $owner = User::factory()->create();

        $project = Project::factory()
            ->hasAttached($owner, [
                'role' => UserProjectRole::OWNER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);

        $stepData = [
            'title' => 'Test Step'
        ];

        $token = JWTAuth::fromUser($owner);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/features/{$feature->id}/steps", $stepData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'completed',
                'feature_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('steps', $stepData);
    }

    public function testLeaderCanCreateStep()
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

        $stepData = [
            'title' => 'Test Step'
        ];

        $token = JWTAuth::fromUser($leader);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/features/{$feature->id}/steps", $stepData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'completed',
                'feature_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('steps', $stepData);
    }

    public function testMemberCanCreateStep()
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

        $stepData = [
            'title' => 'Test Step'
        ];

        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/features/{$feature->id}/steps", $stepData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'completed',
                'feature_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('steps', $stepData);
    }

    public function testObserverCannotCreateStep()
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

        $stepData = [
            'title' => 'Test Step'
        ];

        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/features/{$feature->id}/steps", $stepData);

        $response->assertStatus(403);
    }

    public function testNonProjectUserCannotCreateStep()
    {
        $nonProjectUser = User::factory()->create();

        $project = Project::factory()
            ->hasAttached(User::factory(), [
                'role' => UserProjectRole::OWNER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);

        $stepData = [
            'title' => 'Test Step'
        ];

        $token = JWTAuth::fromUser($nonProjectUser);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson("/api/features/{$feature->id}/steps", $stepData);

        $response->assertStatus(403);
    }
}