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
        Schema::create('ms_shipments', function (Blueprint $table) {
            $table->id('shipment_id');
            $table->dateTime('shipment_date_start', precision: 0);
            $table->dateTime('shipment_date_end', precision: 0)->nullable();
            $table->string('shipment_recipient_name', length: 200)->nullable();
            $table->enum('shipment_status', ['In Progress', 'Delivered', 'Cancelled']);
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('transaction_id')->on('transaction_headers')->onDelete('restrict');
            $table->foreign('courier_id')->references('courier_id')->on('ms_couriers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_shipments');
    }
};
