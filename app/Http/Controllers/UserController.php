<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends CRUDController
{
    public function __construct()
    {
        $this->configure(
            User::class,
            null,
            User::$updateRules,
            'user'
        );
    }
}
