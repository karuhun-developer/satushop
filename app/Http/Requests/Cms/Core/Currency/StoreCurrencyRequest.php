<?php

namespace App\Http\Requests\Cms\Core\Currency;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:10|unique:currencies,code',
            'name' => 'required|string|max:255',
            'is_default' => 'required|boolean',
        ];
    }
}
