<?php


namespace App\Http\DTO;


use App\Category;

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
