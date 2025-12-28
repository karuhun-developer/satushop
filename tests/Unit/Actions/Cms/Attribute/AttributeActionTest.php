<?php

use App\Actions\Cms\Attribute\Attribute\DeleteAttributeAction;
use App\Actions\Cms\Attribute\Attribute\StoreAttributeAction;
use App\Actions\Cms\Attribute\Attribute\UpdateAttributeAction;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeFamily;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store attribute action creates an attribute with translations and options', function () {
    $family = AttributeFamily::create(['code' => 'fam', 'name' => 'Fam', 'status' => true]);
    $action = new StoreAttributeAction;
    $data = [
        'attribute_family_id' => $family->id,
        'code' => 'color',
        'name' => 'Color', // Added name
        'type' => 'select',
        'is_required' => true,
        'is_unique' => false,
        'validation' => 'required',
        'translations' => [
            'en' => ['name' => 'Color'],
            'id' => ['name' => 'Warna'],
        ],
        'options' => [
            [
                'name' => 'Red',
                'order' => 1,
                'status' => true,
                'translations' => [
                    'en' => ['name' => 'Red'],
                    'id' => ['name' => 'Merah'],
                ],
            ],
        ],
    ];

    $attribute = $action->handle($data);

    expect($attribute)->toBeInstanceOf(Attribute::class);
    $this->assertDatabaseHas('attributes', ['code' => 'color']);
    $this->assertDatabaseHas('attribute_translations', ['attribute_id' => $attribute->id, 'locale' => 'en', 'name' => 'Color']);
    $this->assertDatabaseHas('attribute_options', ['attribute_id' => $attribute->id, 'name' => 'Red']);
});

test('update attribute action updates an attribute with translations', function () {
    $family = AttributeFamily::create(['code' => 'fam', 'name' => 'Fam', 'status' => true]);
    $actionStore = new StoreAttributeAction;
    $dataStore = [
        'attribute_family_id' => $family->id,
        'code' => 'color',
        'name' => 'Color', // Added name
        'type' => 'select',
        'is_required' => true,
        'is_unique' => false,
        'validation' => 'required',
        'translations' => [
            'en' => ['name' => 'Color'],
        ],
        'options' => [],
    ];
    $attribute = $actionStore->handle($dataStore);

    $action = new UpdateAttributeAction;
    $data = [
        'attribute_family_id' => $family->id,
        'code' => 'colour',
        'name' => 'Colour', // Added name
        'type' => 'select',
        'is_required' => false, // changed
        'translations' => [
            'en' => ['name' => 'Colour'], // changed
        ],
        'options' => [],
    ];

    $result = $action->handle($attribute, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('attributes', ['id' => $attribute->id, 'code' => 'colour']);
    $this->assertDatabaseHas('attribute_translations', ['attribute_id' => $attribute->id, 'locale' => 'en', 'name' => 'Colour']);
});

test('delete attribute action deletes an attribute', function () {
    $family = AttributeFamily::create(['code' => 'fam', 'name' => 'Fam', 'status' => true]);
    $attribute = Attribute::create([
        'attribute_family_id' => $family->id,
        'code' => 'del',
        'name' => 'Delete Me',
        // 'order' and 'status' might have defaults or be nullable, but good to set if not sure.
        // Assuming strict types, let's provide basic fields if needed,
        // but factory would have handled this. Looking at model: code, name, order, status.
        'order' => 1,
        'status' => true,
    ]);
    $action = new DeleteAttributeAction;

    $result = $action->handle($attribute);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('attributes', ['id' => $attribute->id]);
});
