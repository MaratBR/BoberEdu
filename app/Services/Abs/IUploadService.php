<?php


namespace App\Services\Abs;


use App\User;

interface IUploadService
{
    function generateId(): string;

    function generateIdForType(string $type): string;

    function uploadAvatar(User $user, int $size, $file): string;

    function getAvatarMimeType(string $id): string;

}
