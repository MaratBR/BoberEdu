<?php


namespace App\Providers\Services\Abs;


use App\Course;
use App\CourseAttendance;use App\User;

interface ICourseAttendanceService
{
    function attend(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance;
    function attendanceStatus(int $courseId, User $user): IAttendanceStatus;
    function attachNewPurchase(CourseAttendance $attendance, User $customer);
}
