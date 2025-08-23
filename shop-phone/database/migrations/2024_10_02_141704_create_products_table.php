<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string( 'short_content')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('price', 12, 0);
            $table->decimal('discount', 12, 0);
            $table->string('thumbnail')->default('https://img.muji.net/img/item/4550583440404_1260.jpg');
            $table->json('albums')->nullable(); 
            $table->boolean('publish')->default(2);
            $table->boolean('is_featured')->default(2);
            $table->boolean('has_attribute')->default(2);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->softDeletes();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};