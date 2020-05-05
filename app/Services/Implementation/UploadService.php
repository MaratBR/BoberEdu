<?php


namespace App\Services\Implementation;


use App\FileInfo;
use App\Services\Abs\IUploadService;
use App\User;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use function GuzzleHttp\Psr7\copy_to_stream;

class UploadService implements IUploadService
{
    function generateId(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(5)) . '_' . time();
    }

    function generateIdForType(string $type): string
    {
        return $type . '_' . bin2hex(openssl_random_pseudo_bytes(5)) . '_' . time();
    }

    function getMimeType($file): string {
        $id = $this->generateIdForType('ava');

        $tmpFilename = tempnam(sys_get_temp_dir(), 'bts');

        if (!$tmpFilename)
            throw new HttpException(500);

        $tmp = fopen($tmpFilename, 'r+');

        if (is_resource($file))
            stream_copy_to_stream($file, $tmp);
        elseif (is_string($file))
            fwrite($tmp, $file);
        else
            throw new HttpException(500, "Invalid argument \$file");

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmpFilename);
        finfo_close($finfo);

        return $mime;
    }

    function uploadAvatar(User $user, int $size, $file): string
    {
        $mime = $this->getMimeType($file);
        $id = $this->generateIdForType('ava');
        $ext = MimeType::search($mime);
        $id .= '.' . $ext;
        FileInfo::create([
            'sys_name' => $id,
            'user_id' => $user->id,
            'size' => $size,
            'mime' => $mime
        ]);

        $success = Storage::disk('public')->put('avatars/' . $id, $file);

        if (!$success)
            throw new HttpException(500);

        $user->update([
            'avatar' => $id
        ]);

        return $id;
    }

    function getAvatarMimeType(string $id): string
    {
        return Storage::disk('public');
    }
}
