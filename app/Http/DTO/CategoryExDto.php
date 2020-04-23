<?php


namespace App\Http\DTO;


use App\Category;

class CategoryExDto extends DtoBase
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    function getCategory(): array
    {
        return [
            'id' => $this->category->id,
            'name' => $this->category->name,
            'about' => $this->category->about
        ];
    }

    function getPopular()
    {
        return [
            // TODO
        ];
    }
}
