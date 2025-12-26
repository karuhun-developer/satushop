<?php

namespace Database\Seeders;

use App\Models\Menu\Menu;
use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SuperadminMenuSeeder extends Seeder
{
    public $role;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->role = Role::where('name', 'superadmin')->first();
        Menu::where('role_id', $this->role->id)->delete();

        // Clear cache
        Cache::forget('menus:role:'.$this->role->id);

        // Create menu
        $this->dashboardMenu();
        $this->attributeMenu();
        $this->coreMenu();
        $this->managementMenu();
    }

    public function dashboardMenu()
    {
        Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Dashboard',
            'url' => '/cms/dashboard',
            'icon' => 'LayoutGrid',
            'order' => 1,
            'active_pattern' => '/cms/dashboard',
            'status' => 1,
        ]);
    }

    public function attributeMenu()
    {
        $catalog = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Attributes',
            'url' => '#',
            'icon' => 'Tag',
            'order' => 997,
            'active_pattern' => '/cms/attribute',
            'status' => 1,
        ]);
        $catalog->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Attribute Families',
            'url' => '/cms/attribute/attribute-families',
            'order' => 1,
            'active_pattern' => '/cms/attribute/attribute-families',
            'status' => 1,
        ]);
    }

    public function coreMenu()
    {
        $core = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Settings',
            'url' => '#',
            'icon' => 'Settings',
            'order' => 998,
            'active_pattern' => '/cms/core',
            'status' => 1,
        ]);
        $core->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Locales',
            'url' => '/cms/core/locales',
            'order' => 1,
            'active_pattern' => '/cms/core/locales',
            'status' => 1,
        ]);
        $core->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Currencies',
            'url' => '/cms/core/currencies',
            'order' => 2,
            'active_pattern' => '/cms/core/currencies',
            'status' => 1,
        ]);
        $core->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Exchange Rates',
            'url' => '/cms/core/currency-rates',
            'order' => 3,
            'active_pattern' => '/cms/core/currency-rates',
            'status' => 1,
        ]);
    }

    public function managementMenu()
    {
        $management = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Managements',
            'url' => '#',
            'icon' => 'Folder',
            'order' => 999,
            'active_pattern' => '/cms/management',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Permission',
            'url' => '/cms/management/permissions',
            'order' => 1,
            'active_pattern' => '/cms/management/permissions',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Role',
            'url' => '/cms/management/roles',
            'order' => 2,
            'active_pattern' => '/cms/management/roles',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Menu',
            'url' => '/cms/management/menus',
            'order' => 3,
            'active_pattern' => '/cms/management/menus',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'User',
            'url' => '/cms/management/users',
            'order' => 4,
            'active_pattern' => '/cms/management/users',
            'status' => 1,
        ]);
    }
}
