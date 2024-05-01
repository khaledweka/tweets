<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        //test api register with sanctum
        $faker = Factory::create();
        $response = $this->postJson('/api/register', [
            'name' => $faker->name,
            'email' => $faker->email,
            'username' => $faker->userName,
            'phone' => $faker->phoneNumber,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => $response['data']['email'],
        ]);
    }
}
