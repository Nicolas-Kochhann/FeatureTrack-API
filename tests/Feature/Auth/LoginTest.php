<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Hash;
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

}