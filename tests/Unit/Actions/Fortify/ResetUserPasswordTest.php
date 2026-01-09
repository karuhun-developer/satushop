<?php

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('reset user password action updates password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $action = new ResetUserPassword;
    $data = [
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ];

    $action->reset($user, $data);

    expect(Hash::check('new-password123', $user->refresh()->password))->toBeTrue();
});

test('reset user password action validates password', function () {
    $user = User::factory()->create();
    $action = new ResetUserPassword;
    $data = [
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ];

    $action->reset($user, $data);
})->throws(ValidationException::class);
