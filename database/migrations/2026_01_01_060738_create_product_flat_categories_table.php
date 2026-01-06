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
        Schema::create('product_flat_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Catalog\ProductFlat::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Catalog\ProductCategory::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_flat_categories');
    }
};
