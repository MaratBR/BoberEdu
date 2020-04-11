<?php

namespace App\Http\Controllers;

use App\Exceptions\ThrowUtils;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Users\EditUserRequest;
use App\Services\Abs\IUsersService;
use App\User;

class UserController extends Controller
{
    use ThrowUtils;

    private $users;

    public function __construct(IUsersService $service)
    {
        $this->users = $service;
    }

    /**
     *  Returns user with roles
     *
     * @param AuthenticatedRequest $request
     * @param int $id
     * @return User
     */
    public function get(AuthenticatedRequest $request, int $id)
    {
        return $this->users->getWithRoles($id);
    }

    /**
     * Updates a user
     *
     * @param EditUserRequest $request
     * @param int $id
     */
    public function update(EditUserRequest $request, int $id)
    {
        $user = $this->users->get($id);
        $this->throwForbiddenIfNotAllowed('edit', $user);
        $this->users->update($user, $request->validated());
    }

    /**
     * Returns list (pagination) of users
     *
     * @param AuthenticatedRequest $request
     * @return mixed
     */
    public function index(AuthenticatedRequest $request)
    {
        $this->throwForbiddenIfNotAllowed('paginate', User::class);
        return $this->users->paginate();
    }
}
