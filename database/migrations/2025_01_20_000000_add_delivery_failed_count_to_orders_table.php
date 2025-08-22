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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm trường đếm số lần giao hàng thất bại
            $table->integer('delivery_failed_count')->default(0)->after('is_bom');
            
            // Thêm timestamp cho lần giao hàng thất bại cuối cùng
            $table->timestamp('last_delivery_failed_at')->nullable()->after('delivery_failed_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_failed_count', 'last_delivery_failed_at']);
        });
    }
};
