<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticatedRequest;
use App\Providers\Services\Abs\IUsersService;

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

    public function update(AuthenticatedRequest $request, int $id)
    {

    }
}
