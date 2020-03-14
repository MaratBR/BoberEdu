<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\AuthenticatedRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $credentials = $request->validated();
        $credentials['password'] = Hash::make($credentials['password']);
        $user = User::create($credentials);
        $token = auth()->login($user);

        return response()->json([
            'user' => $user,
            'login' => $this->tokenBody($token)
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Crentials didn\'t match'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function refresh(AuthenticatedRequest $request)
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();

        return ['message' => 'Successfully logged out'];
    }

    public function currentUser(AuthenticatedRequest $request)
    {
        return $request->user();
    }

    protected function respondWithToken($token)
    {
        return $this->tokenBody($token);
    }

    protected function tokenBody($token) {
        return [
            'accessToken' => $token,
            'tokenType'   => 'bearer',
            'expiresIn'   => auth()->factory()->getTTL() * 60,
            'success'     => true
        ];
    }
}
