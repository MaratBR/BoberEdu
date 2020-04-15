<?php

namespace App\Policies;

use App\Course;
use App\CoursePurchase;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return true;
    }

    public function paginate(?User $user)
    {
        return true;
    }

    public function view(?User $user, Course $course)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, $course)
    {
        return $user->isAdmin() or true;
    }

    public function delete(User $user, Course $course)
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Course $course)
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Course $course)
    {
        return $user->isAdmin();
    }

    public function buy(User $user, Course $course)
    {
        return $course->canBePurchased();
    }
}
