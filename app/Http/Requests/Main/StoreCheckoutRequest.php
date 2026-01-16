<?php

namespace App\Http\Requests\Main;

use Illuminate\Foundation\Http\FormRequest;

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
            'payment_method' => 'required|string|in:bank_transfer,midtrans',
            'gateway_type' => 'required_if:payment_method,midtrans|string|in:qris,bank_transfer',
            'gateway_bank' => 'required_if:payment_method,midtrans|string|in:qris,bca,bni,bri,mandiri,permata',

            // Items
            'items' => 'required|array',
            // shop id, product id, quantity
            'items.*.*.quantity' => 'required|integer|min:1',

            // Shipping
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
