<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {

        $credentials = $request->validate([
            'name' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8'
        ]);
        $credentials['password'] = Hash::make($credentials['password']);



        $user = User::create($credentials);

        $token = auth()->login($user);

        return response()->json([
            'user' => $user,
            'login' => $this->tokenBody($token)
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|max:255'
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'success' => false], 401);
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

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function currentUser(Request $request) {

        $user = $request->user();
        return response()->json($user);
    }

    protected function respondWithToken($token)
    {
        return response()->json($this->tokenBody($token));
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
