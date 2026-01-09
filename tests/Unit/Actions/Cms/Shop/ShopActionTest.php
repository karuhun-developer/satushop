<?php

use App\Actions\Cms\Shop\Shop\DeleteShopAction;
use App\Actions\Cms\Shop\Shop\StoreShopAction;
use App\Actions\Cms\Shop\Shop\UpdateShopAction;
use App\Enums\ValidationEnum;
use App\Models\Shop\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store shop action creates a shop', function () {
    Storage::fake('public');
    $action = new StoreShopAction;
    $data = [
        'name' => 'Test Shop',
        'email' => 'shop@test.com',
        'status' => ValidationEnum::PENDING->value,
        'translations' => [
            'en' => [
                'name' => 'Test Shop EN',
                'description' => 'Test Description EN',
            ],
        ],
        // 'logo' => UploadedFile::fake()->image('logo.jpg'), // Removed due to missing AVIF support
        // 'banner' => UploadedFile::fake()->image('banner.jpg'), // Removed due to missing AVIF support
    ];

    $shop = $action->handle($data);

    expect($shop)->toBeInstanceOf(Shop::class);
    $this->assertDatabaseHas('shops', ['name' => 'Test Shop', 'email' => 'shop@test.com', 'status' => ValidationEnum::PENDING->value]);
    $this->assertDatabaseHas('shop_translations', [
        'shop_id' => $shop->id,
        'locale' => 'en',
        'name' => 'Test Shop EN',
    ]);
});

test('update shop action updates a shop', function () {
    Storage::fake('public');
    $shop = Shop::factory()->create(['name' => 'Old Shop', 'status' => ValidationEnum::PENDING]);
    $shop->translations()->create(['locale' => 'en', 'name' => 'Old Shop EN']);

    $action = new UpdateShopAction;
    $data = [
        'name' => 'New Shop',
        'status' => ValidationEnum::APPROVED->value,
        'translations' => [
            'en' => [
                'name' => 'New Shop EN',
                'description' => 'New Description EN',
            ],
        ],
    ];

    $result = $action->handle($shop, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('shops', ['id' => $shop->id, 'name' => 'New Shop', 'status' => ValidationEnum::APPROVED->value]);
    $this->assertDatabaseHas('shop_translations', [
        'shop_id' => $shop->id,
        'locale' => 'en',
        'name' => 'New Shop EN',
    ]);
});

test('delete shop action deletes a shop', function () {
    $shop = Shop::factory()->create();
    $action = new DeleteShopAction;

    $result = $action->handle($shop);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
});
