<?php


namespace App\Http\DTO;


class CategoriesDto extends DtoBase
{
    private $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function getCategories()
    {
        return collect($this->categories)->mapInto(CategoryDto::class);
    }
}
