<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // رمز البرومو
            $table->decimal('discount', 8, 2); // قيمة الخصم
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed'); // نوع الخصم
            $table->datetime('start_date')->notNullable();
            $table->datetime('end_date')->notNullable();
            $table->boolean('active')->default(true); // حالة التفعيل
            $table->decimal('min_amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
