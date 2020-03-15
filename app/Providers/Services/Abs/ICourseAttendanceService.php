<?php


namespace App\Providers\Services\Abs;


use App\Course;
use App\CourseAttendance;
use App\Purchase;
use App\User;

interface ICourseAttendanceService
{
    function get(int $id, User $user): CourseAttendance;
    function purchase(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance;
    function attendanceStatus(int $courseId, User $user): IAttendanceStatus;
    function getAttendanceStatusFrom(?CourseAttendance $attendance): IAttendanceStatus;
    function attachNewPurchase(CourseAttendance $attendance, User $customer);
    function submitPurchase(CourseAttendance $attendance, User $customer): Purchase;
}
