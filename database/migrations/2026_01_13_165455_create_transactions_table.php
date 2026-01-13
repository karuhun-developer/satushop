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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->unsignedBigInteger('ref_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('postcode')->nullable();
            $table->unsignedBigInteger('rajaongkir_province_id')->nullable()->index();
            $table->unsignedBigInteger('rajaongkir_city_id')->nullable()->index();
            $table->unsignedBigInteger('rajaongkir_district_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->string('payment_method')->comment('manual, midtrans, tripay, etc.');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->boolean('status')->default(false)->comment('payment status: paid or unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
