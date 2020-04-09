<?php

namespace App\Http\Requests\Courses;

use App\Services\ICourseUnitsPayload;

class UpdateCourseUnitsRequest extends UpdateCourseRequest implements ICourseUnitsPayload
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delete.*' => 'numeric',

            'order.*' => ['regex:/^n?(?:0|[1-9]\d*)$/i'],

            'new.*.name' => 'required|string',
            'new.*.about' => 'required|string',
            'new.*.is_preview' => 'boolean',

            'upd.*.id' => 'numeric|required',
            'upd.*.name' => 'string',
            'upd.*.about' => 'string',
            'upd.*.is_preview' => 'boolean',
        ];
    }

    public function getOrder(): array
    {
        return $this->validated()['order'] ?? [];
    }

    public function getNew(): array
    {
        return $this->validated()['new'] ?? [];
    }

    public function getUpdated(): array
    {
        return $this->validated()['upd'] ?? [];
    }

    public function getDeleted(): array
    {
        return $this->validated()['delete'] ?? [];
    }
}
