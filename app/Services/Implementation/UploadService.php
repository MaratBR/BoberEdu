<?php


namespace App\Services\Implementation;


use App\Services\Abs\IUploadService;
use App\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UploadService implements IUploadService
{
    function generateId(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(5)) . '_' . time();
    }

    function uploadAvatar(User $user, $file): string
    {
        $id = $this->generateId();

        $success = Storage::disk('public')->put('avatars/' . $id, $file);

        if (!$success)
            throw new HttpException(500);

        $user->update([
            'avatar' => $id
        ]);

        return $id;
    }
}
