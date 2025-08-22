<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thêm giá trị mặc định cho trường total
            $table->decimal('total', 15, 0)->default(0)->change();
        });
        
        // Cập nhật dữ liệu hiện có
        DB::statement("UPDATE orders SET cart = '[]' WHERE cart IS NULL OR cart = ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback - loại bỏ giá trị mặc định
            $table->decimal('total', 15, 0)->change();
            $table->json('cart')->change();
        });
    }
};
