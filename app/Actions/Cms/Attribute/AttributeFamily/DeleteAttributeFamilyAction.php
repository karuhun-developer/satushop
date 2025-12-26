<?php

namespace App\Actions\Cms\Attribute\AttributeFamily;

use App\Models\Attribute\AttributeFamily;

class DeleteAttributeFamilyAction
{
    /**
     * Handle the action.
     */
    public function handle(AttributeFamily $attributeFamily): ?bool
    {
        return $attributeFamily->delete();
    }
}
