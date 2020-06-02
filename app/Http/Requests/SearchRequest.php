<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
        return [];
    }

    public function getQuery(): ?string
    {
        $q = $this->input('q');
        if (is_array($q) && array_key_exists(0, $q)) $q = trim($q[0]);
        elseif (!is_string($q)) $q = null;
        return $q;
    }

    public function hasParametricSearch()
    {
        return $this->getParameter() !== null;
    }

    public function getParameter(): ?string
    {
        $p = $this->input('p');
        return is_string($p) ? $p : null;
    }
}
