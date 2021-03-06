<?php


namespace App\Http\DTO\Uploads;


use App\Models\FileInfo;
use App\Http\DTO\DtoBase;

class UploadedDto extends DtoBase
{
    private $fileInfo;

    public function __construct(FileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    public function getId()
    {
        return $this->fileInfo->getRootUrl();
    }
}
