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
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('discount', 8, 2); // قيمة الخصم
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed'); // نوع الخصم
            $table->timestamp('start_date'); // تاريخ البدء
            $table->timestamp('end_date'); // تاريخ الانتهاء
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_discounts');
    }
};
