<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

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
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout()
    {
        echo auth()->id();
        auth()->logout();

        return ['message' => 'Successfully logged out'];
    }

    public function currentUser(Request $request) {

        $user = $request->user();
        return $user;
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
