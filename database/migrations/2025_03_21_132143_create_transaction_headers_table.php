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
        Schema::create('transaction_headers', function (Blueprint $table) {
            $table->id('transaction_id')->unique();
            $table->dateTime('transaction_date', precision: 0);
            $table->enum('transaction_status', ['Pending', 'Processing', 'Out for Delivery', 'Shipped', 'Completed', 'Cancelled']);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_address_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('payment_method_id');
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('ms_customers')->onDelete('restrict');
            $table->foreign('customer_address_id')->references('customer_address_id')->on('ms_customer_addresses')->onDelete('restrict');
            $table->foreign('admin_id')->references('admin_id')->on('ms_admins')->onDelete('set null');
            $table->foreign('payment_method_id')->references('payment_method_id')->on('ms_payment_methods')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_headers');
    }
};
