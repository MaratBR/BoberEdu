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
     * @throws ForbiddenApiException if user is not allowed to view course
     * @throws ModelNotFoundException if course not found
     * @param int $id
     * @param bool $extra
     * @return Course
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
     * @throws ForbiddenApiException if user is not allowed to update course
     * @throws ModelNotFoundException if course not found
     * @param Course $course
     * @param $data
     * @return bool|null
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
     * @throws ForbiddenApiException if user is not allowed to create course
     * @param array $data
     * @return Course
     */
    function create(array $data): Course;

    /**
     * Updates course's units
     *
     * @throws ForbiddenApiException if user is not allowed to update course
     * @param Course $course
     * @param ICourseUnitsPayload $payload
     * @return ICourseUnitsUpdateResponse
     */
    function updateCourseUnits(Course $course, ICourseUnitsPayload $payload): ICourseUnitsUpdateResponse;
}
