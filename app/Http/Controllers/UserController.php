<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticatedRequest;
use App\Providers\Services\Abs\IUsersService;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $users;

    public function __construct(IUsersService $service)
    {
        $this->users = $service;
    }


    public function get(AuthenticatedRequest $request, int $id)
    {
        return $this->users->get($id);
    }
}
