<?php

namespace App\Http\Controllers\Admin;

use App\AuditRecord;
use App\Http\Controllers\Controller;
use App\Http\DTO\Categories\CategoryDto;
use App\Http\DTO\Uploads\UploadedDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Courses\CreateCategoryRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IUploadService;
use App\Utils\Audit\Audit;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    private $repo;

    public function __construct(ICourseService $courseService)
    {
        $this->repo = $courseService;
    }

    public function create(CreateCategoryRequest $request)
    {
        $category = $this->repo->createCategory($request->getPayload());

        AuditRecord::make($request->user(), $request, Audit::CREATE)
            ->subject($category)->build();

        return $this->created(new CategoryDto($category));
    }

    public function update(UpdateCourseRequest $request, int $id)
    {
        $category = $this->repo->getCategory($id);
        $category->update($request->getPayload());

        AuditRecord::make($request->user(), $request, Audit::UPDATE)
            ->subject($category)->build();

        return $this->created(new CategoryDto($category));
    }

    public function uploadImage(AuthenticatedRequest $request, int $categoryId, IUploadService $uploadService)
    {
        $file = $this->openInput();
        $category = $this->repo->getCategory($categoryId);
        $fileInfo = $uploadService->uploadImage($request->user(), 'cat_bg', $file);
        $category->update([
            'uidata_image_id' => $fileInfo->id
        ]);

        return new UploadedDto($fileInfo);
    }
}
