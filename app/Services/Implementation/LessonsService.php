<?php


namespace App\Services\Implementation;


use App\Exceptions\ThrowUtils;
use App\Lesson;
use App\Services\Abs\ILessonsService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use function Clue\StreamFilter\fun;

class LessonsService implements ILessonsService
{
    /**
     * @inheritDoc
     */
    function get(int $id): Lesson
    {
        $lesson = Lesson::findOrFail($id);
        return $lesson;
    }

    function getOverview(int $id): Lesson
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::query()
            ->with('unit.course.category')
            ->findOrFail($id);
        return $lesson;
    }

    /**
     * @inheritDoc
     */
    function update(Lesson $lesson, array $data): ?bool
    {
        return $lesson->update($data);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    function delete(Lesson $lesson, bool $force = false): ?bool
    {
        return $lesson->delete();
    }

    /**
     * @inheritDoc
     */
    function create(array $data): Lesson
    {
        return Lesson::create($data);
    }
}
