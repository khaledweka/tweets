<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TweetsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function a_user_can_get_all_tweets()
    {
        //act as logged-in user and see tweets
        $this->actingAs($this->user);

        $response = $this->getJson('/api/tweets');

        //assert that the response is successful and has 3 tweets
        $response->assertStatus(200)->assertJsonCount(3, 'data');

        //assert that the response has the correct structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'body',
                    'created_at',
                    'updated_at',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
        

    }
}
