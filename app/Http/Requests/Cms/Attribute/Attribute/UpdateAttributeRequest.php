<?php

namespace App\Http\Requests\Cms\Attribute\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:attributes,code,'.$this->attribute->id,
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
