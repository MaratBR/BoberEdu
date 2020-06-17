<?php


namespace App\Http\DTO\Categories;


use App\Models\Category;
use App\Http\DTO\DtoBase;

class CategoryDto extends DtoBase
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getId()
    {
        return $this->category->id;
    }


    public function getCoursesCount()
    {
        return $this->category->courses_count;
    }


    public function getStudentsCount()
    {
        return $this->category->students_count;
    }

    public function getName()
    {
        return $this->category->name;
    }
}
