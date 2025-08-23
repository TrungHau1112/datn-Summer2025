<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountCodesSeeder extends Seeder
{
    public function run()
    {
        DB::table('discount_codes')->insert([
            [
                'code' => 'THEGIOINOITHAT',
                'title' => 'Giảm giá 10% cho đơn hàng trên 1 triệu',
                'discount_type' => 1, // percent
                'discount_value' => 10, // 10%
                'min_order_amount' => 1000000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FREESHIP100',
                'title' => 'Giảm 100k và miễn phí vận chuyển',
                'discount_type' => 2, // money
                'discount_value' => 100000, // 100,000 VND
                'min_order_amount' => 500000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(15),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HELLO20',
                'title' => 'Giảm 20% cho khách hàng mới',
                'discount_type' => 1, // percent
                'discount_value' => 20,
                'min_order_amount' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NOEL50',
                'title' => 'Giảm 50k cho đơn hàng dịp Noel',
                'discount_type' => 2, // money
                'discount_value' => 50000,
                'min_order_amount' => 200000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 2, // unpublished
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BLACKFRIDAY',
                'title' => 'Giảm giá 30% cho tất cả đơn hàng',
                'discount_type' => 1, // percent
                'discount_value' => 30,
                'min_order_amount' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(1),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
