<?php

namespace App\Http\Requests\Cms\Shop\Shop;

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
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
            'rajaongkir_province_id' => 'required|integer',
            'rajaongkir_city_id' => 'required|integer',
            'rajaongkir_district_id' => 'required|integer',
            'logo' => 'nullable|image|max:2048', // 2MB
            'banner' => 'nullable|image|max:5120', // 5MB
            'status' => 'required|boolean',

            'translations' => 'nullable|array',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ];
    }
}
