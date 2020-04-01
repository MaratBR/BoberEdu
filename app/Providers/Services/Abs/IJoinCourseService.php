<?php


namespace App\Providers\Services\Abs;


use App\Course;
use App\Purchase;
use App\User;
use App\UserCourse;
use Symfony\Component\HttpKernel\Exception\HttpException;

interface IJoinCourseService
{
    /**
     * Returns record that associated given user with course with given ID
     *
     * @throws HttpException if record is not found
     * @param Course $course
     * @param User $user
     * @return UserCourse
     */
    function get(Course $course, User $user): UserCourse;

    /**
     * Creates a record for given course and user
     *
     * @param Course $course
     * @param User $user
     * @return UserCourse
     */
    function join(Course $course, User $user): UserCourse;

    /**
     * Creates new payment using IPaymentService and adds it to the record. Does that only if there's no active
     * payment atm
     *
     * @param UserCourse $record
     * @return Purchase
     */
    function purchase(UserCourse $record, User $user): Purchase;
}
