<?php

namespace Tests\Feature\Invite;

use App\Models\Invite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class InviteTest extends TestCase
{
    public function testUserCanListReceivedInvites()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $invite = Invite::factory()->create(['receiver_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/receivedInvites');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'sender_id',
                    'receiver_id',
                    'project_id',
                    'role',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testUserCanListSentInvites()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $invite = Invite::factory()->create(['sender_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/sentInvites');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'sender_id',
                    'receiver_id',
                    'project_id',
                    'role',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testUserCanShowInvite()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $invite = Invite::factory()->create(['sender_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/invites/{$invite->id}");

        $response->assertStatus(200)
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
    }

    public function testUserCanCreateInvite()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $receiver = User::factory()->create();
        $inviteData = [
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'developer'
        ];
        $token = JWTAuth::fromUser($user);

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

    public function testUserCanUpdateInvite()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $invite = Invite::factory()->create(['sender_id' => $user->id]);
        $updatedData = [
            'role' => 'manager'
        ];
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/invites/{$invite->id}", $updatedData);

        $response->assertStatus(200)
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

        $this->assertDatabaseHas('invites', $updatedData);
    }

    public function testUserCanRespondToInvite()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $invite = Invite::factory()->create(['receiver_id' => $user->id]);
        $responseData = [
            'status' => 'accepted'
        ];
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/invites/{$invite->id}/respond", $responseData);

        $response->assertStatus(200)
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

        $this->assertDatabaseHas('invites', $responseData);
    }

    public function testUserCanDeleteInvite()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $invite = Invite::factory()->create(['sender_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/invites/{$invite->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('invites', ['id' => $invite->id]);
    }
}