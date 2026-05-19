<?php

namespace Tests\Feature\Invite;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class CreateInviteTest extends TestCase
{
    public function testOwnerCanCreateInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $receiver = User::factory()->create();

        $inviteData = [
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'member'
        ];

        $token = JWTAuth::fromUser($owner);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/invites', $inviteData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'sender_id',
                'receiver_id',
                'project_id',
                'role',
                'status',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('invites', $inviteData);
    }

    public function testLeaderCanCreateInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $leader = User::factory()->create();
        $project->users()->attach($leader->id, ['role' => 'leader']);
        $receiver = User::factory()->create();
        $inviteData = [
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'member'
        ];
        $token = JWTAuth::fromUser($leader);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/invites', $inviteData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'sender_id',
                'receiver_id',
                'project_id',
                'role',
                'status',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('invites', $inviteData);
    }

    public function testMemberCannotCreateInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $member = User::factory()->create();
        $project->users()->attach($member->id, ['role' => 'member']);
        $receiver = User::factory()->create();
        $inviteData = [
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'member'
        ];
        $token = JWTAuth::fromUser($member);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/invites', $inviteData);

        $response->assertStatus(403);
    }

    public function testObserverCannotCreateInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $observer = User::factory()->create();
        $project->users()->attach($observer->id, ['role' => 'observer']);
        $receiver = User::factory()->create();
        $inviteData = [
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'member'
        ];
        $token = JWTAuth::fromUser($observer);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/invites', $inviteData);

        $response->assertStatus(403);
    }
}