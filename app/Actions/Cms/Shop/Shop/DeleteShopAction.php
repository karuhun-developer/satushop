<?php

namespace App\Actions\Cms\Shop\Shop;

use App\Models\Shop\Shop;

class DeleteShopAction
{
    /**
     * Handle the action.
     */
    public function handle(Shop $shop): ?bool
    {
        return $shop->delete();
    }
}
