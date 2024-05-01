<?php

namespace App\Console\Commands;

use Faker\Factory;
use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Str;
use Random\RandomException;

class CreateTweetsCommand extends Command
{
    protected $signature = 'create:tweets {count}'; // Add argument for tweet count
    protected $description = 'Create millions of tweets using raw SQL inserts';

    /**
     * @throws RandomException
     */
    public function handle()
    {
        $count = $this->argument('count'); // Access argument
        $this->info('Creating Random Users...');
        $users = [];
        for ($i = 0; $i < $count; $i++) {
            $name = Factory::create()->name;
            $email = Factory::create()->email;
            $userName = Factory::create()->userName;
            $password = bcrypt('password');
            $users[] = "('$name', '$email','$userName', '$password', NOW(), NOW())";
        }

        $sql = "INSERT INTO users (name, email,username, password, created_at, updated_at) VALUES " . implode(',', $users);
        \Illuminate\Support\Facades\DB::statement($sql);
        $this->info('Created ' . $count . ' users');

        $this->info('Creating tweets...');

        $chunkSize = $count; // Adjust chunk size

        for ($i = 0; $i < $count; $i += $chunkSize) {
            $values = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $userId = random_int(1, $count); // Assuming 1000 users
                $text = Factory::create()->text(140);
                $values[] = "($userId, '$text', NOW(), NOW())";
            }

            $sql = "INSERT INTO tweets (user_id, body, created_at, updated_at) VALUES " . implode(',', $values);
            \Illuminate\Support\Facades\DB::statement($sql);

        }
        $this->info("Created chunk of $chunkSize tweets");


        //create random followers with no duplicates

        $this->info('Creating followers...');
        $followers = [];
        for ($i = 0; $i < $count; $i++) {
            $userId = $i;
            $followerId = $i + random_int(1, $count);
            if ($userId !== $followerId) {
                $followers[] = "($userId, $followerId, NOW(), NOW())";
            }
        }

        $sql = "INSERT INTO followers (follower_id, following_id, created_at, updated_at) VALUES " . implode(',', $followers);
        \Illuminate\Support\Facades\DB::statement($sql);

        $this->info('Created ' . count($followers) . ' followers');
    }
}
