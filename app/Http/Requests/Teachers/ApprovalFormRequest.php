<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;
use Illuminate\Foundation\Http\FormRequest;

class ApprovalFormRequest extends AuthenticatedRequest implements IPayloadRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullName' => 'string|required',
            'education' => 'string|required',
            'degree' => 'string|required',
            'location' => 'string|required',
            'extra' => 'string|nullable'
        ];
    }

    function getPayload(): array
    {
        return Convert::toSnakeCase($this->validated());
    }
}
