<?php

namespace App\Actions\Cms\Attribute\Attribute;

use App\Models\Attribute\Attribute;

class UpdateAttributeAction
{
    /**
     * Handle the action.
     */
    public function handle(Attribute $attribute, array $data): bool
    {
        // Translations
        foreach ($data['translations'] as $locale => $translation) {
            $attribute->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $translation['name']]
            );
        }

        // Options
        if (isset($data['options']) && is_array($data['options'])) {
            $existingOptionIds = $attribute->options()->pluck('id')->toArray();
            $submittedOptionIds = [];

            // Create or update options
            foreach ($data['options'] as $optionData) {
                if (isset($optionData['id']) && in_array($optionData['id'], $existingOptionIds)) {
                    // Update existing option
                    $option = $attribute->options()->find($optionData['id']);
                    $option->update([
                        'name' => $optionData['name'],
                        'order' => $optionData['order'],
                        'status' => $optionData['status'],
                    ]);
                } else {
                    // Create new option
                    $option = $attribute->options()->create([
                        'name' => $optionData['name'],
                        'order' => $optionData['order'],
                        'status' => $optionData['status'],
                    ]);
                }
                $submittedOptionIds[] = $option->id;
                // Option Translations
                foreach ($optionData['translations'] as $locale => $translation) {
                    $option->translations()->updateOrCreate(
                        ['locale' => $locale],
                        ['name' => $translation['name']]
                    );
                }
            }

            // Delete removed options
            $optionsToDelete = array_diff($existingOptionIds, $submittedOptionIds);
            if (! empty($optionsToDelete)) {
                $attribute->options()->whereIn('id', $optionsToDelete)->delete();
            }
        }

        return $attribute->update($data);
    }
}
