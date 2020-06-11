<?php

namespace App\Http\Controllers\Teacher;

use App\Exceptions\ThrowUtils;
use App\Http\Controllers\Controller;
use App\Http\DTO\Courses\CourseExDto;
use App\Http\DTO\Lessons\LessonExDto;
use App\Http\DTO\Uploads\UploadedDto;
use App\Http\Requests\Courses\CreateNewCourseRequest;
use App\Http\Requests\Courses\OrdnungMussSeinRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\Courses\UpdateCourseUnitsRequest;
use App\Http\Requests\Lessons\CreateNewLessonRequest;
use App\Http\Requests\Lessons\UpdateLessonRequest;
use App\Http\Requests\Teachers\TeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ITeachersService;
use App\Services\Abs\IUploadService;
use App\Services\Implementation\LessonsService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ThrowUtils;
    private $repo;

    public function __construct(ICourseService $courseService)
    {
        $this->repo = $courseService;
    }

    public function updateCourse(UpdateCourseRequest $request, int $id)
    {
        $this->checkPerms($request->user()->teacher, $id);

        $course = $this->repo->get($id);
        $course->update($request->getPayload());
        return new CourseExDto($course);
    }

    public function createCourse(CreateNewCourseRequest $request, ITeachersService $teachersService)
    {
        $course = $this->repo->create($request->getPayload());
        $teachersService->assign($request->user()->teacher, $course);
        return $this->created(new CourseExDto($course));
    }

    public function uploadCourseImage(User $user, Teacher $teacher, int $id, IUploadService $uploadService)
    {
        $this->checkPerms($teacher, $id);
        $course = $this->repo->get($id);
        $fileInfo = $uploadService->uploadCourseImage($user, $course, $this->openInput());
        return new UploadedDto($fileInfo);
    }

    public function updateUnitsOrder(UpdateCourseUnitsRequest $request, int $id)
    {
        $this->checkPerms($request->user()->teacher, $id);
        $course = $this->repo->get($id);
        $this->repo->updateCourseUnits($course, $request);

        return $this->done();
    }

    public function updateLessonsOrder(OrdnungMussSeinRequest $request, int $id) {
        $this->checkPerms($request->user()->teacher, $id);
        $this->repo->putLessonsOrder($id, $request->getPayload());
        return $this->done();
    }

    public function updateLesson(UpdateLessonRequest $request, LessonsService $service, int $id) {
        $lesson = $service->getWithCoure($id);
        $this->checkPerms($request->user()->teacher, $lesson->unit->course->id);
        $lesson->update($request->getPayload());
        return new LessonExDto($lesson);
    }

    public function createLesson(CreateNewLessonRequest $request, LessonsService $service) {
        $unit = $this->repo->getUnit($request->getUnitId());
        $this->checkPerms($request->user()->teacher, $unit->course_id);
        $lesson = $service->create($request->getPayload());
        return new LessonExDto($lesson);
    }

    private function checkPerms(Teacher $teacher, int $id) {
        if (!$teacher->courses()
            ->where('id', '=', $id)->exists())
            $this->throwError(401, "You can't edit this course");
    }
}
