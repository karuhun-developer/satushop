<?php

namespace App\Http\Requests\Cms\Attribute\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attribute_family_id' => 'required|exists:attribute_families,id',
            'code' => 'required|string|max:255|unique:attributes,code',
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
            'translations' => 'required|array',
            'translations.*.name' => 'nullable|string|max:255',

            'options' => 'nullable|array',
            'options.*.id' => 'nullable|exists:attribute_options,id',
            'options.*.name' => 'required|string|max:255',
            'options.*.order' => 'required|string|max:255',
            'options.*.status' => 'required|boolean',
            'options.*.translations' => 'required|array',
            'options.*.translations.*.name' => 'nullable|string|max:255',

            'status' => 'required|boolean',
        ];
    }
}
