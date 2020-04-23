<?php

namespace App\Http\Controllers;

use App\Exceptions\ThrowUtils;
use App\Http\DTO\PaginationDto;
use App\Http\DTO\UserDto;
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
     * @return UserDto
     */
    public function get(AuthenticatedRequest $request, int $id)
    {
        $user = $this->users->getWithRoles($id);

        return new UserDto($user);
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
        $paginator = $this->users->paginate();
        return new PaginationDto($paginator, UserDto::class);
    }
}
