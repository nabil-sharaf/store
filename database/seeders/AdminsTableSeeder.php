<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Admin;
use Illuminate\Support\Str;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        Admin::truncate();

        Admin::create([
            'name' => 'Nabil',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}

