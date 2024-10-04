<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminsTableSeeder::class,
            UsersTableSeeder::class,
            StatusesTableSeeder::class,
            CategoriesTableSeeder::class,
            SettingsTableSeeder::class,
            RolesAndPermissionsSeeder::class,
         //   ProductsTableSeeder::class,
           // OrdersTableSeeder::class,
         //   OrderDetailsTableSeeder::class,
          //  ImagesTableSeeder::class,
          //  CategoryProductTableSeeder::class,
            ShippingRateSeeder::class,
        ]);
    }
}
