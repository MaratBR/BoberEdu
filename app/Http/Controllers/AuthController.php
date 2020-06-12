<?php

namespace App\Http\Controllers;

use App\Http\DTO\Users\SelfUserDto;
use App\Http\DTO\Users\UserDto;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\AuthenticatedRequest;
use App\Services\Abs\IUsersService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, IUsersService $usersService)
    {
        $credentials = $request->getPayload();
        $user = $usersService->create($credentials);
        return $this->created(new UserDto($user));
    }

    public function login(LoginRequest $request)
    {
        $success = Auth::attempt([
            'name' => $request->getUsername(),
            'password' => $request->getUserPassword()
        ], $request->getRememberMe());

        if (!$success) {
            $this->throwError(401, "Invalid credentials");
        }
    }

    public function currentUser(AuthenticatedRequest $request)
    {
        return new SelfUserDto($request->user());
    }

    public function logout(AuthenticatedRequest $request)
    {
        Auth::logout();
    }
}
