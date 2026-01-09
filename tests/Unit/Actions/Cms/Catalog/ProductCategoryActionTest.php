<?php

use App\Actions\Cms\Catalog\ProductCategory\DeleteProductCategoryAction;
use App\Actions\Cms\Catalog\ProductCategory\StoreProductCategoryAction;
use App\Actions\Cms\Catalog\ProductCategory\UpdateProductCategoryAction;
use App\Models\Catalog\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store product category action creates a category', function () {
    Storage::fake('public');
    $action = new StoreProductCategoryAction;
    $data = [
        'name' => 'Test Category',
        'status' => 'active',
        'translations' => [
            'en' => [
                'name' => 'Test Category EN',
                'description' => 'Test Description EN',
            ],
        ],
        'image' => UploadedFile::fake()->image('category.jpg'),
    ];

    $category = $action->handle($data);

    expect($category)->toBeInstanceOf(ProductCategory::class);
    $this->assertDatabaseHas('product_categories', ['name' => 'Test Category', 'status' => 'active']);
    $this->assertDatabaseHas('product_category_translations', [
        'product_category_id' => $category->id,
        'locale' => 'en',
        'name' => 'Test Category EN',
    ]);
});

test('update product category action updates a category', function () {
    Storage::fake('public');
    $category = ProductCategory::create(['name' => 'Old Name', 'status' => 'active']);
    $category->translations()->create(['locale' => 'en', 'name' => 'Old Name EN']);

    $action = new UpdateProductCategoryAction;
    $data = [
        'name' => 'New Name',
        'status' => 'inactive',
        'translations' => [
            'en' => [
                'name' => 'New Name EN',
                'description' => 'New Description EN',
            ],
        ],
    ];

    $result = $action->handle($category, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('product_categories', ['id' => $category->id, 'name' => 'New Name', 'status' => 'inactive']);
    $this->assertDatabaseHas('product_category_translations', [
        'product_category_id' => $category->id,
        'locale' => 'en',
        'name' => 'New Name EN',
    ]);
});

test('delete product category action deletes a category', function () {
    $category = ProductCategory::create(['name' => 'Delete Me', 'status' => 'active']);
    $action = new DeleteProductCategoryAction;

    $result = $action->handle($category);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('product_categories', ['id' => $category->id]);
});
