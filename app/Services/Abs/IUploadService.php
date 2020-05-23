<?php


namespace App\Services\Abs;


use App\User;

interface IUploadService
{

    function uploadAvatar(User $user, $file): string;
}
