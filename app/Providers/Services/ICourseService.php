<?php


namespace App\Providers\Services;


use App\Course;
use App\CourseAttendance;
use App\User;

const ATTENDANCE_STATUS_YES = 'yes'; // user has access to course
const ATTENDANCE_STATUS_NO = 'no'; // user does not have access
const ATTENDANCE_STATUS_CANCELLED = 'cancelled'; // user tried to pay for course but payment get cancelled
const ATTENDANCE_STATUS_AWAITING_PAYMENT = 'awaiting_payment'; // no payment created yet
const ATTENDANCE_STATUS_PREVIEW = 'preview'; // user has access to course due to preview period
const ATTENDANCE_STATUS_PREVIEW_EXPIRED = 'preview_expired'; // self-explanatory

interface IAttendanceStatus
{
    function hasAccess(): bool;
    function isPreview(): bool;
    function isExpired(): bool;
    function asString(): string;
    function getPurchaseInnerStatus(): ?string;
    function hasPayment(): bool;
}

interface ICourseService
{
    function get(int $id, bool $extra = false): Course;
    function paginate($page = 15);
    function update(Course $course, $data): ?bool;
    function delete(Course $course): ?bool;
    function create(array $data): Course;
    function updateCourseUnits(Course $course, ICourseUnitsPayload $payload): ICourseUnitsUpdateResponse;
    function attend(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance;
    function attendanceStatus(Course $course, User $user): IAttendanceStatus;
}
