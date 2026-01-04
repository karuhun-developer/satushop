<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Shop\Shop;
use App\Traits\WithGetFilterData;
use App\Traits\WithReturnResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use WithGetFilterData, WithReturnResponse;

    public function shop(Request $request)
    {
        return $this->responseWithSuccess(
            data: $this->getDataWithFilter(
                model: new Shop,
                searchBy: [
                    'name',
                    'slug',
                    'phone',
                    'email',
                ]
            )
        );
    }
}
