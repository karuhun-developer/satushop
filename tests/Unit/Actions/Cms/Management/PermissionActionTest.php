<?php

use App\Actions\Cms\Management\Permission\DeletePermissionAction;
use App\Actions\Cms\Management\Permission\StorePermissionAction;
use App\Actions\Cms\Management\Permission\UpdatePermissionAction;
use App\Models\Spatie\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store permission action creates a permission', function () {
    $action = new StorePermissionAction;
    $data = ['name' => 'view dashboard', 'guard_name' => 'api'];

    $permission = $action->handle($data);

    expect($permission)->toBeInstanceOf(Permission::class);
    $this->assertDatabaseHas('permissions', $data);
});

test('update permission action updates a permission', function () {
    $permission = Permission::create(['name' => 'old_permission', 'guard_name' => 'api']);
    $action = new UpdatePermissionAction;
    $data = ['name' => 'new_permission', 'guard_name' => 'web'];

    $result = $action->handle($permission, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'new_permission', 'guard_name' => 'web']);
});

test('delete permission action deletes a permission', function () {
    $permission = Permission::create(['name' => 'delete_me', 'guard_name' => 'api']);
    $action = new DeletePermissionAction;

    $result = $action->handle($permission);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
});
