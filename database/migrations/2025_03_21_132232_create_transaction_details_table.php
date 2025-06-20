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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('unit_price_at_buy', total: 12, places: 2);
            $table->timestamps();

            $table->primary(['transaction_id', 'product_id']);

            $table->foreign('transaction_id')->references('transaction_id')->on('transaction_headers')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('ms_products')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
