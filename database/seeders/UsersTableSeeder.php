<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        User::create([
            'name' => 'alaa',
            'phone' => '012345678910',
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => ' mohammed',
            'phone' => '01012345678',
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
        ]);
    }
}

