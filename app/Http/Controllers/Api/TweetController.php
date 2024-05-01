<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{

    public function index()
    {
        //get all tweets from people I follow
        $following = auth()->user()->following()->pluck('users.id');

        //add redis cache to fastify the response
        $tweets = cache()->remember('tweets', 60, function () use ($following) {
            return Tweet::whereIn('user_id', $following)->paginate(15);
        });
        return response()->json([
            'message' => 'Data Found',
            'data' => $tweets,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $tweet = Tweet::create([
            'user_id' => auth()->id(),
            'body' => $request->body
        ]);

        $tweets = cache()->forget('tweets');
        return response()->json([
            'message' => '  Tweet Created successfully',
            'data' => $tweet
        ]);
    }


    public function show(Tweet $tweet)
    {
        return response()->json([
            'message' => '  Data Found',
            'data' => $tweet
        ]);
    }


    public function update(Request $request, Tweet $tweet)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $tweet->update([
            'body' => $request->body
        ]);

        $tweets = cache()->forget('tweets');
        return response()->json([
            'message' => 'Tweet Updated successfully',
            'data' => $tweet
        ]);
    }


    public function destroy(Tweet $tweet, Request $request)
    {
        //ensure the user is the owner of the tweet
        if ($tweet->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $tweet->delete();

        $tweets = cache()->forget('tweets');
        return response()->json([
            'message' => 'Tweet deleted successfully'
        ]);
    }

}
