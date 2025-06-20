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
        Schema::create('ms_categories', function (Blueprint $table) {
            $table->id('category_id')->unique();
            $table->enum('category_name', ['Foods', 'Beverages', 'Household Supplies', 'Health Supplies', 'Kitchen Supplies', 'Fresh Products']);
            $table->string('category_slug', length: 200)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_categories');
    }
};
