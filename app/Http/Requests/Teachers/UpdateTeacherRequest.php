<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;

class UpdateTeacherRequest extends AuthenticatedRequest implements IPayloadRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullName' => 'string',
            'about' => 'string',

            'linkWeb' => 'string|nullable',
            'linkYt' => 'string|nullable',
            'linkLinkedIn' => 'string|nullable',
            'linkVk' => 'string|nullable',
            'linkFb' => 'string|nullable',
            'linkTwitter' => 'string|nullable',
        ];
    }

    function getPayload(): array
    {
        return Convert::toSnakeCase($this->validated());
    }
}
