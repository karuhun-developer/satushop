<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Models\Catalog\ProductFlat;
use App\Traits\WithMediaCollection;
use Illuminate\Http\UploadedFile;

class UpdateProductAction
{
    use WithMediaCollection;

    /**
     * Handle the action.
     */
    public function handle(ProductFlat $product, array $data): bool
    {
        // Translations
        foreach ($data['translations'] as $locale => $translation) {
            $product->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $translation['name'] ?? null,
                    'description' => $translation['description'] ?? null,
                ]
            );
        }

        // Save media
        if ($data['image'] ?? null instanceof UploadedFile) {
            $this->saveMedia(
                model: $product,
                file: $data['image'],
                collection: 'image',
            );
        }

        return $product->update($data);
    }
}
