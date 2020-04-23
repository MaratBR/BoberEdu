<?php

namespace App\Http\Requests\Teachers;

use App\Http\Requests\AdminRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AssignTeacherRequest extends AdminRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'since' => 'date_format:Y-m-d|required',
            'until' => 'date_format:Y-m-d|required',
            'comment' => 'string|required'
        ];
    }

    public function getComment(): string
    {
        return $this->validated()['comment'];
    }

    public function getSince(): Carbon
    {
        return $this->validated()['since'];
    }

    public function getUntil(): Carbon
    {
        return $this->validated()['until'];
    }
}

