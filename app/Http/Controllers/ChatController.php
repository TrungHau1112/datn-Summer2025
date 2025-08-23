<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function handleChat(Request $request)
    {
        try {
            $userMessage = trim($request->input('message'));
            if (empty($userMessage)) {
                return response()->json(['response' => 'Vui lòng nhập câu hỏi nha! 😊'], 400);
            }

            // Xác định ý định của người dùng
            $intent = $this->determineIntent($userMessage);
            Log::info("User intent: $intent, Message: $userMessage");

            $response = '';
            $baseUrl = rtrim(config('app.url'), '/'); // Loại bỏ dấu / cuối nếu có

            if ($intent === 'product') {
                // Tìm kiếm sản phẩm
                $searchTerm = trim(preg_replace('/\b(tôi|muốn|mua|tìm|tim)\b|\?|,/i', '', $userMessage));
                $searchTerm = trim(preg_replace('/\s+/', ' ', $searchTerm));

                // Xử lý câu hỏi "Tìm iPhone"
                if (strtolower($userMessage) === 'tìm iphone') {
                    $searchTerm = 'iPhone';
                }

                if (empty($searchTerm) || strpos($searchTerm, 'tôi') !== false) {
                    $words = explode(' ', $userMessage);
                    $searchTerm = end($words);
                }

                Log::info("Processing product search for: $searchTerm 🛒");

                // Tìm danh mục
                $category = DB::table('categories')
                    ->where('name', 'like', "%$searchTerm%")
                    ->where('publish', 1)
                    ->first();

                $products = collect([]);
                $response .= "<p>Chào bạn! Bạn đang muốn tìm '<strong>$searchTerm</strong>' đúng không? Dưới đây là các sản phẩm liên quan: 🛒</p><ul>";

                // Tìm sản phẩm theo danh mục
                if ($category) {
                    $products = DB::table('products')
                        ->join('category_product', 'products.id', '=', 'category_product.product_id')
                        ->where('category_product.category_id', $category->id)
                        ->where('products.publish', 1)
                        ->select('products.id', 'products.name', 'products.price', 'products.thumbnail as image_url', 'products.slug')
                        ->get();
                    Log::info("Products found in category '$searchTerm': " . json_encode($products));
                }

                // Nếu không tìm thấy theo danh mục, tìm theo tên sản phẩm
                if ($products->isEmpty()) {
                    $products = DB::table('products')
                        ->where('name', 'like', "%$searchTerm%")
                        ->where('publish', 1)
                        ->select('products.id', 'products.name', 'products.price', 'products.thumbnail as image_url', 'products.slug')
                        ->get();
                    Log::info("Products found by name '$searchTerm': " . json_encode($products));
                }

                // Xử lý thông tin sản phẩm
                $products = $products->map(function ($product) use ($baseUrl) {
                    $variants = DB::table('product_variants')
                        ->join('product_variant_attribute', 'product_variants.id', '=', 'product_variant_attribute.product_variant_id')
                        ->join('attributes', 'product_variant_attribute.attribute_id', '=', 'attributes.id')
                        ->join('attribute_category', 'attributes.attribute_category_id', '=', 'attribute_category.id')
                        ->where('product_variants.product_id', $product->id)
                        ->select('attributes.value', 'attribute_category.name as category_name')
                        ->get();

                    $variantInfo = $variants->map(function ($variant) {
                        return "{$variant->category_name}: {$variant->value}";
                    })->implode(', ');

                    // Xử lý image_url
                    $originalThumbnail = $product->image_url ?? '';
                    Log::debug("Processing product '{$product->name}': Original thumbnail = '$originalThumbnail'");

                    if (!empty($originalThumbnail)) {
                        if (str_starts_with($originalThumbnail, 'http://') || str_starts_with($originalThumbnail, 'https://')) {
                            $product->image_url = $originalThumbnail;
                        } else {
                            $product->image_url = $baseUrl . '/' . ltrim($originalThumbnail, '/');
                        }
                    } else {
                        $product->image_url = 'https://via.placeholder.com/150';
                    }

                    Log::debug("Product '{$product->name}': Final image_url = '{$product->image_url}'");

                    $product->variant_info = $variantInfo ?: 'Không có biến thể';
                    return $product;
                });

                // Nếu không tìm thấy sản phẩm
                if ($products->isEmpty()) {
                    $response = "<p>Không tìm thấy sản phẩm '$searchTerm'. Vui lòng thử lại với các từ khóa như iPhone, Samsung, áo thun, hoặc liên hệ hỗ trợ nha! 😊</p>";
                } else {
                    foreach ($products as $product) {
                        $productUrl = url("/san-pham/{$product->slug}");
                        $response .= "<li>";
                        $response .= "<strong>Sản phẩm: {$product->name}</strong> ({$product->variant_info})<br>";
                        $response .= "Giá: " . number_format($product->price, 0, ',', '.') . " VNĐ<br>";
                        $response .= "<img src='{$product->image_url}' alt='{$product->name}' style='max-width: 100px; height: auto; margin: 10px 0; display: block;' /><br>";
                        $response .= "<a href='{$productUrl}' target='_blank' class='product-link'>Xem chi tiết sản phẩm</a>";
                        $response .= "</li>";
                    }
                    $response .= "</ul>";
                }

                // Thêm thông tin khuyến mãi
                $response .= "<p><strong>Chương trình khuyến mãi hiện tại: 🎁</strong><br>";
                $response .= "- Giao hàng miễn phí cho đơn hàng trên 10 triệu VNĐ.<br>";
                $response .= "- Hỗ trợ 24/7 qua hotline hoặc chat.<br>";
                $response .= "- Chính sách đổi trả miễn phí trong 30 ngày cho đơn hàng trên 200.000 VNĐ.<br>";
                $response .= "Bạn có muốn mình tư vấn thêm về sản phẩm nào không? 😊</p>";

            } elseif ($intent === 'post') {
                // Tìm kiếm bài viết
                $searchTerm = trim(preg_replace('/\b(tôi|muốn|tìm|tim|đọc|bài viết|bai viet)\b|\?|,/i', '', $userMessage));
                $searchTerm = trim(preg_replace('/\s+/', ' ', $searchTerm));

                // Xử lý câu hỏi "Bài viết về thời trang"
                if (strtolower($userMessage) === 'bài viết về thời trang') {
                    $searchTerm = 'thời trang';
                }

                Log::info("Processing post search for: $searchTerm 📝");

                $posts = DB::table('posts')
                    ->where('title', 'like', "%$searchTerm%")
                    ->orWhere('content', 'like', "%$searchTerm%")
                    ->orWhereHas('category', fn($query) => $query->where('name', 'like', "%$searchTerm%"))
                    ->where('publish', 1)
                    ->select('id', 'title', 'thumbnail', 'slug')
                    ->get();

                Log::info("Posts found for '$searchTerm': " . json_encode($posts));

                if ($posts->isEmpty()) {
                    $response = "<p>Không tìm thấy bài viết liên quan đến '$searchTerm'. Vui lòng thử lại với từ khóa khác hoặc liên hệ hỗ trợ nha! 😊</p>";
                } else {
                    $response = "<p>Chào bạn! Dưới đây là các bài viết liên quan đến '<strong>$searchTerm</strong>': 📝</p><ul>";
                    foreach ($posts as $post) {
                        $postUrl = url("/bai-viet/{$post->slug}");
                        $thumbnail = $post->thumbnail
                            ? (str_starts_with($post->thumbnail, 'http') ? $post->thumbnail : $baseUrl . '/' . ltrim($post->thumbnail, '/'))
                            : 'https://via.placeholder.com/150';
                        Log::debug("Post '{$post->title}': Final thumbnail = '$thumbnail'");
                        $response .= "<li>";
                        $response .= "<strong>Bài viết: {$post->title}</strong><br>";
                        $response .= "<img src='{$thumbnail}' alt='{$post->title}' style='max-width: 100px; height: auto; margin: 10px 0; display: block;' /><br>";
                        $response .= "<a href='{$postUrl}' target='_blank' class='product-link'>Xem bài viết</a>";
                        $response .= "</li>";
                    }
                    $response .= "</ul>";
                    $response .= "<p>Bạn có muốn tìm thêm bài viết nào khác không? 😊</p>";
                }

            } elseif ($intent === 'promotion') {
                // Xử lý câu hỏi "Khuyến mãi hiện tại"
                $response = "<p>Chào bạn! Hiện tại, cửa hàng đang có các chương trình khuyến mãi sau: 🎁</p><ul>";
                $response .= "<li>Giao hàng miễn phí cho đơn hàng trên 10 triệu VNĐ.</li>";
                $response .= "<li>Giảm giá iPhone 16 Series lên đến 4,5 triệu (đến 31/10/2025).</li>";
                $response .= "<li>Tặng balo Targus thời trang khi mua MacBook Air (đến 31/10/2025).</li>";
                $response .= "</ul>";
                $response .= "<p>Bạn có muốn mình tìm thêm chi tiết về khuyến mãi cho sản phẩm cụ thể không? 😊</p>";
                Log::info("Promotion response sent 🎉");

            } else {
                // Xử lý câu hỏi chung bằng API Gemini
                $contextData = $this->fetchContextData($intent, $userMessage);
                $prompt = $this->buildPrompt($userMessage, $contextData);
                $response = $this->callGeminiApi($prompt);
                // Thêm lời kêu gọi hành động
                $response .= "<p>Bạn có muốn mình tìm sản phẩm, bài viết, hay giải đáp thêm gì không? 😊</p>";
            }

            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            Log::error("ChatController error: " . $e->getMessage());
            return response()->json(['response' => 'Có lỗi xảy ra. Vui lòng thử lại nha! 😓'], 500);
        }
    }

    private function determineIntent($message)
    {
        // Xác định ý định dựa trên từ khóa
        $message = strtolower($message);
        if ($message === 'tìm iphone') {
            return 'product';
        } elseif ($message === 'bài viết về thời trang') {
            return 'post';
        } elseif ($message === 'khuyến mãi hiện tại') {
            return 'promotion';
        } elseif (preg_match('/muốn mua|tìm mua|muốn tìm|tim mua|sản phẩm|san pham|iphone|samsung|xiaomi|oppo|vivo|realme|nokia|huawei|phone|di động|mobile/i', $message)) {
            return 'product';
        } elseif (preg_match('/bài viết|bai viet|đọc bài|doc bai|tin tức|tin tuc|thời trang/i', $message)) {
            return 'post';
        }
        return 'general';
    }

    private function fetchContextData($intent, $message)
    {
        // Lấy ngữ cảnh nếu cần (ví dụ: lịch sử trò chuyện, thông tin người dùng)
        return [
            'user_message' => $message,
            'site_context' => 'Bạn là trợ lý của một trang thương mại điện tử, hỗ trợ tìm kiếm sản phẩm, bài viết và giải đáp câu hỏi chung.'
        ];
    }

    private function buildPrompt($message, $contextData)
    {
        // Tạo lời nhắc cho AI với ngữ cảnh tự nhiên
        $prompt = "Bạn là một trợ lý AI thân thiện trên một trang thương mại điện tử tại Việt Nam. Nhiệm vụ của bạn là trả lời câu hỏi một cách tự nhiên, gần gũi, bằng tiếng Việt, và đúng ngữ cảnh. Nếu câu hỏi không rõ, hãy yêu cầu người dùng giải thích thêm. Dưới đây là câu hỏi từ người dùng: '$message'. Hãy trả lời ngắn gọn, thân thiện và đúng trọng tâm. Nếu cần, gợi ý người dùng tìm sản phẩm hoặc bài viết liên quan.";
        return $prompt;
    }

    private function callGeminiApi($prompt)
    {
        // Gọi API Gemini
        $apiKey = env('GEMINI_API_KEY');
        $apiUrl = env('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($apiUrl . "?key=$apiKey", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 500,
                    'temperature' => 0.7,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, mình chưa hiểu rõ câu hỏi. Bạn có thể nói rõ hơn không? 😊';
                // Loại bỏ ký tự xuống dòng hoặc ký tự đặc biệt không mong muốn
                $text = str_replace(["\n", "\r"], ' ', trim($text));
                return $text;
            } else {
                Log::warning("Gemini API error: " . $response->body());
                return 'Xin lỗi, mình chưa hiểu rõ câu hỏi. Bạn có thể nói rõ hơn không? 😊';
            }
        } catch (\Exception $e) {
            Log::error("Gemini API call failed: " . $e->getMessage());
            return 'Xin lỗi, mình chưa hiểu rõ câu hỏi. Bạn có thể nói rõ hơn không? 😊';
        }
    }
}