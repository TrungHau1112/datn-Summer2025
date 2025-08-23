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
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id')->nullable(); 
            // $table->string('session_id', 255)->nullable(); 
            // $table->unsignedBigInteger('product_id'); 
            // $table->unsignedBigInteger('collection_id'); 
            // $table->string('sku')->unique();
            $table->string('sku'); 
            $table->integer('quantity')->default(1); 
            // $table->decimal('price', 12, 0);
            // $table->decimal('total_price', 10, 2); 
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); 
            // $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
