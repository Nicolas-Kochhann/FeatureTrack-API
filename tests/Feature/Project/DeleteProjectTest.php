<?php

namespace Tests\Feature\Project;

use App\Enums\UserProjectRole;
use App\Models\Project;
use App\Models\User;
use JWTAuth;
use Tests\TestCase;

class DeleteProjectTest extends TestCase
{
    public function testOwnerCanDeleteProject()
    {
        $user = User::factory()->create();

        $project = Project::factory()
            ->hasAttached($user, [
                'role' => UserProjectRole::OWNER
            ])
            ->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function testLeaderCannotDeleteProject()
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

        $token = JWTAuth::fromUser($leader);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function testMemberCannotDeleteProject()
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

        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function testObserverCannotDeleteProject()
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

        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function testNonProjectUserCannotDeleteProject()
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
        ])->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

}