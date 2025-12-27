<?php

namespace App\Actions\Cms\Attribute\Attribute;

use App\Models\Attribute\Attribute;

class StoreAttributeAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Attribute
    {
        $attribute = Attribute::create($data);

        // Translations
        foreach ($data['translations'] as $locale => $translation) {
            $attribute->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $translation['name']]
            );
        }

        // Options
        if (isset($data['options']) && is_array($data['options'])) {
            foreach ($data['options'] as $optionData) {
                $option = $attribute->options()->create([
                    'name' => $optionData['name'],
                    'order' => $optionData['order'],
                    'status' => $optionData['status'],
                ]);
                // Option Translations
                foreach ($optionData['translations'] as $locale => $translation) {
                    $option->translations()->updateOrCreate(
                        ['locale' => $locale],
                        ['name' => $translation['name']]
                    );
                }
            }
        }

        return $attribute;
    }
}
