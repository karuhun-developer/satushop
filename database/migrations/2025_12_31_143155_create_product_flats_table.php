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
        Schema::create('product_flats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Catalog\Product::class)->constrained()->cascadeOnDelete();
            $table->string('sku')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->json('meta_data')->nullable();
            $table->decimal('price', 12, 4)->default(0);
            $table->decimal('special_price', 12, 4)->nullable();
            $table->dateTime('special_price_start')->nullable();
            $table->dateTime('special_price_end')->nullable();
            $table->decimal('weight', 12, 4)->default(0);
            $table->decimal('length', 12, 4)->default(0);
            $table->decimal('width', 12, 4)->default(0);
            $table->decimal('height', 12, 4)->default(0);
            $table->decimal('diameter', 12, 4)->default(0);
            $table->unsignedBigInteger('stock')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->boolean('visible_individually')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_flats');
    }
};
