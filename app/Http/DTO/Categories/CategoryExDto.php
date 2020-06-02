<?php


namespace App\Http\DTO\Categories;


use App\Category;
use App\Http\DTO\Categories\CategoryDto;
use App\Http\DTO\Courses\CourseDto;

class CategoryExDto extends CategoryDto
{
    private $popular;

    public function __construct(Category $category, $popular)
    {
        parent::__construct($category);
        $this->popular = $popular;
    }

    function getPopular()
    {
        return collect($this->popular)->mapInto(CourseDto::class);
    }
}
