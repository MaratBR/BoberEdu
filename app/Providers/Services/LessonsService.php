<?php


namespace App\Providers\Services;


use App\Lesson;
use App\Providers\Services\Abs\ILessonsService;
use App\User;
use Illuminate\Support\Facades\Gate;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;
use Lanin\Laravel\ApiExceptions\UnauthorizedApiException;

class LessonsService implements ILessonsService
{
    use Utils;
    const UPDATE_NOT_ALLOWED = "You are not allowed to update this lesson";
    const VIEW_NOT_ALLOWED = "You are not allowed to view this lesson";
    const DELETE_NOT_ALLOWED = "You are not allowed to delete this lesson";
    const CREATE_NOT_ALLOWED = "You are not allowed to create a lesson";

    /**
     * @inheritDoc
     */
    function get(int $id): Lesson
    {
        $lesson = Lesson::findOrFail($id);
        $this->throwForbiddenIfNotAllowed(['view', 'viewAny'], $lesson, self::VIEW_NOT_ALLOWED);
        return $lesson;
    }

    /**
     * @inheritDoc
     */
    function update(Lesson $lesson, array $data): ?bool
    {
        $this->throwForbiddenIfNotAllowed('update', $lesson, self::UPDATE_NOT_ALLOWED);
        return $lesson->update($data);
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    function delete(Lesson $lesson, bool $force = false): ?bool
    {
        $this->throwForbiddenIfNotAllowed('delete', $lesson, self::DELETE_NOT_ALLOWED);
        return $lesson->delete();
    }

    /**
     * @inheritDoc
     */
    function create(array $data): Lesson
    {
        $this->throwForbiddenIfNotAllowed('create', Lesson::class, self::CREATE_NOT_ALLOWED);
        return Lesson::create($data);
    }
}
