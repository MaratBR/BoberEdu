<?php

namespace App\Http\Controllers;

use App\Http\DTO\UserDto;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\AuthenticatedRequest;
use App\Services\Abs\IUsersService;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, IUsersService $usersService)
    {
        $credentials = $request->getPayload();
        $user = $usersService->create($credentials);
        return $this->created(new UserDto($user));
    }

    protected function tokenBody($token)
    {
        return [
            'token' => $token
        ];
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Crentials didn\'t match'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return $this->tokenBody($token);
    }

    public function currentUser(AuthenticatedRequest $request)
    {
        return new UserDto($request->user());
    }
}
