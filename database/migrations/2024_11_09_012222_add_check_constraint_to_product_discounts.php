<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // إضافة القيد المخصص للتحقق من أن إما product_id أو variant_id يحتويان على قيمة واحدة فقط
        DB::statement('
            ALTER TABLE product_discounts
            ADD CONSTRAINT check_product_variant_one_not_null
            CHECK (
                (product_id IS NOT NULL AND variant_id IS NULL) OR
                (product_id IS NULL AND variant_id IS NOT NULL)
            )
        ');
    }

    public function down()
    {
        // حذف القيد المخصص في حالة التراجع عن الميجريشن
        DB::statement('ALTER TABLE product_discounts DROP CONSTRAINT check_product_variant_one_not_null');
    }
};
