<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\Main\StoreCheckoutRequest;
use App\Models\User\UserAddress;

class CheckoutController extends Controller
{
    public function index()
    {
        $userAddresses = [];

        if (auth()->check()) {
            $userAddresses = UserAddress::where('user_id', auth()->id())
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return inertia('main/Checkout', [
            'userAddresses' => $userAddresses,
        ]);
    }

    public function store(StoreCheckoutRequest $request)
    {
        // TODO: Implement checkout processing with StoreCheckoutAction
        dd($request->validated());
    }
}
