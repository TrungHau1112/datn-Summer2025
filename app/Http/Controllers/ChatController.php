<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    public function handleChat(Request $request)
    {
        try {
            $userMessage = trim($request->input('message'));
            if (empty($userMessage)) {
                return response()->json(['response' => 'Vui lòng nhập câu hỏi nha! 😊'], 400);
            }

            // Chuẩn hóa và mở rộng ngôn ngữ (slang/từ viết tắt) để hiểu tốt hơn
            $normalizedMessage = $this->normalizeMessage($userMessage);
            $expandedMessage = $this->expandSlangAndSynonyms($normalizedMessage);

            // Xác định ý định của người dùng từ câu đã chuẩn hóa
            $intent = $this->determineIntent($expandedMessage);
            Log::info("User intent: $intent, Message: $userMessage");

            $response = '';
            $baseUrl = rtrim(config('app.url'), '/'); // Loại bỏ dấu / cuối nếu có
            $entities = $this->extractEntities($expandedMessage, $userMessage);

            // Gợi ý theo ngữ cảnh trước đó khi người dùng nhắn ngắn gọn (ví dụ: "nổi bật", "tin tức")
            $lastIntent = Session::get('chat.last_intent');
            if (in_array($expandedMessage, ['nổi bật', 'noi bat', 'bán chạy', 'ban chay', 'hot']) && $lastIntent) {
                $intent = $lastIntent;
            }

            if ($intent === 'product') {
                // Tìm kiếm sản phẩm
                // Ưu tiên thực thể đã nhận diện (model/brand/category)
                $searchTerm = $entities['model']
                    ?? $entities['brand']
                    ?? $entities['category']
                    ?? trim(preg_replace('/\b(tôi|toi|mình|minh|muốn|muon|mua|tìm|tim|cần|can|kiếm|kiem|xem)\b|\?|,/i', '', $userMessage));
                $searchTerm = trim(preg_replace('/\s+/', ' ', $searchTerm));

                // Trường hợp người dùng muốn xem tất cả/không rõ từ khóa/đề xuất nổi bật
                $isGeneric = preg_match('/\b(tất cả|tat ca|tatca|toàn bộ|toan bo|all|everything|tổng hợp|tong hop|nổi bật|noi bat|hot|bán chạy|ban chay|gợi ý|goi y|đề xuất|de xuat|recommend|suggest)\b/ui', $expandedMessage);

                // Tách khoảng giá nếu có: "tầm 10tr", "dưới 5 triệu", "8-12tr"
                $priceRange = $this->extractPriceRange($expandedMessage);

                // Xử lý câu hỏi "Tìm iPhone"
                if (strtolower($userMessage) === 'tìm iphone') {
                    $searchTerm = 'iPhone';
                }

                if (empty($searchTerm) || strpos($searchTerm, 'tôi') !== false) {
                    $words = explode(' ', $userMessage);
                    $searchTerm = end($words);
                }

                Log::info("Processing product search for: $searchTerm 🛒");

                // Nếu chung chung -> lấy danh sách sản phẩm nổi bật/gần đây
                if ($isGeneric || empty($searchTerm) || strtolower($searchTerm) === 'sản phẩm') {
                    $products = $this->getTopProducts();
                    $response .= "<p>Mình gợi ý một số sản phẩm đang được quan tâm: 🛒</p><ul>";
                } else {
                    // Tìm danh mục
                    $category = DB::table('categories')
                        ->where('name', 'like', "%$searchTerm%")
                        ->where('publish', 1)
                        ->first();

                    $products = collect([]);
                    $response .= "<p>Chào bạn! Bạn đang muốn tìm '<strong>$searchTerm</strong>' đúng không? Dưới đây là các sản phẩm liên quan: 🛒</p><ul>";

                    // Tìm sản phẩm theo danh mục
                    if ($category) {
                        try {
                            $query = DB::table('products')
                                ->join('category_product', 'products.id', '=', 'category_product.product_id')
                                ->where('category_product.category_id', $category->id)
                                ->where('products.publish', 1)
                                ->select('products.id', 'products.name', 'products.price', 'products.thumbnail as image_url', 'products.slug');
                            if ($priceRange) { $query = $this->applyPriceRange($query, $priceRange); }
                            $products = $query->get();
                            Log::info("Products found in category '$searchTerm': " . json_encode($products));
                        } catch (\Exception $e) {
                            Log::warning('Category product query failed: ' . $e->getMessage());
                        }
                    }

                    // Nếu không tìm thấy theo danh mục, tìm theo tên sản phẩm
                    if ($products->isEmpty()) {
                        try {
                            $query = DB::table('products')
                                ->where('name', 'like', "%$searchTerm%")
                                ->where('publish', 1)
                                ->select('products.id', 'products.name', 'products.price', 'products.thumbnail as image_url', 'products.slug');
                            if ($priceRange) { $query = $this->applyPriceRange($query, $priceRange); }
                            $products = $query->get();
                            Log::info("Products found by name '$searchTerm': " . json_encode($products));
                        } catch (\Exception $e) {
                            Log::warning('Name product query failed: ' . $e->getMessage());
                        }
                    }
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
                    // Fallback: gợi ý top sản phẩm và hướng dẫn thêm
                    $suggested = $this->getTopProducts();
                    if ($suggested->isNotEmpty()) {
                        $response = "<p>Chưa thấy sản phẩm phù hợp với '<strong>$searchTerm</strong>'. Mình gợi ý vài sản phẩm nổi bật: </p><ul>";
                        $products = $suggested; // tái sử dụng render bên dưới
                    } else {
                        $response = "<p>Không tìm thấy sản phẩm '<strong>$searchTerm</strong>'. Bạn thử mô tả khác (vd: 'iphone 13', 'samsung tầm 10tr') hoặc hỏi mình nhé! 😊</p>";
                    }
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
                $searchTerm = $entities['topic']
                    ?? trim(preg_replace('/\b(tôi|toi|mình|minh|muốn|muon|tìm|tim|đọc|doc|bài viết|bai viet|tin tức|tin tuc|xem tin|doc tin tuc|đọc tin tức)\b|\?|,/i', '', $userMessage));
                $searchTerm = trim(preg_replace('/\s+/', ' ', $searchTerm));

                // Xử lý câu hỏi "Bài viết về thời trang"
                if (strtolower($userMessage) === 'bài viết về thời trang') {
                    $searchTerm = 'thời trang';
                }

                Log::info("Processing post search for: $searchTerm 📝");

                // Tìm bài viết với Eloquent
                try {
                    $posts = \App\Models\Post::with('category')
                        ->where('publish', 1)
                        ->where(function ($query) use ($searchTerm) {
                            $query->where('title', 'like', "%$searchTerm%")
                                ->orWhere('content', 'like', "%$searchTerm%")
                                ->orWhereHas('category', function ($q) use ($searchTerm) {
                                    $q->where('name', 'like', "%$searchTerm%");
                                });
                        })
                        ->select('id', 'title', 'thumbnail', 'slug')
                        ->get();
                } catch (\Exception $e) {
                    Log::warning('Post query failed: ' . $e->getMessage());
                    $posts = collect([]);
                }

                // Nếu người dùng chỉ nói chung chung (tin tức/bài viết) hoặc không có từ khóa,
                // hiển thị các bài viết mới nhất
                $isGenericPost = empty($searchTerm) || preg_match('/^(bài viết|bai viet|tin tức|tin tuc|đọc tin tức|doc tin tuc|xem tin)$/ui', trim($expandedMessage));
                if ($isGenericPost) {
                    $posts = $this->getTopPosts();
                }

                Log::info("Posts found for '$searchTerm': " . json_encode($posts));

                if ($posts->isEmpty()) {
                    // Fallback: lấy các bài viết mới nhất khi không có kết quả
                    $fallbackPosts = $this->getTopPosts();
                    if ($fallbackPosts->isNotEmpty()) {
                        $posts = $fallbackPosts;
                        $response = "<p>Mình gửi bạn các bài viết mới nhất: 📝</p><ul>";
                    } else {
                        $response = "<p>Không tìm thấy bài viết liên quan đến '$searchTerm'. Vui lòng thử lại với từ khóa khác hoặc liên hệ hỗ trợ nha! 😊</p>";
                    }
                } else {
                    // Nếu là yêu cầu chung chung (tin tức/bài viết), đổi tiêu đề phù hợp
                    $titleText = ($isGenericPost)
                        ? "<p>Chào bạn! Dưới đây là các bài viết mới/đáng chú ý: 📝</p><ul>"
                        : "<p>Chào bạn! Dưới đây là các bài viết liên quan đến '<strong>$searchTerm</strong>': 📝</p><ul>";
                    $response = $titleText
;                }

                if ($posts->isEmpty()) {
                    $response = "<p>Không tìm thấy bài viết liên quan đến '$searchTerm'. Vui lòng thử lại với từ khóa khác hoặc liên hệ hỗ trợ nha! 😊</p>";
                } else {
                    foreach ($posts as $post) {
                        $rawSlug = $post->slug ?? '';
                        $cleanSlug = $rawSlug;
                        if (preg_match('/\.html$/i', $cleanSlug)) {
                            $cleanSlug = preg_replace('/\.html$/i', '', $cleanSlug);
                        }
                        if (preg_match('#^https?://#i', $rawSlug)) {
                            $postUrl = $rawSlug;
                        } elseif (str_starts_with($rawSlug, '/')) {
                            $postUrl = $baseUrl . '/' . ltrim($rawSlug, '/');
                        } else {
                            $postUrl = url("/tin-tuc/{$cleanSlug}");
                        }
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
            } elseif ($intent === 'greeting') {
                $response = "<p>Chào bạn 👋 Mình có thể giúp bạn:</p><ul>"
                    . "<li>Tìm sản phẩm theo thương hiệu/model (vd: iPhone 13, Samsung S24)</li>"
                    . "<li>Xem bài viết/tin tức</li>"
                    . "<li>Hỏi về khuyến mãi, vận chuyển, đổi trả</li>"
                    . "</ul><p>Bạn muốn bắt đầu với sản phẩm nào không? 😊</p>";
            } elseif ($intent === 'help') {
                $response = "<p>Đây là vài ví dụ để bạn thử:</p><ul>"
                    . "<li>\"mua iphone 13 pro max\"</li>"
                    . "<li>\"bài viết đánh giá macbook\"</li>"
                    . "<li>\"khuyến mãi hiện tại\"</li>"
                    . "</ul><p>Bạn cứ nhắn tự nhiên, mình hiểu tiếng Việt đời thường nha! 😉</p>";
            } elseif ($intent === 'promotion') {
                $response = "<p><strong>Khuyến mãi hiện tại: 🎁</strong><br>"
                    . "- Giao hàng miễn phí cho đơn hàng trên 10 triệu VNĐ.<br>"
                    . "- Giảm thêm 5% khi thanh toán qua VNPAY/Momo.<br>"
                    . "- Đổi trả miễn phí trong 30 ngày cho đơn từ 200.000 VNĐ.<br>"
                    . "Bạn muốn mình gợi ý sản phẩm đang giảm giá không? 😊</p>";
            } elseif ($intent === 'shipping') {
                $response = "<p><strong>Vận chuyển & giao hàng 🚚</strong></p>"
                    . "<ul>"
                    . "<li>Thời gian: Nội thành 1-2 ngày, tỉnh 2-5 ngày.</li>"
                    . "<li>Phí: Miễn phí cho đơn ≥ 10.000.000 VNĐ, còn lại tính theo địa chỉ.</li>"
                    . "<li>Đơn đã tạo sẽ có mã theo dõi gửi qua email/SMS.</li>"
                    . "<li>Hỗ trợ đồng kiểm khi nhận hàng.</li>"
                    . "</ul><p>Bạn cần mình tính phí ship cho địa chỉ của bạn không? 😊</p>";
            } elseif ($intent === 'returns') {
                $response = "<p><strong>Đổi trả & hoàn tiền 🔁</strong></p>"
                    . "<ul>"
                    . "<li>Đổi trả trong 30 ngày cho đơn từ 200.000 VNĐ.</li>"
                    . "<li>Sản phẩm còn nguyên tem/phiếu bảo hành/hóa đơn.</li>"
                    . "<li>Hoàn tiền trong 3-5 ngày làm việc sau khi duyệt.</li>"
                    . "</ul><p>Mình có thể kiểm tra đơn của bạn nếu bạn cho mình mã đơn nhé.</p>";
            } elseif ($intent === 'warranty') {
                $response = "<p><strong>Bảo hành 🔧</strong></p>"
                    . "<ul>"
                    . "<li>Thời hạn bảo hành theo từng sản phẩm/nhà sản xuất.</li>"
                    . "<li>Hỗ trợ 1 đổi 1 nếu lỗi do nhà sản xuất trong 7 ngày.</li>"
                    . "<li>Trung tâm tiếp nhận tại cửa hàng hoặc qua chuyển phát nhanh.</li>"
                    . "</ul><p>Bạn cho mình model/mã sản phẩm để tra thời hạn bảo hành nhé.</p>";
            } elseif ($intent === 'payment') {
                $response = "<p><strong>Thanh toán 💳</strong></p>"
                    . "<ul>"
                    . "<li>COD (nhận hàng trả tiền) – áp dụng toàn quốc.</li>"
                    . "<li>Chuyển khoản ngân hàng.</li>"
                    . "<li>Ví điện tử: MoMo, VNPAY (giảm thêm 5% khi có chương trình).</li>"
                    . "<li>Thẻ nội địa/quốc tế.</li>"
                    . "</ul><p>Bạn muốn thanh toán theo hình thức nào để mình hướng dẫn chi tiết?</p>";
            } elseif ($intent === 'installment') {
                $response = "<p><strong>Trả góp 0% 🧾</strong></p>"
                    . "<ul>"
                    . "<li>Kỳ hạn 3-12 tháng, đối tác tài chính/qua thẻ tín dụng.</li>"
                    . "<li>Yêu cầu: CCCD + số điện thoại + thẻ (nếu qua thẻ).</li>"
                    . "<li>Phí chuyển đổi theo ngân hàng, có thể 0% khi ưu đãi.</li>"
                    . "</ul><p>Bạn quan tâm model nào và kỳ hạn bao lâu?</p>";
            } elseif ($intent === 'contact') {
                $response = "<p><strong>Liên hệ hỗ trợ 📞</strong></p>"
                    . "<ul>"
                    . "<li>Hotline: 1900-xxx-xxx (8:00 – 21:00)</li>"
                    . "<li>Email: support@example.com</li>"
                    . "<li>Chat trực tuyến: ngay tại cửa sổ này 24/7</li>"
                    . "</ul><p>Bạn muốn mình gọi lại hay hỗ trợ qua chat luôn?</p>";
            } elseif ($intent === 'hours') {
                $response = "<p><strong>Giờ làm việc 🕒</strong></p>"
                    . "<ul><li>Thứ 2 – Chủ nhật: 8:00 – 21:00</li></ul>"
                    . "<p>Bạn muốn ghé cửa hàng vào khung giờ nào để mình chuẩn bị?</p>";
            } else {
                // Xử lý câu hỏi chung bằng API Gemini
                $contextData = $this->fetchContextData($intent, $userMessage, $entities);
                $prompt = $this->buildPrompt($userMessage, $contextData);
                $response = $this->callGeminiApi($prompt);
                // Thêm lời kêu gọi hành động
                $response .= "<p>Bạn có muốn mình tìm sản phẩm, bài viết, hay giải đáp thêm gì không? 😊</p>";
            }

            // Lưu ngữ cảnh cho lượt chat sau
            Session::put('chat.last_intent', $intent);
            Session::put('chat.last_message', $expandedMessage);
            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            Log::error("ChatController error: " . $e->getMessage());
            return response()->json(['response' => 'Có lỗi xảy ra. Vui lòng thử lại nha! 😓'], 500);
        }
    }

    private function determineIntent($message)
    {
        $message = strtolower($message);
        if ($message === 'tìm iphone') {
            return 'product';
        } elseif ($message === 'bài viết về thời trang') {
            return 'post';
        } elseif ($message === 'khuyến mãi hiện tại') {
            return 'promotion';
        } elseif (preg_match('/\b(hi|hello|helo|chao|chào|xin chào|alo|yo|hey|hola)\b/i', $message)) {
            return 'greeting';
        } elseif (preg_match('/\b(help|giup|giúp|hdsd|hướng dẫn|huong dan|how to|cách dùng|cach dung)\b/i', $message)) {
            return 'help';
        } elseif (preg_match('/vận chuyển|ship|giao hàng|phi ship|phí ship|shipping|giao nhanh/i', $message)) {
            return 'shipping';
        } elseif (preg_match('/đổi trả|doi tra|trả hàng|tra hang|hoàn tiền|hoan tien|return/i', $message)) {
            return 'returns';
        } elseif (preg_match('/bảo hành|bao hanh|warranty|bh/i', $message)) {
            return 'warranty';
        } elseif (preg_match('/thanh toán|thanh toan|payment|momo|vnpay|visa|master|atm/i', $message)) {
            return 'payment';
        } elseif (preg_match('/trả góp|tra gop|installment|0%|0 phan tram/i', $message)) {
            return 'installment';
        } elseif (preg_match('/liên hệ|lien he|hotline|support|hỗ trợ|ho tro|contact/i', $message)) {
            return 'contact';
        } elseif (preg_match('/giờ mở cửa|gio mo cua|giờ làm việc|gio lam viec|opening hours|open time/i', $message)) {
            return 'hours';
        } elseif (preg_match('/khuyến mãi|khuyen mai|giảm giá|giam gia|sale|freeship|voucher|mã giảm/i', $message)) {
            return 'promotion';
        } elseif (preg_match('/muốn mua|tìm mua|muốn tìm|tim mua|sản phẩm|san pham|iphone|samsung|xiaomi|oppo|vivo|realme|nokia|huawei|phone|điện thoại|dien thoai|di động|di dong|mobile|ipad|tablet|tai nghe|airpods|macbook|laptop/i', $message)) {
            return 'product';
        } elseif (preg_match('/bài viết|bai viet|đọc bài|doc bai|tin tức|tin tuc|thời trang/i', $message)) {
            return 'post';
        }
        return 'general';
    }

    private function fetchContextData($intent, $message, $entities = [])
    {
        return [
            'user_message' => $message,
            'site_context' => 'Bạn là trợ lý của một trang thương mại điện tử, hỗ trợ tìm kiếm sản phẩm, bài viết và giải đáp câu hỏi chung.',
            'intent' => $intent,
            'entities' => $entities,
        ];
    }

    private function buildPrompt($message, $contextData)
    {
        $intent = $contextData['intent'] ?? 'general';
        $entities = $contextData['entities'] ?? [];
        $hints = [];
        if (!empty($entities['brand'])) { $hints[] = "Thương hiệu: " . $entities['brand']; }
        if (!empty($entities['model'])) { $hints[] = "Model: " . $entities['model']; }
        if (!empty($entities['category'])) { $hints[] = "Danh mục: " . $entities['category']; }
        if (!empty($entities['topic'])) { $hints[] = "Chủ đề: " . $entities['topic']; }
        $hintText = empty($hints) ? '' : ("Gợi ý ngữ cảnh: " . implode('; ', $hints) . ". ");

        $prompt = "Bạn là một trợ lý AI thân thiện trên một trang thương mại điện tử tại Việt Nam. "
            . "Nhiệm vụ của bạn là trả lời tự nhiên, gần gũi, bằng tiếng Việt, đúng ngữ cảnh và chính xác. "
            . "Nếu câu hỏi chưa rõ, hãy hỏi lại để làm rõ. "
            . $hintText
            . "Ý định người dùng: $intent. "
            . "Câu hỏi từ người dùng: '$message'. "
            . "Hãy trả lời ngắn gọn, thân thiện, đúng trọng tâm. Nếu phù hợp, gợi ý tìm sản phẩm/bài viết liên quan.";
        return $prompt;
    }

    private function callGeminiApi($prompt)
    {
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

    // --------- Nâng cao hiểu ngôn ngữ tự nhiên ---------
    private function normalizeMessage($message)
    {
        $message = trim($message);
        // Chuẩn hóa khoảng trắng và ký tự
        $message = str_replace(["\u{00A0}", "\u{200B}"], ' ', $message);
        $message = preg_replace('/\s+/', ' ', $message);
        return $message;
    }

    private function expandSlangAndSynonyms($message)
    {
        $map = [
            // phủ định
            '/\b(k|ko|kh|khong|hong|hông|honk)\b/i' => 'không',
            // tắt/mở rộng phổ biến trong mua sắm
            '/\bmuon\b/i' => 'muốn',
            '/\btim\b/i' => 'tìm',
            '/\bcan\b/i' => 'cần',
            '/\bkiem\b/i' => 'kiếm',
            '/\bdt\b/i' => 'điện thoại',
            '/\bđt\b/i' => 'điện thoại',
            '/\bdtdd\b/i' => 'điện thoại',
            '/\bip\b/i' => 'iphone',
            '/\bss\b/i' => 'samsung',
            '/\bgg\b/i' => 'google',
            '/\bkm\b/i' => 'khuyến mãi',
            '/\bggiam\b/i' => 'giảm giá',
            '/\bmgg\b/i' => 'mã giảm',
            '/\bfreeship\b/i' => 'miễn phí vận chuyển',
            // danh mục phổ biến
            '/\bphu kien\b/i' => 'phụ kiện',
            '/\bop lung\b/i' => 'ốp lưng',
            '/\btai nghe\b/i' => 'tai nghe',
            '/\bin ear\b/i' => 'tai nghe',
            '/\bmac\b/i' => 'macbook',
        ];
        $expanded = $message;
        foreach ($map as $pattern => $replacement) {
            $expanded = preg_replace($pattern, $replacement, $expanded);
        }
        return $expanded;
    }

    private function extractEntities($normalizedMessage, $originalMessage)
    {
        $text = mb_strtolower($normalizedMessage);

        $brands = [
            'iphone', 'apple', 'samsung', 'xiaomi', 'oppo', 'vivo', 'realme', 'nokia', 'huawei',
            'asus', 'lenovo', 'dell', 'hp', 'acer', 'msi', 'sony', 'google', 'oneplus'
        ];
        $categories = ['điện thoại', 'di động', 'laptop', 'macbook', 'ipad', 'tablet', 'tai nghe', 'ốp lưng', 'phụ kiện'];

        $foundBrand = null;
        foreach ($brands as $brand) {
            if (preg_match('/\b' . preg_quote($brand, '/') . '\b/u', $text)) {
                $foundBrand = $brand;
                break;
            }
        }

        $foundCategory = null;
        foreach ($categories as $cat) {
            if (preg_match('/\b' . preg_quote($cat, '/') . '\b/u', $text)) {
                $foundCategory = $cat;
                break;
            }
        }

        // Model: cụm từ sau brand, ví dụ: "iphone 13 pro max", "samsung s24 ultra"
        $foundModel = null;
        if ($foundBrand) {
            if (preg_match('/' . preg_quote($foundBrand, '/') . '\s+([a-z0-9\s\-\+]{1,25})/ui', $text, $m)) {
                $candidate = trim($m[1]);
                // loại bỏ từ rác thường gặp
                $candidate = preg_replace('/\b(chính hãng|chinh hang|giá rẻ|gia re|cũ|cu|mới|moi)\b/ui', '', $candidate);
                $candidate = trim(preg_replace('/\s+/', ' ', $candidate));
                if (!empty($candidate)) {
                    $foundModel = $foundBrand . ' ' . $candidate;
                }
            }
        }

        // Topic cho bài viết
        $foundTopic = null;
        if (preg_match('/\b(thời trang|thủ thuật|đánh giá|so sánh|kinh nghiệm|mẹo|hướng dẫn)\b/ui', $text, $tm)) {
            $foundTopic = $tm[1];
        }

        return [
            'brand' => $foundBrand,
            'category' => $foundCategory,
            'model' => $foundModel,
            'topic' => $foundTopic,
        ];
    }

    private function getTopProducts()
    {
        try {
            // Ưu tiên sản phẩm publish, có giá và có thumbnail; sắp xếp theo mới nhất hoặc theo id desc
            $query = DB::table('products')
                ->orderByDesc('id')
                ->limit(8)
                ->select('id', 'name', 'price', 'thumbnail as image_url', 'slug');

            // thử publish trước, nếu rỗng sẽ bỏ điều kiện publish
            $products = (clone $query)->where('publish', 1)->get();
            if ($products->isEmpty()) {
                $products = $query->get();
            }
            return $products;
        } catch (\Exception $e) {
            Log::warning('getTopProducts failed: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    private function getTopPosts()
    {
        try {
            $query = \App\Models\Post::query()
                ->orderByDesc('id')
                ->limit(6)
                ->select('id', 'title', 'thumbnail', 'slug');
            $posts = (clone $query)->where('publish', 1)->get();
            if ($posts->isEmpty()) {
                $posts = $query->get();
            }
            return $posts;
        } catch (\Exception $e) {
            Log::warning('getTopPosts failed: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function extractPriceRange($text)
    {
        // Chuẩn hóa đơn vị: tr, triệu, vnd
        $normalized = preg_replace('/\./', '', $text); // bỏ dấu chấm trong số
        $normalized = str_replace([',', 'vnđ', 'vnd'], ['', '', ''], strtolower($normalized));

        // 8-12tr, 8-12 triệu
        if (preg_match('/(\d{1,3})\s*[-tođếnden]{1,3}\s*(\d{1,3})\s*(tr|triệu|trieu)?/u', $normalized, $m)) {
            $min = (int)$m[1] * 1000000;
            $max = (int)$m[2] * 1000000;
            return ['min' => $min, 'max' => $max];
        }
        // tầm 10tr, khoảng 15 triệu
        if (preg_match('/(tầm|tam|khoảng|khoang|cỡ|co)\s*(\d{1,3})\s*(tr|triệu|trieu)/u', $normalized, $m)) {
            $center = (int)$m[2] * 1000000;
            return ['min' => max(0, $center - 2000000), 'max' => $center + 2000000];
        }
        // dưới 5tr, dưới 7 triệu
        if (preg_match('/(dưới|duoi|<=|<)\s*(\d{1,3})\s*(tr|triệu|trieu)/u', $normalized, $m)) {
            $max = (int)$m[2] * 1000000;
            return ['min' => 0, 'max' => $max];
        }
        // trên 10tr, > 20 triệu
        if (preg_match('/(trên|tren|>=|>)\s*(\d{1,3})\s*(tr|triệu|trieu)/u', $normalized, $m)) {
            $min = (int)$m[2] * 1000000;
            return ['min' => $min, 'max' => null];
        }
        return null;
    }

    private function applyPriceRange($query, $range)
    {
        if (isset($range['min']) && $range['min'] !== null) {
            $query->where('products.price', '>=', $range['min']);
        }
        if (isset($range['max']) && $range['max'] !== null) {
            $query->where('products.price', '<=', $range['max']);
        }
        return $query;
    }

}
