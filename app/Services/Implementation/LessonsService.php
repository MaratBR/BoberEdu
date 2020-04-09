<?php


namespace App\Services\Implementation;


use App\Exceptions\ThrowUtils;
use App\Lesson;
use App\Services\Abs\ILessonsService;
use Exception;

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
