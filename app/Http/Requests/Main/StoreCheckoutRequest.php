<?php

namespace App\Http\Requests\Main;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckoutRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'postcode' => 'nullable|string|max:20',
            'rajaongkir_province_id' => 'required|integer',
            'rajaongkir_city_id' => 'required|integer',
            'rajaongkir_district_id' => 'required|integer',

            // Payment
            'payment_method' => 'required|string',
            'shipping' => 'required|array',
            'shipping.*.id' => 'required|string|max:50',
            'shipping.*.label' => 'required|string|max:50',
            'shipping.*.courier' => 'required|string|max:50',
            'shipping.*.courier_name' => 'required|string|max:50',
            'shipping.*.service' => 'required|string|max:50',
            'shipping.*.cost' => 'required|numeric',
            'shipping.*.etd' => 'required|string|max:50',
        ];
    }
}
