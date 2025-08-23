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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('attribute_category_id');
            $table->foreign('attribute_category_id')->references('id')->on('attribute_category')->onDelete('cascade');
            $table->text('value');
            $table->boolean('publish')->default(2);
			$table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes_values');
    }
};
