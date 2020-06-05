<?php


namespace App\Services\Abs;


use App\FileInfo;
use App\User;

interface IUploadService
{
    function uploadAvatar(User $user, $file): FileInfo;

    function uploadImage(User $user, string $type, $file): FileInfo;
}
