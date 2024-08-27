<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Category;
use Illuminate\Support\Facades\DB;
class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        // تعطيل التحقق من المفاتيح الأجنبية
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('categories')->truncate();

// إعادة تمكين التحقق من المفاتيح الأجنبية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Category::create(['name' => 'العاب', 'description' => '','parent_id'=>null]);
        Category::create(['name' => 'العاب 1', 'description' => ' ','parent_id'=>1]);
        Category::create(['name' => ' العاب 2', 'description' => ' ','parent_id'=>1]);
        Category::create(['name' => ' العاب 3', 'description' => '','parent_id'=>2]);
        Category::create(['name' => 'العاب 4 ', 'description' => '','parent_id'=>3]);
    }
}
