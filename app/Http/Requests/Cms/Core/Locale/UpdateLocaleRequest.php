<?php

namespace App\Http\Requests\Cms\Core\Locale;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:10|unique:locales,code,'.request()->route('locale')->id,
            'name' => 'required|string|max:255',
            'direction' => 'required|string|in:ltr,rtl',
            'is_default' => 'required|boolean',
        ];
    }
}
