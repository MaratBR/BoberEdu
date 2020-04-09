<?php

namespace App\Http\Controllers;

use App\Exceptions\ThrowUtils;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Users\EditUserRequest;
use App\Services\Abs\IUsersService;
use App\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    use ThrowUtils;

    private $users;

    public function __construct(IUsersService $service)
    {
        $this->users = $service;
    }

    public function get(AuthenticatedRequest $request, int $id)
    {
        $user = $this->users->get($id);
        $this->throwForbiddenIfNotAllowed('view', $user);
        return $user;
    }

    public function update(EditUserRequest $request, int $id)
    {
        $user = $this->users->get($id);
        $this->throwForbiddenIfNotAllowed('edit', $user);
        $this->users->update($user, $request->validated());
    }

    public function index(AuthenticatedRequest $request)
    {
        $this->throwForbiddenIfNotAllowed('paginate', User::class);
        return $this->users->paginate();
    }
}
