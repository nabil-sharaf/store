<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_images', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable(); // مسار صورة اللوجو
            $table->string('slider_image')->nullable(); // مسار صورة السلايدر
            $table->string('footer_image')->nullable(); // مسار صورة الفوتر
            $table->string('offer_one')->nullable(); // مسار الصورة العروض 1
            $table->string('offer_two')->nullable(); // مسار الصورة العروض 2
            $table->string('payment_image')->nullable(); // مسار صورة وسائل الدفع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_images');
    }
};
