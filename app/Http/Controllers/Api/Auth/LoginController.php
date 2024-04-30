<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends BaseController
{
    private const INCORRECT_CREDENTIALS = ['email' => ['The provided credentials are incorrect.']];

    /**
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = $this->authenticateUser($request->email, $request->password);

        $token = $user->createToken($user->email)->plainTextToken;

        return $this->sendResponse(['token' => $token], 'User login successfully.');
    }


    /**
     * @throws ValidationException
     */
    private function authenticateUser(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages(self::INCORRECT_CREDENTIALS);
        }

        return $user;
    }
}
