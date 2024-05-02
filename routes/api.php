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
