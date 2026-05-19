<?php

namespace Tests\Feature\Invite;

use App\Models\Invite;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class ListInviteTest extends TestCase
{
    public function testUserCanListReceivedInvites()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
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
}