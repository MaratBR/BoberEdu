<?php


namespace App\Http\DTO\Utils;


use App\Http\DTO\DtoBase;

class ItemsDto extends DtoBase
{
    private $items;
    private $inner;

    public function __construct($items, $inner)
    {
        $this->items = $items;
        $this->inner = $inner;
    }

    public function getItems()
    {
        $coll = collect($this->items);

        if (is_string($this->inner))
            $coll = $coll->mapInto($this->inner);
        elseif (is_callable($this->inner))
            $coll = $coll->map($this->inner);

        return $coll;
    }
}
