<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;

class CollectionSeeder extends Seeder
{
    public function run()
    {
        Collection::create([
            'name' => 'Bộ sưu tập mùa hè',
            'slug' => 'bo-suu-tap-mua-he',
            'short_description' => 'Thiết kế và phong cách độc quyền cho mùa hè.',
            'description' => 'Đây là mô tả chi tiết về Bộ sưu tập mùa hè.',
            'discount' => 15.00, // Giảm giá 15%
            'thumbnail' => 'https://placehold.co/600x600?text=The Gioi \nNoi That',
            'publish' => 1, // Đã xuất bản
            'meta_title' => 'Bộ sưu tập mùa hè 2024',
            'meta_description' => 'Khám phá Bộ sưu tập mùa hè 2024 với các khuyến mãi và thiết kế mới.',
        ]);

        Collection::create([
            'name' => 'Bộ sưu tập mùa đông',
            'slug' => 'bo-suu-tap-mua-dong',
            'short_description' => 'Thiết kế mùa đông ấm áp và thời trang.',
            'description' => 'Đây là mô tả chi tiết về Bộ sưu tập mùa đông.',
            'discount' => 10.00, // Giảm giá 10%
            'thumbnail' => 'https://placehold.co/600x600?text=The Gioi \nNoi That',
            'publish' => 1, // Đã xuất bản
            'meta_title' => 'Bộ sưu tập mùa đông 2024',
            'meta_description' => 'Khám phá Bộ sưu tập mùa đông 2024 với các ưu đãi độc quyền về trang phục ấm áp.',
        ]);

        Collection::create([
            'name' => 'Bộ sưu tập mùa xuân',
            'slug' => 'bo-suu-tap-mua-xuan',
            'short_description' => 'Thời trang mùa xuân tươi mới và đầy màu sắc.',
            'description' => 'Đây là mô tả chi tiết về Bộ sưu tập mùa xuân.',
            'discount' => 5.00, // Giảm giá 5%
            'thumbnail' => 'https://placehold.co/600x600?text=The Gioi \nNoi That',
            'publish' => 2, // Chưa xuất bản
            'meta_title' => 'Bộ sưu tập mùa xuân 2024',
            'meta_description' => 'Xem trước Bộ sưu tập mùa xuân 2024 với các ưu đãi hấp dẫn.',
        ]);
    }
}
