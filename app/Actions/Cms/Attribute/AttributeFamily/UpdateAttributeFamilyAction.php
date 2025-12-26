<?php

namespace App\Actions\Cms\Attribute\AttributeFamily;

use App\Models\Attribute\AttributeFamily;

class UpdateAttributeFamilyAction
{
    /**
     * Handle the action.
     */
    public function handle(AttributeFamily $attributeFamily, array $data): bool
    {
        return $attributeFamily->update($data);
    }
}
