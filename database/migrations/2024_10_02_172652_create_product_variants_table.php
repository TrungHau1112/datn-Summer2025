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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('code')->nullable();
            $table->string('title')->nullable();
            $table->decimal('price')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('thumbnail')->default('https://img.muji.net/img/item/4550583440404_1260.jpg');
            $table->json('albums')->nullable();
            $table->integer('publish')->default(1);
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
