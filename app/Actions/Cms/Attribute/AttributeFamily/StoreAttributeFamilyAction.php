<?php

namespace App\Actions\Cms\Attribute\AttributeFamily;

use App\Models\Attribute\AttributeFamily;

class StoreAttributeFamilyAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): AttributeFamily
    {
        return AttributeFamily::create($data);
    }
}
