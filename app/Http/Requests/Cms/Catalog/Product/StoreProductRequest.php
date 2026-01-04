<?php

namespace App\Http\Requests\Cms\Catalog\Product;

use App\Enums\ProductTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shop_id' => 'required|exists:shops,id',
            'attribute_family_id' => 'required|exists:attribute_families,id',
            'type' => 'required|in:'.implode(',', ProductTypeEnum::toArray()),
            'sku' => 'required|string|unique:products,sku',
        ];
    }
}
