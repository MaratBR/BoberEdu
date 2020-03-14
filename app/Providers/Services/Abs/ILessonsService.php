<?php


namespace App\Providers\Services\Abs;


use App\Lesson;
use App\User;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;

interface ILessonsService
{
    /**
     * Queries a lesson with given id. Performs Gate authorization check
     *
     * @throws ForbiddenApiException if user is not allowed to view lesson
     * @param int $id
     * @return Lesson
     */
    function get(int $id): Lesson;

    /**
     *
     * @throws ForbiddenApiException is user is not allowed to update this lesson
     * @param Lesson $lesson
     * @param array $data
     * @return bool|null
     */
    function update(Lesson $lesson, array $data): ?bool;

    /**
     *
     * @throws ForbiddenApiException is user is not allowed to delete this lesson
     * @param Lesson $lesson
     * @param bool $force
     * @return bool|null
     */
    function delete(Lesson $lesson, bool $force = false): ?bool;

    /**
     * Creates a new lesson from given data.
     *
     * @throws ForbiddenApiException is user is not allowed to create a lesson
     * @param array $data
     * @return Lesson
     */
    function create(array $data): Lesson;
}
