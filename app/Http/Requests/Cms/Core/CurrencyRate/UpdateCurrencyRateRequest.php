<?php

namespace App\Http\Requests\Cms\Core\CurrencyRate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyRateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'target_currency_id' => 'required|exists:currencies,id',
            'rate' => 'required|numeric|min:0',
        ];
    }
}
