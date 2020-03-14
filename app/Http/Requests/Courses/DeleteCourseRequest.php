<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AuthenticatedRequest;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCourseRequest extends AuthenticatedRequest
{
    public function isForce(): bool
    {
        return $this->validated()['force'] ?? false;
    }

    public function rules()
    {
        return [
            'force' => 'booleand'
        ];
    }
}
