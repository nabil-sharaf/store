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

        Category::create(['name' => 'حلويات', 'description' => '','parent_id'=>null]);
        Category::create(['name' => 'شرقي', 'description' => 'حلويات شرقي','parent_id'=>1]);
        Category::create(['name' => ' غربي', 'description' => 'حلويات غربي','parent_id'=>1]);
        Category::create(['name' => ' كنافة', 'description' => 'كنافة','parent_id'=>2]);
        Category::create(['name' => 'كنافة مكسرات ', 'description' => '','parent_id'=>3]);
    }
}
