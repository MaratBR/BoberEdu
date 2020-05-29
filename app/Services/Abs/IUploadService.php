<?php


namespace App\Services\Abs;


use App\FileInfo;
use App\User;

interface IUploadService
{
    function uploadAvatar(User $uploaded, $file): FileInfo;
}
