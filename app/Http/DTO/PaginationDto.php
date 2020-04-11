<?php


namespace App\Http\DTO;


use Illuminate\Pagination\AbstractPaginator;

class PaginationDto extends DtoBase
{
    protected $paginator;
    private $innerDto;

    public function __construct(AbstractPaginator $paginator, string $innerDto = null)
    {
        $this->innerDto = $innerDto;
        $this->paginator = $paginator;
    }

    public function getData()
    {
        $data = $this->paginator->getCollection();

        if ($this->innerDto != null)
        {
            $data = $data->mapInto($this->innerDto);
        }

        return $data;
    }
}
