<?php

namespace App\Actions\Cms\Shop\Shop;

use App\Models\Shop\Shop;
use App\Traits\WithMediaCollection;
use Illuminate\Http\UploadedFile;

class UpdateShopAction
{
    use WithMediaCollection;

    /**
     * Handle the action.
     */
    public function handle(Shop $shop, array $data): bool
    {
        // Translations
        foreach ($data['translations'] as $locale => $translation) {
            $shop->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $translation['name'],
                    'description' => $translation['description'] ?? null,
                ]
            );
        }

        // Save media
        if ($data['logo'] ?? null instanceof UploadedFile) {
            $this->saveMedia(
                model: $shop,
                file: $data['logo'],
                collection: 'logo',
            );
        }

        if ($data['banner'] ?? null instanceof UploadedFile) {
            $this->saveMedia(
                model: $shop,
                file: $data['banner'],
                collection: 'banner',
            );
        }

        return $shop->update($data);
    }
}
