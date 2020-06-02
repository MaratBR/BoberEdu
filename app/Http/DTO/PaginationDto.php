<?php


namespace App\Http\DTO;


use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaginationDto extends DtoBase
{
    protected $paginator;
    private $innerDto;

    /**
     * PaginationDto constructor.
     * @param LengthAwarePaginator|array|Collection $paginator
     * @param string|null $innerDto
     */
    public function __construct($paginator, string $innerDto = null)
    {
        $this->innerDto = $innerDto;
        $this->paginator = $paginator;
    }

    public function getData()
    {
        if ($this->paginator instanceof LengthAwarePaginator) {
            $data = $this->paginator->getCollection();
        } elseif ($this->paginator instanceof Collection) {
            $data = $this->paginator;
        } else {
            $data = collect($this->paginator);
        }

        if ($this->innerDto != null)
        {
            $data = $data->mapInto($this->innerDto);
        }

        return $data;
    }

    public function getMeta()
    {
        if ($this->paginator instanceof LengthAwarePaginator) {
            return [
                'total' => $this->paginator->total(),
                'perPage' => $this->paginator->perPage(),
                'page' => $this->paginator->currentPage(),
                'lastPage' => $this->paginator->lastPage(),
                'next' => $this->paginator->nextPageUrl(),
                'prev' => $this->paginator->previousPageUrl()
            ];
        } elseif ($this->paginator instanceof Collection) {
            return [
                'total' => $this->paginator->count(),
                'perPage' => $this->paginator->count(),
                'page' => 1,
                'lastPage' => 1,
                'next' => null,
                'prev' => null
            ];
        } else {
            return [
                'total' => count($this->paginator),
                'perPage' => count($this->paginator),
                'page' => 1,
                'lastPage' => 1,
                'next' => null,
                'prev' => null
            ];
        }


    }
}
