<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\TweetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

//create random users
Route::get('/create-users/{count}', function ($count) {
    $users = [];
    for ($i = 0; $i < $count; $i++) {
        $name = \Faker\Factory::create()->name;
        $email = \Faker\Factory::create()->email;
        $userName = \Faker\Factory::create()->userName;
        $password = bcrypt('password');
        $users[] = ['name' => $name, 'email' => $email, 'username' => $userName, 'password' => $password];
    }
    \App\Models\User::insert($users);
    return response()->json(['message' => 'users created']);
});
//create random tweets
Route::get('/create-tweets/{count}', function ($count) {
    $users = \App\Models\User::all();
    $tweets = [];
    for ($i = 0; $i < $count; $i++) {
        $userId = $users->random()->id;
        $text = \Faker\Factory::create()->text(140);
        $tweets[] = ['user_id' => $userId, 'body' => $text];
    }
    \App\Models\Tweet::insert($tweets);
    return response()->json(['message' => 'tweets created']);
});
//create random followers with no duplicates
Route::get('/create-followers', function () {
    $users = \App\Models\User::all();
    $users->each(function ($user) use ($users) {
        $user->following()->sync($users->random(10));
    });
    return response()->json(['message' => 'followers created']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [LogoutController::class, 'logout']);

//tweets
    Route::get('tweets', [TweetController::class, 'index']);
    Route::get('tweets/{tweet}', [TweetController::class, 'show']);
    Route::post('tweets', [TweetController::class, 'store']);
    Route::post('tweets/{tweet}', [TweetController::class, 'update']);
    Route::delete('tweets/{tweet}', [TweetController::class, 'destroy']);


    //follow
    Route::post('/follow/{user}', [FollowController::class, 'follow']);
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow']);


});
