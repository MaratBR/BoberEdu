<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserTrait
{
    // TODO Implement
    use CRUDTrait;

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
