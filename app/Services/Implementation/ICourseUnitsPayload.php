<?php


namespace App\Services\Implementation;


interface ICourseUnitsPayload
{
    public function getOrder(): array;

    public function getNew(): array;

    public function getUpdated(): array;

    public function getDeleted(): array;
}
