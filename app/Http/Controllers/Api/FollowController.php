<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user, Request $request): \Illuminate\Http\JsonResponse
    {
        //ensure that the authenticated user is not following the user
        if ($request->user()->isFollowing($user->id)) {
            return response()->json(['message' => 'You are already following this user'], 200);
        }
        $user->followers()->attach(auth()->user()->id);


        $tweets = cache()->forget('tweets');
        return response()->json(['message' => 'You are now following this user'], 200);
    }

    public function unfollow(User $user): \Illuminate\Http\JsonResponse
    {
        $user->followers()->detach(auth()->user()->id);


        $tweets = cache()->forget('tweets');
        return response()->json(['message' => 'You are no longer following this user'], 200);
    }
}
