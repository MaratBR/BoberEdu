<?php

namespace App\Http\Requests\Payments;

use App\Http\Requests\AuthenticatedRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends AuthenticatedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'gateaway' => 'string|required',
            'data' => 'array|required'
        ];
    }

    public function getData(): array
    {
        return $this->json('data');
    }

    public function getGateaway(): string
    {
        return $this->json('gateaway');
    }
}
