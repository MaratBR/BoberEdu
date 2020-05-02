<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\IPayloadRequest;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest implements IPayloadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8'
        ];
    }

    function getPayload(): array
    {
        return $this->validated();
    }
}
