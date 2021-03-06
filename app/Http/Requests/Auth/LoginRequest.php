<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'name' => 'required|max:255',
            'password' => 'required|max:255',
            'rememberMe' => 'boolean'
        ];
    }

    public function getUsername(): string
    {
        return $this->validated()['name'];
    }


    public function getUserPassword(): string
    {
        return $this->validated()['password'];
    }

    public function getRememberMe(): bool
    {
        return $this->validated()['rememberMe'] ?? false;
    }
}
