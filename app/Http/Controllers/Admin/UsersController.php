<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\DTO\Users\AdminUserDto;
use App\Http\DTO\Users\UserDto;
use App\Services\Abs\IUsersService;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $repo;

    public function __construct(IUsersService $users)
    {
        $this->repo = $users;
    }

    public function get(int $id)
    {
        return new AdminUserDto($this->repo->get($id));
    }


}
