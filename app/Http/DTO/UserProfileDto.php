<?php


namespace App\Http\DTO;


use App\Enrollment;
use App\User;

class UserProfileDto extends DtoBase
{
    private $user;
    private $enrollments;

    public function __construct(User $user, $enrollments)
    {
        $this->user = $user;
        $this->enrollments = $enrollments;
    }

    function getCourses() {
        return collect($this->enrollments)->map(function (Enrollment $enrollment) {
            return [
                'since' => $enrollment->enrolled_at,
                'activated' => $enrollment->activated,
                'trialEnd' => $enrollment->trial_ends_at,
                'course' => [
                    'name' => $enrollment->course->name,
                    'id' => $enrollment->course->id
                ]
            ];
        });
    }

    function getUser() {
        return (new UserDto($this->user))->toArray();
    }
}
