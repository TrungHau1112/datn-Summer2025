<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Giả sử bạn đã có 1 user

        $posts = [
            [
                'title' => 'Xu hướng thời trang hè 2025',
                'excerpt' => 'Khám phá những xu hướng nổi bật mùa hè năm nay.',
                'content' => 'Mùa hè 2025 mang đến làn gió mới với các thiết kế mát mẻ, tông màu pastel và chất liệu thân thiện với môi trường...',
                'thumbnail' => 'https://www.elle.vn/wp-content/uploads/2016/06/23/Meo-thoi-trang-mua-he-ma-nang-nao-cung-nen-biet-9.jpg',
            ],
            [
                'title' => 'Cách phối đồ đi làm thanh lịch',
                'excerpt' => 'Gợi ý cách chọn trang phục vừa chuyên nghiệp vừa thời trang.',
                'content' => 'Để vừa lịch sự mà vẫn nổi bật nơi công sở, bạn có thể chọn sơ mi trắng kết hợp quần tây, váy bút chì và phụ kiện nhẹ nhàng...',
                'thumbnail' => 'https://cdn.eva.vn/upload/2-2019/images/2019-04-12/ch190307_b08--1--1555040099-572-width600height765.jpg',
            ],
            [
                'title' => 'Top 5 phụ kiện không thể thiếu năm nay',
                'excerpt' => 'Túi xách, kính mát và nhiều phụ kiện đang được săn đón.',
                'content' => 'Năm 2025 chứng kiến sự trở lại mạnh mẽ của các phụ kiện như kính mắt tròn, túi đeo chéo mini, khuyên tai hình học...',
                'thumbnail' => 'https://down-vn.img.susercontent.com/file/cn-11134207-7ras8-m89z0zk6ftzm3b',
            ],
            [
                'title' => 'Phong cách tối giản: Ít mà chất',
                'excerpt' => 'Tối giản không nhàm chán nếu bạn biết cách phối.',
                'content' => 'Từ Nhật Bản đến châu Âu, phong cách thời trang tối giản vẫn giữ được chỗ đứng nhờ sự tinh tế và linh hoạt...',
                'thumbnail' => 'https://tuixachsieucap.com.vn/wp-content/uploads/2022/12/phong-cach-thoi-trang-toi-gian-19-853x1024.jpg',
            ],
            [
                'title' => 'Lựa chọn giày phù hợp cho từng dáng người',
                'excerpt' => 'Chọn đúng đôi giày sẽ giúp bạn tôn dáng hơn.',
                'content' => 'Nếu bạn có chiều cao khiêm tốn, hãy ưu tiên các mẫu giày mũi nhọn hoặc cao gót 5-7cm. Người cao nên thử giày đế bằng, sneaker trắng...',
                'thumbnail' => 'https://pos.nvncdn.com/80c639-72864/ps/20221117_G2MS1ZMNvAWqT87SbdKSwi4d.jpg',
            ],
        ];

        foreach ($posts as $post) {
            Post::create([
                'user_id' => $user->id,
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'thumbnail' => $post['thumbnail'],
                'publish' => 2,
                'published_at' => now(),
            ]);
        }
    }
}
