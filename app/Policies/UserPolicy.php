<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, User $subject)
    {
        return $user->id == $subject->id || $user->isAdmin();
    }

    public function edit(User $user, User $subject)
    {
        return $user->id == $subject->id || $user->isAdmin();
    }

    public function paginate(User $user)
    {
        return $user->isAdmin();
    }
}
