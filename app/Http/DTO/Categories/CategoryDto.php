<?php


namespace App\Http\DTO\Categories;


use App\Category;
use App\Http\DTO\DtoBase;

class CategoryDto extends DtoBase
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getId()
    {
        return $this->category->id;
    }


    public function getName()
    {
        return $this->category->name;
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
