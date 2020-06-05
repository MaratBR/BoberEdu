<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;

class EditUserRequest extends AuthenticatedRequest implements IPayloadRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string',
            'about' => 'string|nullable',
            'status' => 'string|nullable',
            'email' => 'string',
            'displayName' => 'string|nullable'
        ];
    }

    function getPayload(): array
    {
        return Convert::toSnakeCase($this->validated());
    }
}
