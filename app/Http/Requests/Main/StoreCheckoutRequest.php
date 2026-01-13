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
            // Address selection or manual input
            'user_address_id' => [
                'nullable',
                Rule::exists('user_addresses', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],

            // Required if user_address_id is not provided
            'name' => 'required_without:user_address_id|string|max:255',
            'email' => 'required_without:user_address_id|email|max:255',
            'phone' => 'required_without:user_address_id|string|max:20',
            'address' => 'required_without:user_address_id|string',
            'postcode' => 'nullable|string|max:20',
            'rajaongkir_province_id' => 'required_without:user_address_id|integer',
            'rajaongkir_city_id' => 'required_without:user_address_id|integer',
            'rajaongkir_district_id' => 'required_without:user_address_id|integer',

            // Payment
            'payment_method' => 'required|string',
        ];
    }
}
