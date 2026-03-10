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

class UpdateStepTest extends TestCase
{
    public function testOwnerCanUpdateStep()
    {
        $owner = User::factory()->create();

        $project = Project::factory()
            ->hasAttached($owner, [
                'role' => UserProjectRole::OWNER
            ])
            ->create();
        
        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $step = Step::factory()->create(['feature_id' => $feature->id]);

        $updatedData = [
            'title' => 'Updated Step',
            'completed' => true
        ];

        $token = JWTAuth::fromUser($owner);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/steps/{$step->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'completed',
                'feature_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('steps', $updatedData);
    }

    public function testLeaderCanUpdateStep()
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
        $step = Step::factory()->create(['feature_id' => $feature->id]);

        $updatedData = [
            'title' => 'Updated Step',
            'completed' => true
        ];
        
        $token = JWTAuth::fromUser($leader);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/steps/{$step->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'completed',
                'feature_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('steps', $updatedData);
    }

    public function testMemberCanUpdateStep()
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
        $step = Step::factory()->create(['feature_id' => $feature->id]);

        $updatedData = [
            'title' => 'Updated Step',
            'completed' => true
        ];

        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/steps/{$step->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'completed',
                'feature_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('steps', $updatedData);
    }

    public function testObserverCannotUpdateStep()
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
        $step = Step::factory()->create(['feature_id' => $feature->id]);

        $updatedData = [
            'title' => 'Updated Step',
            'completed' => true
        ];

        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/steps/{$step->id}", $updatedData);

        $response->assertStatus(403);
    }
}