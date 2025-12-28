<?php

namespace App\Http\Requests\Cms\Shop\Shop;

use App\Enums\ValidationEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|integer|in:'.implode(',', ValidationEnum::toArray()),
        ];
    }
}
