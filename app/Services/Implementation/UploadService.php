<?php


namespace App\Services\Implementation;


use App\Models\Course;
use App\Models\FileInfo;
use App\Services\Abs\IUploadService;
use App\Models\User;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UploadService implements IUploadService
{
    function uploadAvatar(User $user, $file): FileInfo
    {
        return $this->putFile($file, 'a', null, $user);
    }

    function uploadCourseImage(User $user, Course $course, $file)
    {
        $fileInfo = $this->uploadImage($user, 'course', $file);
        $course->update([
            'image_id' => $fileInfo->id
        ]);
        return $fileInfo;
    }

    function uploadImage(User $user, string $type, $file): FileInfo
    {
        return $this->putFile($file, 'img_' . $type, null, $user);
    }

    function createTmpFile()
    {
        $tmpFilename = tempnam(sys_get_temp_dir(), 'bts');

        if (!$tmpFilename)
            throw new HttpException(500);

        return $tmpFilename;
    }

    function generateIdForType(string $type): string
    {
        return $type . '/' . bin2hex(openssl_random_pseudo_bytes(3)) . '_' . time();
    }

    private function putFile($file, $type = 'g', ?string $about = null, ?User $user = null)
    {
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

}
