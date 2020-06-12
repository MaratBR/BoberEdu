<?php

namespace App\Http\Requests\Lessons;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;

class CreateNewLessonRequest extends AuthenticatedRequest implements IPayloadRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'unitId' => 'numeric|required',
            'title' => 'string|required',
            'content' => 'string',
            'summary' => 'string|required'
        ];
    }

    function getPayload(): array
    {
        return Convert::toSnakeCase($this->validated());
    }

    public function getUnitId()
    {
        return $this->validated()['unitId'];
    }
}
