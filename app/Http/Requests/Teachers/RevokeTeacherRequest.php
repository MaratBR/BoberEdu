<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AdminRequest;
use Illuminate\Foundation\Http\FormRequest;

class RevokeTeacherRequest extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'string|required'
        ];
    }

    public function getComment(): string
    {
        return $this->validated()['comment'];
    }
}
