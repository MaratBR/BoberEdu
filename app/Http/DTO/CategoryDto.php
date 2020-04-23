<?php


namespace App\Http\DTO;


use App\Category;

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
}
