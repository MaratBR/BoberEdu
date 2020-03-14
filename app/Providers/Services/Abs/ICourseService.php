<?php


namespace App\Providers\Services\Abs;


use App\Course;
use App\Providers\Services\ICourseUnitsPayload;

interface ICourseService
{
    function get(int $id, bool $extra = false): Course;
    function paginate($page = 15);
    function update(Course $course, $data): ?bool;
    function delete(Course $course): ?bool;
    function create(array $data): Course;
    function updateCourseUnits(Course $course, ICourseUnitsPayload $payload): ICourseUnitsUpdateResponse;
}
