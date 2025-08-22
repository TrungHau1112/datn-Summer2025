<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('province_id')->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci');
            $table->string('district_id')->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci');
            $table->string('ward_id')->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci');
            $table->string('address');
            $table->text('note')->nullable();
            $table->json('cart');
            $table->decimal('total', 15, 0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status')->default('pending');
            $table->tinyInteger('payment_status')->default(2); // 2 = chưa thanh toán, 1 = đã thanh toán
            $table->decimal('fee_ship', 15, 0)->nullable();
            $table->timestamps();
            

            $table->foreign('province_id')->references('code')->on('provinces');
            $table->foreign('district_id')->references('code')->on('districts');
            $table->foreign('ward_id')->references('code')->on('wards');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
