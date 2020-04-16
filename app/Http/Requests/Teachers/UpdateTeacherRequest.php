<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends AdminRequest implements IPayloadRequest
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
            'passNum' => 'string'
        ];
    }

    function getPayload(): array
    {
        return Convert::toSnakeCase($this->validated());
    }
}
