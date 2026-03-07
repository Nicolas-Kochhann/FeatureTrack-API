<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class RegisterTest extends TestCase 
{
    public function testUserCanBeRegistered()
    {
        $url = 'api/auth/register';

        $userJson = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret123',
        ];

        $response  = $this->postJson($url, $userJson);

        $response->assertStatus(201)->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $userJson['name'],
            'email' => $userJson['email']
        ]);
    }

}