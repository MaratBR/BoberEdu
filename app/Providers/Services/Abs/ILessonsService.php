<?php


namespace App\Providers\Services\Abs;


use App\Lesson;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;

interface ILessonsService
{
    /**
     * Queries a lesson with given id. Performs Gate authorization check
     *
     * @param int $id
     * @return Lesson
     * @throws ForbiddenApiException if user is not allowed to view lesson
     */
    function get(int $id): Lesson;

    /**
     *
     * @param Lesson $lesson
     * @param array $data
     * @return bool|null
     * @throws ForbiddenApiException is user is not allowed to update this lesson
     */
    function update(Lesson $lesson, array $data): ?bool;

    /**
     *
     * @param Lesson $lesson
     * @param bool $force
     * @return bool|null
     * @throws ForbiddenApiException is user is not allowed to delete this lesson
     */
    function delete(Lesson $lesson, bool $force = false): ?bool;

    /**
     * Creates a new lesson from given data.
     *
     * @param array $data
     * @return Lesson
     * @throws ForbiddenApiException is user is not allowed to create a lesson
     */
    function create(array $data): Lesson;
}
