<?php


namespace App\Services\Implementation;


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
        if (!array_key_exists('order_num', $data)) {
            $data['order_num'] = Lesson::query()->where('unit_id', '=', $data['unit_id'])->max('order_num') + 1;
        }
        return Lesson::create($data);
    }
}
