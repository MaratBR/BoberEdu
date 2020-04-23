<?php


namespace App\Services\Abs;


use App\Category;
use App\Course;
use App\Services\Implementation\ICourseUnitsPayload;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     */
    function get(int $id, bool $extra = false): Course;

    /**
     * Returns a pagination with given size. Page number will be determined based
     * on the query parameters
     *
     * @param int $size size of the page
     * @return mixed
     */
    function paginateWithExtra($size = 15);

    /**
     * Update a course with given data
     *
     * @param Course $course
     * @param $data
     * @return bool|null
     * @throws ModelNotFoundException if course not found
     */
    function update(Course $course, $data): ?bool;

    /**
     * Deletes course.
     *
     * @param Course $course
     * @return bool|null
     */
    function delete(Course $course): ?bool;

    /**
     * Creates a new course
     *
     * @param array $data
     * @return Course
     */
    function create(array $data): Course;

    /**
     * Updates course's units
     *
     * @param Course $course
     * @param ICourseUnitsPayload $payload
     * @return ICourseUnitsUpdateResponse
     */
    function updateCourseUnits(Course $course, ICourseUnitsPayload $payload): ICourseUnitsUpdateResponse;


    function getWithUnitsAndLessonsNames(int $id);

    function getWithUnits(int $id);

    function getTrialDays(int $id): int;

    function paginateInCategory(Category $category, int $size = 15): LengthAwarePaginator;

    function getCategory(int $categoryId): Category;

    function getPopular(int $categoryId);

    function getAllCategories();

    function removeRate(Course $course, User $user);

    function setRate(Course $course, User $user, int $value);
}
