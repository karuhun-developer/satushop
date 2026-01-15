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
        Schema::create('transaction_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Transaction\Transaction::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Shop\Shop::class)->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->nullable()->unique();
            $table->string('courier')->nullable();
            $table->json('shipment_details')->nullable();
            $table->json('shipment_parameters')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->boolean('shipping_status')->default(0)->comment('0: pending, 1: shipped, 2: delivered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_shops');
    }
};
