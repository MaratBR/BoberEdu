<?php


namespace App\Http\DTO\Units;


use App\Http\DTO\DtoBase;
use App\Models\Unit;

class UnitDto extends DtoBase
{
    protected $unit;

    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
    }

    public function getName(): string
    {
        return $this->unit->name;
    }

    public function getId(): int
    {
        return $this->unit->id;
    }

    public function isPreview(): bool
    {
        return $this->unit->preview;
    }
}
