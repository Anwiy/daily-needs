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
        Schema::create('ms_payment_methods', function (Blueprint $table) {
            $table->id('payment_method_id')->unique();
            $table->enum('payment_method_name', ['Bank Transfer', 'Cash on Delivery', 'Application Balance'])->unique();
            $table->string('payment_method_slug', length: 200)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_payment_methods');
    }
};
