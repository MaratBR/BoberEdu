<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;
use Illuminate\Foundation\Http\FormRequest;

class CreateTeacherRequest extends AdminRequest implements IPayloadRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'passNum' => 'required',
            'fullName' => 'required',
            'userId' => 'numeric|required'
        ];
    }

    public function getUserId(): int
    {
        return $this->json('fullName');
    }

    function getPayload(): array
    {
        $payload = Convert::onlyKeys($this->validated(), ['fullName', 'passNum']);
        return Convert::toSnakeCase($payload);
    }
}
