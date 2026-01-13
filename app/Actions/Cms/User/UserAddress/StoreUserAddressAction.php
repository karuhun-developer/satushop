<?php

namespace App\Actions\Cms\User\UserAddress;

use App\Models\User\UserAddress;

class StoreUserAddressAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): UserAddress
    {
        // If this is set as default, unset other default addresses for this user
        if ($data['is_default'] ?? false) {
            UserAddress::where('user_id', $data['user_id'])
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $address = UserAddress::create($data);

        return $address;
    }
}
