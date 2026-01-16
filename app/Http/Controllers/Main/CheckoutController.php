<?php

namespace App\Http\Controllers\Main;

use App\Actions\Main\Checkout\StoreCheckoutAction;
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

    public function store(StoreCheckoutRequest $request, StoreCheckoutAction $action)
    {
        try {
            $transaction = $action->handle($request->validated());

            return to_route('transaction.show', [
                'transaction' => $transaction->id,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors('An error occurred while processing your checkout. Please try again.');
        }
    }
}
