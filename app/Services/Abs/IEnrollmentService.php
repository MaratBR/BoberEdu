<?php


namespace App\Services\Abs;


use App\Course;
use App\Enrollment;
use App\User;
use Carbon\Carbon;

interface IEnrollmentService
{
    /**
     * Returns list of enrollment records for a user
     *
     * @param User $user
     * @return mixed
     */
    function getUserEnrolls(User $user);

    /**
     * Returns list of enrollment records with courses for a user
     *
     * @param User $user
     * @return mixed
     */
    function getUserEnrollsWithCourses(User $user);

    /**
     * Returns enrollment record.
     *
     * @param int $courseId
     * @param User $user
     * @return Enrollment
     */
    function getEnrollmentRecord(int $courseId, User $user): Enrollment;

    /**
     * Like getEnrollmentRecord but returns null if not found
     *
     * @param int $courseId
     * @param User $user
     * @return Enrollment|null
     */
    function getEnrollmentRecordOrNull(int $courseId, User $user): ?Enrollment;

    function getEnrollmentRecordWithPaymentOrNull(int $courseId, User $user): ?Enrollment;

    /**
     * Returns true if user has enrollment record
     *
     * @param int $courseId
     * @param User $user
     * @return bool
     */
    function hasEnrollment(int $courseId, User $user): bool;

    function hasAccess(int $courseId, User $user): bool;

    function isEnrollmentTrashed(int $courseId, User $user): bool;

    function createEnrollRecord(int $courseId, User $user): Enrollment;

    function activate(Enrollment $record): void;

    function deactivate(Enrollment $record): void;

    function delete(int $courseId, User $user);

    function restore(int $courseId, User $user);
}
