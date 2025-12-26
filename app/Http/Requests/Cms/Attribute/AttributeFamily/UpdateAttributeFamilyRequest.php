<?php

namespace App\Http\Requests\Cms\Attribute\AttributeFamily;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeFamilyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:attribute_families,code,'.$this->attribute_family->id,
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ];
    }
}
