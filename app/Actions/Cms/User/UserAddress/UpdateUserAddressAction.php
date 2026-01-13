<?php

namespace App\Actions\Cms\User\UserAddress;

use App\Models\User\UserAddress;

class UpdateUserAddressAction
{
    /**
     * Handle the action.
     */
    public function handle(UserAddress $userAddress, array $data): UserAddress
    {
        // If this is set as default, unset other default addresses for this user
        if ($data['is_default'] ?? false) {
            UserAddress::where('user_id', $userAddress->user_id)
                ->where('id', '!=', $userAddress->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $userAddress->update($data);

        return $userAddress;
    }
}
