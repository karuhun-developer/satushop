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
        $data['price'] = currencyToNumber($data['price']);

        // Translations
        foreach ($data['translations'] as $locale => $translation) {
            $product->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $translation['name'] ?? '',
                    'description' => $translation['description'] ?? null,
                    'short_description' => $translation['short_description'] ?? null,
                ]
            );
        }

        // Save media
        for ($i = 1; $i <= 8; $i++) {
            if ($data['image_'.$i] ?? null instanceof UploadedFile) {
                $this->saveMedia(
                    model: $product,
                    file: $data['image_'.$i],
                    collection: 'image_'.$i,
                );
            }
        }

        // Save categories
        if (isset($data['categories'])) {
            $product->categories()->delete();

            foreach ($data['categories'] as $categoryId) {
                $product->categories()->create([
                    'product_category_id' => $categoryId,
                ]);
            }
        }

        // Save variants
        if (isset($data['variants'])) {
            $product->variants()->delete();

            foreach ($data['variants'] as $variantId) {
                $product->variants()->create([
                    'product_id' => $product->product_id,
                    'variant_product_id' => $variantId,
                ]);
            }
        }

        return $product->update($data);
    }
}
