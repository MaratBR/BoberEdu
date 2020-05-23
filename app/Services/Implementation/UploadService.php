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
    function generateIdForType(string $type): string
    {
        return $type . '/' . bin2hex(openssl_random_pseudo_bytes(3)) . '_' . time();
    }

    function createTmpFile() {
        $tmpFilename = tempnam(sys_get_temp_dir(), 'bts');

        if (!$tmpFilename)
            throw new HttpException(500);

        return $tmpFilename;
    }

    function putFile($file, $type = 'g', ?string $about = null, ?User $user = null) {
        // Create tmp file
        $tmpFilename = $this->createTmpFile();
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

        // Get size of the file
        $size = filesize($tmpFilename);

        // Upload file
        $ext = MimeType::search($mime);
        $ext = $ext ? $ext : 'bin';
        $id = $this->generateIdForType($type) . '.' . $ext;
        $success = Storage::disk('public')->put($id, $file);

        if (!$success)
            throw new HttpException(500);

        // Create file info model
        $fileInfo = new FileInfo([
            'sys_name' => $id,
            'user_id' => $user ? $user->id : null,
            'size' => $size,
            'mime' => $mime
        ]);
        $fileInfo->saveOrFail();
        $fileInfo->refresh();


        return $fileInfo;
    }

    function uploadAvatar(User $user, $file): string
    {
        $avatar = $this->putFile($file, 'a', null, $user);

        $user->update([
            'avatar_id' => $avatar->id
        ]);

        return $avatar->sys_name;
    }

    function getAvatarMimeType(string $id): string
    {
        return Storage::disk('public');
    }
}
