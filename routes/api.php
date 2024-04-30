<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);
Route::post('/logout', LogoutController::class);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', 'App\Http\Controllers\UserController@index');
    Route::get('/user/{user}', 'App\Http\Controllers\UserController@show');
    Route::put('/user/{user}', 'App\Http\Controllers\UserController@update');
    Route::delete('/user/{user}', 'App\Http\Controllers\UserController@destroy');
//tweets
    Route::get('/tweets', 'App\Http\Controllers\TweetController@index');
    Route::post('/tweets', 'App\Http\Controllers\TweetController@store');
    Route::get('/tweets/{tweet}', 'App\Http\Controllers\TweetController@show');
    Route::put('/tweets/{tweet}', 'App\Http\Controllers\TweetController@update');
    Route::delete('/tweets/{tweet}', 'App\Http\Controllers\TweetController@destroy');


});
