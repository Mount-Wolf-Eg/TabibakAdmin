<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RoleTableSeeder::class,
            UsersTableSeeder::class,
            VendorTypesTableSeeder::class,
            RegionsTableSeeder::class,
            CitiesTableSeeder::class,
            CurrenciesTableSeeder::class
        ]);
    }
}
