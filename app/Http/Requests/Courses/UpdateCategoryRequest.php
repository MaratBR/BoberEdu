<?php

namespace App\Http\Requests\Courses;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\IPayloadRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends AdminRequest implements IPayloadRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|max:40',
            'about' => 'string|max:250',
            'color' => 'string|max:6|min:6'
        ];
    }

    function getPayload(): array
    {
        $d = $this->validated();

        if (array_key_exists('color', $d)) {
            $d['uidata_color'] = $d['color'];
            unset($d['color']);
        }
        return $d;
    }
}
