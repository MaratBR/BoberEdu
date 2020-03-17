<?php


namespace App\Providers\Services\Abs;


use App\Course;
use App\CourseAttendance;
use App\Purchase;
use App\User;

interface ICourseAttendanceService
{
    function get(int $courseId, User $user): CourseAttendance;
    function exists(int $courseId, User $user): bool;
    function attend(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance;
    function getAttendanceStatusFrom(?CourseAttendance $attendance): IAttendanceStatus;
    function attendanceStatus(int $courseId, User $user);
    function attachNewPurchase(CourseAttendance $attendance, User $customer);
    function makePurchase(CourseAttendance $attendance, User $customer): Purchase;
}
