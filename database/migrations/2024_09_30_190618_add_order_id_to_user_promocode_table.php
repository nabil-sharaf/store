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
        Schema::table('user_promocode', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // الاوردر الذي تم تطبيق الكوبون عليه
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_promocode', function (Blueprint $table) {
            //
        });
    }
};
