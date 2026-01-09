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
        $attributeFamily = AttributeFamily::create($data);

        // Attach attributes if provided
        foreach ($data['attributes'] ?? [] as $attributeId) {
            $attributeFamily->groups()->create([
                'attribute_id' => $attributeId,
            ]);
        }

        return $attributeFamily;
    }
}
