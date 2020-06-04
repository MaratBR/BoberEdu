<?php


namespace App\Http\DTO\Categories;


use App\Category;
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


    public function getName()
    {
        return $this->category->name;
    }
}
