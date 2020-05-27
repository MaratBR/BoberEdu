<?php

namespace App\Http\Requests\Lessons;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;

class UpdateLessonRequest extends AuthenticatedRequest implements IPayloadRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string',
            'content' => 'string',
            'summary' => 'string'
        ];
    }

    function getPayload(): array
    {
        return $this->validated();
    }
}
