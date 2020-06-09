<?php


namespace App\Http\DTO\Teachers;


use App\Http\DTO\Courses\CoursePageItemDto;

class TeacherProfileDto extends TeacherExDto
{
    public function getCourses()
    {
        return $this->teacher->courses()
            ->orderByRaw(
                '(
                SELECT COUNT(*)
                FROM enrollments
                WHERE enrollments.activated = 1 AND enrollments.deleted_at IS NOT NULL AND enrollments.course_id = courses.id)'
            )
            ->take(5)
            ->get()
            ->mapInto(CoursePageItemDto::class);
    }
}
