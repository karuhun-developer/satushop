<?php

namespace App\Http\Requests\Cms\User\UserAddress;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserAddressRequest extends FormRequest
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
            'address' => 'required|string',
            'postcode' => 'nullable|string|max:20',
            'rajaongkir_province_id' => 'required|integer',
            'rajaongkir_city_id' => 'required|integer',
            'rajaongkir_district_id' => 'required|integer',
            'is_default' => 'nullable|boolean',
        ];
    }
}
