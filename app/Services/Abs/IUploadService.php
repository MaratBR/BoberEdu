<?php


namespace App\Services\Abs;


use App\User;

interface IUploadService
{
    function generateId(): string;

    function uploadAvatar(User $user, $file): string;
}
