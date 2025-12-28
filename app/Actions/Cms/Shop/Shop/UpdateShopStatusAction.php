<?php

namespace App\Actions\Cms\Shop\Shop;

use App\Models\Shop\Shop;

class UpdateShopStatusAction
{
    /**
     * Handle the action.
     */
    public function handle(Shop $shop, array $data): bool
    {
        return $shop->update([
            'status' => $data['status'],
        ]);
    }
}
