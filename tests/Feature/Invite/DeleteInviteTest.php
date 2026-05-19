<?php

namespace Tests\Feature\Invite;

use App\Models\Invite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class DeleteInviteTest extends TestCase
{
    public function testSenderCanDeleteInvite()
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
        $token = JWTAuth::fromUser($owner);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/invites/{$invite->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('invites', ['id' => $invite->id]);
    }

    public function testNonSenderCannotDeleteInvite()
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
        $token = JWTAuth::fromUser($nonSender);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/invites/{$invite->id}");

        $response->assertStatus(403);
    }
}