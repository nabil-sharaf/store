<?php

namespace Database\Seeders;

use App\Models\Admin\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ايقاف التحقق من الفورين كي مؤقتا
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Status::truncate();

        // اعادة التحقق من الفورين كي
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Status::create(['name' => 'جاري المعالجة']);
        Status::create(['name' => 'جاري الشحن']);
        Status::create(['name' => 'تم التسليم ']);
        Status::create(['name' => 'ملغي']);
    }
}
