<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
    /**
     * Invokes the register function.
     *
     * @param RegisterRequest $request The register request.
     *
     * @return JsonResponse The JSON response.
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {

        $user = $this->createUser($request);

        return $this->sendResponse($user, 'User register successfully.');
    }

    /**
     * Create a new user
     *
     * @param Request $request The request object containing user data
     * @return User The newly created user
     */
    private function createUser(Request $request): User
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

}

