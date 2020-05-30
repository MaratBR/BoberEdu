<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\DTO\PaginationDto;
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

    public function paginate(Request $request)
    {
        $order = $request->query('order');
        if (is_array($order))
            $order = $order[0];
        if ($order !== null) {
            if (!in_array($order, ['id', 'age', 'email']))
                $order = 'name';
        }

        return new PaginationDto($this->repo->paginate(50, $order), AdminUserDto::class);
    }
}
