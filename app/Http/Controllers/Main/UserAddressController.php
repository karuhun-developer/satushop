<?php

namespace App\Http\Controllers\Main;

use App\Actions\Cms\User\UserAddress\DeleteUserAddressAction;
use App\Actions\Cms\User\UserAddress\StoreUserAddressAction;
use App\Actions\Cms\User\UserAddress\UpdateUserAddressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\User\UserAddress\StoreUserAddressRequest;
use App\Http\Requests\Cms\User\UserAddress\UpdateUserAddressRequest;
use App\Models\User\UserAddress;

class UserAddressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserAddressRequest $request, StoreUserAddressAction $action)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $action->handle($data);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserAddressRequest $request, UserAddress $userAddress, UpdateUserAddressAction $action)
    {
        // Ensure user can only update their own addresses
        if ($userAddress->user_id !== auth()->id()) {
            abort(403);
        }

        $action->handle($userAddress, $request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddress $userAddress, DeleteUserAddressAction $action)
    {
        // Ensure user can only delete their own addresses
        if ($userAddress->user_id !== auth()->id()) {
            abort(403);
        }

        $action->handle($userAddress);

        return back();
    }
}
