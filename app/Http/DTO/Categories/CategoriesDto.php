<?php


namespace App\Http\DTO\Categories;

use App\Http\DTO\Categories\CategoryDto;
use App\Http\DTO\DtoBase;

class CategoriesDto extends DtoBase
{
    private $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function getCategories()
    {
        return collect($this->categories)->mapInto(CategoryExDto::class);
    }
}
