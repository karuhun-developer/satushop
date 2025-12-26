<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Cache::flush();

        $this->call([
            CoreSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            SuperadminMenuSeeder::class,
            DefaultShopSeeder::class,
            AttributeSeeder::class,
        ]);
    }
}
