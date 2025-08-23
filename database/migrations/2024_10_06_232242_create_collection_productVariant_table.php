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
        Schema::create('collection_productVariant', function (Blueprint $table) {
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->unsignedBigInteger('productVariant_id')->nullable();

            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->foreign('productVariant_id')->references('id')->on('product_variants')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_product');
    }
};
