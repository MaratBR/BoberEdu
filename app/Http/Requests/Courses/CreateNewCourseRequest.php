<?php

namespace App\Http\Requests\Courses;

use App\Course;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\IPayloadRequest;
use App\Utils\Convert;
use Illuminate\Support\Facades\Gate;

class CreateNewCourseRequest extends AuthenticatedRequest implements IPayloadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize() && Gate::allows('create', Course::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255',
            'price' => 'numeric|min:0|max:9999999999999999999.99',
            'about' => 'required',
            'signUpBeg' => 'nullable|date_format:Y-m-d',
            'signUpEnd' => 'nullable|date_format:Y-m-d',
            'available' => 'boolean',
            'categoryId' => 'numeric|required|exists:categories,id',
            'trialLength' => 'numeric|min:0|max:365',
            'summary' => 'string|nullable'
        ];
    }

    function getPayload(): array
    {
        return Convert::toSnakeCase($this->validated());
    }
}
