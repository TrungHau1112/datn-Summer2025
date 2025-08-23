<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá (duy nhất)
            $table->string('title'); // Tiêu đề mã giảm giá
            $table->tinyInteger('discount_type'); // 1:percent, 2:money
            $table->decimal('discount_value', 12, 0); // Giá trị giảm
            $table->unsignedBigInteger('min_order_amount'); // Số tiền tối thiểu áp dụng mã
            $table->timestamp('start_date'); // Ngày bắt đầu
            $table->timestamp('end_date'); // Ngày kết thúc
            $table->tinyInteger('status')->default(1); // 1:publish, 2:unpublish
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_codes');
    }
}
