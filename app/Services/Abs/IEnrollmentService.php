<?php


namespace App\Services\Abs;


use App\Course;
use App\Enrollment;
use App\User;

interface IEnrollmentService
{
    function getUserEnrolls(User $user);

    function getEnrollmentRecord(int $courseId, User $user): Enrollment;

    function getEnrollmentRecordOrNull(int $courseId, User $user): ?Enrollment;

    function hasEnrollment(int $courseId, User $user): bool;

    function enroll(int $courseId, User $user): Enrollment;

    function enrollCourse(Course $course, User $user): Enrollment;

    function disenrollCourse(Course $course, User $user): bool;

    function disenroll(int $courseId, User $user): bool;
}
