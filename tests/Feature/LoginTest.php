<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        //test api login with sanctum
        $user = User::factory()->create([
            'name' => Factory::create()->name,
            'email' => Factory::create()->email,
            'username' => Factory::create()->userName,
            'phone' => Factory::create()->phoneNumber,
            'password' => 'password',
        ]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }
}
