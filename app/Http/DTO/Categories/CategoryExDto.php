<?php


namespace App\Http\DTO\Categories;


use App\Category;
use App\Http\DTO\Categories\CategoryDto;
use App\Http\DTO\Courses\CourseDto;

class CategoryExDto extends CategoryDto
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    public function getAbout()
    {
        return $this->category->about;
    }

    public function getColor()
    {
        return $this->category->uidata_color;
    }

    public function getBgImage() {
        return $this->category->uidata_image_id === null ? null : $this->category->image->sys_name;
    }
}
