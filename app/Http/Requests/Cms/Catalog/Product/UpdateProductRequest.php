<?php

namespace App\Http\Requests\Cms\Catalog\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meta_data' => 'nullable|array',
            'price' => 'required',
            'special_price' => 'nullable|numeric|min:0|lt:price',
            'special_price_start' => 'nullable|date',
            'special_price_end' => 'nullable|date|after_or_equal:special_price_start',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'diameter' => 'nullable|numeric|min:0',
            'visible_individually' => 'required|boolean',

            'translations' => 'required|array',
            'translations.*.name' => 'nullable|string|max:255',
            'translations.*.short_description' => 'nullable|string',
            'translations.*.description' => 'nullable|string',

            'categories' => 'nullable|array',
            'categories.*' => 'required|exists:product_categories,id',

            'variants' => 'nullable',
            'variants.*' => 'required|exists:product_flats,id',

            'image_1' => 'nullable|image|max:5120', // 5MB
            'image_2' => 'nullable|image|max:5120', // 5MB
            'image_3' => 'nullable|image|max:5120', // 5MB
            'image_4' => 'nullable|image|max:5120', // 5MB
            'image_5' => 'nullable|image|max:5120', // 5MB
            'image_6' => 'nullable|image|max:5120', // 5MB
            'image_7' => 'nullable|image|max:5120', // 5MB
            'image_8' => 'nullable|image|max:5120', // 5MB
        ];
    }
}
