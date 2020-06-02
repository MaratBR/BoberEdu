<?php


namespace App\Http\DTO\Courses;


use App\Course;
use App\Http\DTO\DtoBase;
use App\Http\DTO\Teachers\TeacherDto;

class CourseDto extends DtoBase
{
    protected $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function getPrice()
    {
        return $this->course->price;
    }

    public function getId(): int
    {
        return $this->course->id;
    }

    public function getRating(): ?float
    {
        return $this->course->rating()->avg('value');
    }

    public function getRatingVotes(): ?float
    {
        return $this->course->rating()->count();
    }


    public function getName(): string
    {
        return $this->course->name;
    }

    public function getAvailable(): bool
    {
        return $this->course->available;
    }

    public function getAbout(): string
    {
        return $this->course->about;
    }

    public function getTrialDays(): int
    {
        return $this->course->trial_length;
    }

    public function getRequirements(): ?array
    {
        return [
            'signUp' => [
                'beg' => $this->course->sign_up_beg,
                'end' => $this->course->sign_up_end,
                'purchasable' => $this->course->canBePurchased()
            ]
        ];
    }

    public function getTeachers()
    {
        return collect($this->course->teachers)->mapInto(TeacherDto::class);
    }

    public function getSummary()
    {
        return $this->course->summary;
    }
};
