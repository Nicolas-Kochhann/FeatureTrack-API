<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Hash;
use JWTAuth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testUserCanLogIn()
    {
        $url = 'api/auth/login';

        $user = User::factory()->create([
            'password' => Hash::make('secret123')
        ]);

        $response  = $this->postJson($url, [
            'email' => $user->email,
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    public function testUserCantLogInWithWrongPassword()
    {
        $url = 'api/auth/login';

        $user = User::factory()->create([
            'password' => Hash::make('secret123')
        ]);

        $response = $this->postJson($url, [
            'email' =>  $user->email,
            'password' => 'wrongSecret'
        ]);

        $response->assertStatus(401)->assertJsonStructure([
            'error'
        ]);
    }

    public function testUserCantLogInWithoutBeRegistered()
    {
        $url = 'api/auth/login';

        $user = User::factory()->make([
            'password' => Hash::make('secret123')
        ]); // Creates User model without save on db.

        $response = $this->postJson($url, [
            'email' => $user->email,
            'password' => 'secret123'
        ]);

        $response->assertStatus(401)->assertJsonStructure([
            'error'
        ]);
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out'
            ]);
    }

    public function testUserCanRefreshToken()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    public function testUserCanGetMe()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ]);
    }
}
