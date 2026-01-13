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
            $table->id();
            $table->foreignIdFor(\App\Models\Transaction\Transaction::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Transaction\TransactionShop::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Catalog\ProductFlat::class)->constrained()->cascadeOnDelete();
            $table->json('product_details');
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('quantity')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->timestamps();
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
