<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;

class UpdateCourseRequest extends AuthenticatedRequest implements IPayloadRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'min:1|max:255',
            'price' => 'numeric|min:0|max:9999999999999999999.99',
            'about' => 'string',
            'signUpBeg' => 'nullable|date_format:Y-m-d',
            'signUpEnd' => 'nullable|date_format:Y-m-d',
            'trialLength' => 'numeric|min:0|max:365',
            'available' => 'boolean',
            'summary' => 'string|nullable'
        ];
    }

    function getPayload(): array
    {
        return Convert::toSnakeCase($this->validated());
    }
}
