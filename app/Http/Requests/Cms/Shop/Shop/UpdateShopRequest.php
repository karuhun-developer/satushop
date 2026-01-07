<?php

namespace App\Http\Requests\Cms\Shop\Shop;

use App\Enums\ValidationEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'rajaongkir_province_id' => 'required|integer',
            'rajaongkir_city_id' => 'required|integer',
            'rajaongkir_district_id' => 'required|integer',
            'meta_data' => 'nullable|array',
            'meta_data.*' => 'nullable|string',
            'logo' => 'nullable|image|max:2048', // 2MB
            'banner' => 'nullable|image|max:5120', // 5MB
            'status' => 'required|in:'.implode(',', ValidationEnum::toArray()),

            'translations' => 'nullable|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ];
    }
}
