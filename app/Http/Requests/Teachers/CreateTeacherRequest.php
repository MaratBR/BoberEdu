<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\IPayloadRequest;

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
            'userId' => 'numeric|required',
            'comment' => 'required'
        ];
    }

    public function getUserId(): int
    {
        return $this->validated()['userId'];
    }

    function getPayload(): array
    {
        return [
            'full_name' => $this->validated()['fullName'],
            'passport_num' => $this->validated()['passNum']
        ];
    }

    function getComment(): string
    {
        return $this->validated()['comment'];
    }
}
