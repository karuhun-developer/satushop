<?php

namespace App\Actions\Cms\User\UserAddress;

use App\Models\User\UserAddress;

class DeleteUserAddressAction
{
    /**
     * Handle the action.
     */
    public function handle(UserAddress $userAddress): void
    {
        $userAddress->delete();
    }
}
