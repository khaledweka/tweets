<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Random\RandomException;

class RegisterController extends BaseController
{
    /**
     * Invokes the register function.
     *
     * @param RegisterRequest $request The register request.
     *
     * @return JsonResponse The JSON response.
     * @throws RandomException
     */
    public function register(RegisterRequest $request): JsonResponse
    {

        $user = $this->createUser($request);

        return $this->sendResponse($user, 'User register successfully.');
    }

    /**
     * Create a new user
     *
     * @param Request $request The request object containing user data
     * @return User The newly created user
     * @throws RandomException
     */
    private function createUser(Request $request): User
    {
        $avatar = '';
        $username = $this->generateUserName();
        if ($request->has('image')) {
            $avatar = $this->saveAvatar($request->image, $username);
        }

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $username,
            'phone' => $request->phone ?? '',
            'image' => $avatar,
        ]);
    }

    /**
     * Generate a unique username
     *
     * @return string The generated username
     * @throws RandomException
     */
    private function generateUserName(): string
    {
        $username = 'user' . random_int(1000, 9999);
        if (User::where('username', $username)->exists()) {
            return $this->generateUserName();
        }
        return $username;

    }

    /**
     * Save the user avatar
     *
     * @param string $image The image data
     * @param string $username The username
     * @return string The image path
     */
    private function saveAvatar(string $image, string $username): string
    {
        //user laravel upload images to storage
        $path = 'avatars/' . $username . '.png';
        Storage::disk('public')->put($path, base64_decode($image));
        return $path;
    }

}

