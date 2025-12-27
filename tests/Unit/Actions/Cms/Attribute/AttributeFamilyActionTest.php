<?php

use App\Actions\Cms\Attribute\AttributeFamily\DeleteAttributeFamilyAction;
use App\Actions\Cms\Attribute\AttributeFamily\StoreAttributeFamilyAction;
use App\Actions\Cms\Attribute\AttributeFamily\UpdateAttributeFamilyAction;
use App\Models\Attribute\AttributeFamily;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store attribute family action creates an attribute family', function () {
    $action = new StoreAttributeFamilyAction;
    $data = [
        'code' => 'clothing',
        'name' => 'Clothing',
    ];

    $family = $action->handle($data);

    expect($family)->toBeInstanceOf(AttributeFamily::class);
    $this->assertDatabaseHas('attribute_families', $data);
});

test('update attribute family action updates an attribute family', function () {
    $family = AttributeFamily::create([
        'code' => 'old',
        'name' => 'Old Name',
        'status' => true,
    ]);
    $action = new UpdateAttributeFamilyAction;
    $data = [
        'code' => 'new',
        'name' => 'New Name',
    ];

    $result = $action->handle($family, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('attribute_families', ['id' => $family->id, 'code' => 'new']);
});

test('delete attribute family action deletes an attribute family', function () {
    $family = AttributeFamily::create([
        'code' => 'del',
        'name' => 'Delete Me',
        'status' => true,
    ]);
    $action = new DeleteAttributeFamilyAction;

    $result = $action->handle($family);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('attribute_families', ['id' => $family->id]);
});
