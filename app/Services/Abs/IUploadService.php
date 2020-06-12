<?php


namespace App\Services\Abs;


use App\Models\Course;
use App\Models\FileInfo;
use App\Models\User;

interface IUploadService
{
    function uploadAvatar(User $user, $file): FileInfo;

    function uploadCourseImage(User $user, Course $course, $file);

    function uploadImage(User $user, string $type, $file): FileInfo;
}
