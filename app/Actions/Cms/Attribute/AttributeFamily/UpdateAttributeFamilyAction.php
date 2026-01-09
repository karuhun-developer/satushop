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
        // Attach attributes if provided
        $attributeFamily->groups()->delete();
        foreach ($data['attributes'] ?? [] as $attributeId) {
            $attributeFamily->groups()->create([
                'attribute_id' => $attributeId,
            ]);
        }

        return $attributeFamily->update($data);
    }
}
