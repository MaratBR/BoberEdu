<?php


namespace App\Providers\Services\Abs;


use App\Course;
use App\Providers\Services\ICourseUnitsPayload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;

interface ICourseService
{
    /**
     * Queries a course with given id, calls Gate authorization.
     *
     * @param int $id
     * @param bool $extra
     * @return Course
     * @throws ModelNotFoundException if course not found
     * @throws ForbiddenApiException if user is not allowed to view course
     */
    function get(int $id, bool $extra = false): Course;

    /**
     * Returns a pagination with given size. Page number will be determined based
     * on the query parameters
     *
     * @param int $size size of the page
     * @return mixed
     */
    function paginate($size = 15);

    /**
     * Update a course with given data
     *
     * @param Course $course
     * @param $data
     * @return bool|null
     * @throws ModelNotFoundException if course not found
     * @throws ForbiddenApiException if user is not allowed to update course
     */
    function update(Course $course, $data): ?bool;

    /**
     * Deletes course.
     *
     * @param Course $course
     * @param bool $force
     * @return bool|null
     */
    function delete(Course $course, bool $force = false): ?bool;

    /**
     * Creates a new course
     *
     * @param array $data
     * @return Course
     * @throws ForbiddenApiException if user is not allowed to create course
     */
    function create(array $data): Course;

    /**
     * Updates course's units
     *
     * @param Course $course
     * @param ICourseUnitsPayload $payload
     * @return ICourseUnitsUpdateResponse
     * @throws ForbiddenApiException if user is not allowed to update course
     */
    function updateCourseUnits(Course $course, ICourseUnitsPayload $payload): ICourseUnitsUpdateResponse;
}
