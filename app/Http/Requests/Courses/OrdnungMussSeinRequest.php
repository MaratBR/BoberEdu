<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\IPayloadRequest;
use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\Types\Integer;
use function Clue\StreamFilter\fun;

class OrdnungMussSeinRequest extends AdminRequest implements IPayloadRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'units.*.id' => 'numeric|required',
            'units.*.order' => 'array|required|min:1',
            'units.*.order.*' => 'numeric|required'
        ];
    }


    function getPayload(): array
    {
        return $this->validated();
    }
}
