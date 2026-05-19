<?php

namespace Tests\Feature\Invite;

use App\Models\Invite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class UpdateInviteTest extends TestCase
{
    public function testSenderCanUpdateInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $receiver = User::factory()->create();
        $invite = Invite::factory()->create([
            'sender_id' => $owner->id,
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'developer'
        ]);
        $updatedData = [
            'role' => 'manager'
        ];
        $token = JWTAuth::fromUser($owner);

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

    public function testNonSenderCannotUpdateInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $receiver = User::factory()->create();
        $invite = Invite::factory()->create([
            'sender_id' => $owner->id,
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'developer'
        ]);
        $nonSender = User::factory()->create();
        $updatedData = [
            'role' => 'manager'
        ];
        $token = JWTAuth::fromUser($nonSender);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/invites/{$invite->id}", $updatedData);

        $response->assertStatus(403);
    }
}