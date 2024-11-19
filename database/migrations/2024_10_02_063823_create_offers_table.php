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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('offer_name');  // اسم العرض
            $table->integer('offer_quantity');  // الكمية المطلوبة للحصول على العرض
            $table->integer('free_quantity');  // الكمية المجانية
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->enum('customer_type', ['goomla', 'regular','all']);  // نوع العميل
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // ربط بالمنتج
            $table->foreignId('variant_id')->nullable()->constrained()->onDelete('cascade'); // ربط بالفاريانت
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
