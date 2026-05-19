<?php

namespace Tests\Feature\Invite;

use App\Models\Invite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class RespondInviteTest extends TestCase
{
    public function testReceiverCanRespondToInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create();
        $receiver = User::factory()->create();
        $invite = Invite::factory()->create([
            'sender_id' => $owner->id,
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'developer'
        ]);
        $responseData = [
            'status' => 'accepted'
        ];
        $token = JWTAuth::fromUser($receiver);

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

    public function testNonReceiverCannotRespondToInvite()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create();
        $receiver = User::factory()->create();
        $invite = Invite::factory()->create([
            'sender_id' => $owner->id,
            'receiver_id' => $receiver->id,
            'project_id' => $project->id,
            'role' => 'developer'
        ]);
        $nonReceiver = User::factory()->create();
        $responseData = [
            'status' => 'accepted'
        ];
        $token = JWTAuth::fromUser($nonReceiver);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson("/api/invites/{$invite->id}/respond", $responseData);

        $response->assertStatus(403);
    }
}