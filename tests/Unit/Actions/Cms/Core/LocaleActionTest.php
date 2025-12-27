<?php

use App\Actions\Cms\Core\Locale\DeleteLocaleAction;
use App\Actions\Cms\Core\Locale\StoreLocaleAction;
use App\Actions\Cms\Core\Locale\UpdateLocaleAction;
use App\Models\Core\Locale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store locale action creates a locale', function () {
    $action = new StoreLocaleAction;
    $data = [
        'code' => 'en',
        'name' => 'English',
        'direction' => 'ltr',
    ];

    $locale = $action->handle($data);

    expect($locale)->toBeInstanceOf(Locale::class);
    $this->assertDatabaseHas('locales', $data);
});

test('update locale action updates a locale', function () {
    $locale = Locale::create([
        'code' => 'old',
        'name' => 'Old Name',
        'direction' => 'ltr',
        'is_default' => false,
    ]);
    $action = new UpdateLocaleAction;
    $data = [
        'code' => 'new',
        'name' => 'New Name',
        'direction' => 'rtl',
    ];

    $result = $action->handle($locale, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('locales', ['id' => $locale->id, 'code' => 'new', 'direction' => 'rtl']);
});

test('delete locale action deletes a locale', function () {
    $locale = Locale::create([
        'code' => 'del',
        'name' => 'Delete Me',
        'direction' => 'ltr',
        'is_default' => false,
    ]);
    $action = new DeleteLocaleAction;

    $result = $action->handle($locale);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('locales', ['id' => $locale->id]);
});
