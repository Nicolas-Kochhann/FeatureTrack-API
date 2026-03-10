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

class DeleteStepTest extends TestCase
{
    public function testOwnerCanDeleteStep()
    {
        $owner = User::factory()->create();

        $project = Project::factory()
            ->hasAttached($owner, [
                'role' => UserProjectRole::OWNER
            ])
            ->create();

        $feature = Feature::factory()->create(['project_id' => $project->id]);
        $step = Step::factory()->create(['feature_id' => $feature->id]);

        $token = JWTAuth::fromUser($owner);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/steps/{$step->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('steps', ['id' => $step->id]);
    }

    public function testLeaderCanDeleteStep()
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

        $token = JWTAuth::fromUser($leader);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/steps/{$step->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('steps', ['id' => $step->id]);
    }

    public function testMemberCanDeleteStep()
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

        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/steps/{$step->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('steps', ['id' => $step->id]);
    }

    public function testObserverCannotDeleteStep()
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

        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/steps/{$step->id}");

        $response->assertStatus(403);
    }
}