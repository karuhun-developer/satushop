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
        Schema::create('transaction_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Transaction\Transaction::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Transaction\TransactionShop::class)->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('date')->nullable();
            $table->boolean('is_arrived')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_shipments');
    }
};
