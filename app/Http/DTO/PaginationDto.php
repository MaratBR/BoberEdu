<?php


namespace App\Http\DTO;


use Illuminate\Pagination\LengthAwarePaginator;

class PaginationDto extends DtoBase
{
    protected $paginator;
    private $innerDto;

    public function __construct(LengthAwarePaginator $paginator, string $innerDto = null)
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

    public function getMeta()
    {
        return [
            'total' => $this->paginator->total(),
            'perPage' => $this->paginator->perPage(),
            'page' => $this->paginator->currentPage(),
            'lastPage' => $this->paginator->lastPage(),
            'next' => $this->paginator->nextPageUrl(),
            'prev' => $this->paginator->previousPageUrl()
        ];
    }
}
