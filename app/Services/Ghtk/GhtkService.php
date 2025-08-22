<?php

namespace App\Services\Ghtk;

use App\Models\GhtkApiToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GhtkService
{
    protected $baseUrl = 'https://services.giaohangtietkiem.vn';
    protected $apiToken;
    protected $headers;

    public function __construct()
    {
        $this->setActiveToken();
    }

    /**
     * Lấy token active từ database
     */
    protected function setActiveToken()
    {
        $token = GhtkApiToken::where('is_active', true)
            ->where(function($query) {
                $query->where('expires_at', '>', now())
                      ->orWhereNull('expires_at');
            })
            ->first();

        if ($token) {
            $this->apiToken = $token->api_token;
            $this->headers = [
                'Token' => $this->apiToken,
                'Content-Type' => 'application/json',
            ];
        } else {
            // Set headers mặc định để tránh lỗi null
            $this->headers = [
                'Content-Type' => 'application/json',
            ];
        }
    }

    /**
     * Kiểm tra kết nối API
     */
    public function testConnection()
    {
        // Kiểm tra xem có token active không
        if (!$this->hasActiveToken()) {
            Log::error('GHTK API: Không có token active');
            return false;
        }

        try {
            // Test với endpoint đơn giản hơn
            $response = Http::withHeaders($this->headers)
                ->get($this->baseUrl . '/services/shipment/v2/areas');

            if ($response->successful()) {
                Log::info('GHTK API test successful. Status: ' . $response->status());
                return true;
            } else {
                Log::error('GHTK API test failed. Status: ' . $response->status() . ', Body: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('GHTK API connection failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra xem có token active không
     */
    protected function hasActiveToken()
    {
        return !empty($this->apiToken);
    }

    /**
     * Refresh token từ database (khi cần thiết)
     */
    public function refreshToken()
    {
        $this->setActiveToken();
        return $this->hasActiveToken();
    }

    /**
     * Lấy danh sách tỉnh thành từ database
     */
    public function getProvinces()
    {
        try {
            // Lấy dữ liệu từ bảng provinces có sẵn
            $provinces = DB::table('provinces')->select('code as id', 'name')->get();
            
            return $provinces->map(function ($province) {
                return [
                    'id' => $province->id,
                    'name' => $province->name,
                    'code' => $province->id
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to get provinces from database: ' . $e->getMessage());
            return $this->getDefaultProvinces();
        }
    }

    /**
     * Lấy danh sách tỉnh thành mặc định của Việt Nam
     */
    protected function getDefaultProvinces()
    {
        return [
            ['id' => 1, 'name' => 'Hà Nội', 'code' => 'HANOI'],
            ['id' => 2, 'name' => 'Hồ Chí Minh', 'code' => 'HCM'],
            ['id' => 3, 'name' => 'Đà Nẵng', 'code' => 'DANANG'],
            ['id' => 4, 'name' => 'Hải Phòng', 'code' => 'HAIPHONG'],
            ['id' => 5, 'name' => 'Cần Thơ', 'code' => 'CANTHO'],
            ['id' => 6, 'name' => 'An Giang', 'code' => 'ANGIANG'],
            ['id' => 7, 'name' => 'Bà Rịa - Vũng Tàu', 'code' => 'BARIAVUNGTAU'],
            ['id' => 8, 'name' => 'Bắc Giang', 'code' => 'BACGIANG'],
            ['id' => 9, 'name' => 'Bắc Kạn', 'code' => 'BACKAN'],
            ['id' => 10, 'name' => 'Bạc Liêu', 'code' => 'BACLIEU'],
            ['id' => 11, 'name' => 'Bắc Ninh', 'code' => 'BACNINH'],
            ['id' => 12, 'name' => 'Bến Tre', 'code' => 'BENTRE'],
            ['id' => 13, 'name' => 'Bình Định', 'code' => 'BINHDINH'],
            ['id' => 14, 'name' => 'Bình Dương', 'code' => 'BINHDUONG'],
            ['id' => 15, 'name' => 'Bình Phước', 'code' => 'BINHPHUOC'],
            ['id' => 16, 'name' => 'Bình Thuận', 'code' => 'BINHTHUAN'],
            ['id' => 17, 'name' => 'Cà Mau', 'code' => 'CAMAU'],
            ['id' => 18, 'name' => 'Cao Bằng', 'code' => 'CAOBANG'],
            ['id' => 19, 'name' => 'Đắk Lắk', 'code' => 'DAKLAK'],
            ['id' => 20, 'name' => 'Đắk Nông', 'code' => 'DAKNONG'],
            ['id' => 21, 'name' => 'Điện Biên', 'code' => 'DIENBIEN'],
            ['id' => 22, 'name' => 'Đồng Nai', 'code' => 'DONGNAI'],
            ['id' => 23, 'name' => 'Đồng Tháp', 'code' => 'DONGTHAP'],
            ['id' => 24, 'name' => 'Gia Lai', 'code' => 'GIALAI'],
            ['id' => 25, 'name' => 'Hà Giang', 'code' => 'HAGIANG'],
            ['id' => 26, 'name' => 'Hà Nam', 'code' => 'HANAM'],
            ['id' => 27, 'name' => 'Hà Tĩnh', 'code' => 'HATINH'],
            ['id' => 28, 'name' => 'Hải Dương', 'code' => 'HAIDUONG'],
            ['id' => 29, 'name' => 'Hậu Giang', 'code' => 'HAUGIANG'],
            ['id' => 30, 'name' => 'Hòa Bình', 'code' => 'HOABINH'],
            ['id' => 31, 'name' => 'Hưng Yên', 'code' => 'HUNGYEN'],
            ['id' => 32, 'name' => 'Khánh Hòa', 'code' => 'KHANHHOA'],
            ['id' => 33, 'name' => 'Kiên Giang', 'code' => 'KIENGIANG'],
            ['id' => 34, 'name' => 'Kon Tum', 'code' => 'KONTUM'],
            ['id' => 35, 'name' => 'Lai Châu', 'code' => 'LAICHAU'],
            ['id' => 36, 'name' => 'Lâm Đồng', 'code' => 'LAMDONG'],
            ['id' => 37, 'name' => 'Lạng Sơn', 'code' => 'LANGSON'],
            ['id' => 38, 'name' => 'Lào Cai', 'code' => 'LAOCAI'],
            ['id' => 39, 'name' => 'Long An', 'code' => 'LONGAN'],
            ['id' => 40, 'name' => 'Nam Định', 'code' => 'NAMDINH'],
            ['id' => 41, 'name' => 'Nghệ An', 'code' => 'NGHEAN'],
            ['id' => 42, 'name' => 'Ninh Bình', 'code' => 'NINHBINH'],
            ['id' => 43, 'name' => 'Ninh Thuận', 'code' => 'NINHTHUAN'],
            ['id' => 44, 'name' => 'Phú Thọ', 'code' => 'PHUTHO'],
            ['id' => 45, 'name' => 'Phú Yên', 'code' => 'PHUYEN'],
            ['id' => 46, 'name' => 'Quảng Bình', 'code' => 'QUANGBINH'],
            ['id' => 47, 'name' => 'Quảng Nam', 'code' => 'QUANGNAM'],
            ['id' => 48, 'name' => 'Quảng Ngãi', 'code' => 'QUANGNGAI'],
            ['id' => 49, 'name' => 'Quảng Ninh', 'code' => 'QUANGNINH'],
            ['id' => 50, 'name' => 'Quảng Trị', 'code' => 'QUANGTRI'],
            ['id' => 51, 'name' => 'Sóc Trăng', 'code' => 'SOCTRANG'],
            ['id' => 52, 'name' => 'Sơn La', 'code' => 'SONLA'],
            ['id' => 53, 'name' => 'Tây Ninh', 'code' => 'TAYNINH'],
            ['id' => 54, 'name' => 'Thái Bình', 'code' => 'THAIBINH'],
            ['id' => 55, 'name' => 'Thái Nguyên', 'code' => 'THAINGUYEN'],
            ['id' => 56, 'name' => 'Thanh Hóa', 'code' => 'THANHHOA'],
            ['id' => 57, 'name' => 'Thừa Thiên Huế', 'code' => 'THUATHIENHUE'],
            ['id' => 58, 'name' => 'Tiền Giang', 'code' => 'TIENGIANG'],
            ['id' => 59, 'name' => 'Trà Vinh', 'code' => 'TRAVINH'],
            ['id' => 60, 'name' => 'Tuyên Quang', 'code' => 'TUYENQUANG'],
            ['id' => 61, 'name' => 'Vĩnh Long', 'code' => 'VINHLONG'],
            ['id' => 62, 'name' => 'Vĩnh Phúc', 'code' => 'VINHPHUC'],
            ['id' => 63, 'name' => 'Yên Bái', 'code' => 'YENBAI']
        ];
    }

    /**
     * Lấy danh sách quận huyện theo tỉnh từ database
     */
    public function getDistricts($provinceCode)
    {
        try {
            // Lấy dữ liệu từ bảng districts theo province_code
            $districts = DB::table('districts')
                ->where('province_code', $provinceCode)
                ->select('code as id', 'name')
                ->get();
            
            return $districts->map(function ($district) {
                return [
                    'id' => $district->id,
                    'name' => $district->name,
                    'code' => $district->id
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error("Failed to get districts for province {$provinceCode}: " . $e->getMessage());
            return $this->getDefaultDistricts($provinceCode);
        }
    }

    /**
     * Lấy danh sách quận huyện mặc định theo tỉnh
     */
    protected function getDefaultDistricts($provinceId)
    {
        $districts = [
            1 => [ // Hà Nội
                ['id' => 1, 'name' => 'Ba Đình', 'code' => 'BADINH'],
                ['id' => 2, 'name' => 'Hoàn Kiếm', 'code' => 'HOANKIEM'],
                ['id' => 3, 'name' => 'Tây Hồ', 'code' => 'TAYHO'],
                ['id' => 4, 'name' => 'Long Biên', 'code' => 'LONGBIEN'],
                ['id' => 5, 'name' => 'Cầu Giấy', 'code' => 'CAUGIAY'],
                ['id' => 6, 'name' => 'Đống Đa', 'code' => 'DONGDA'],
                ['id' => 7, 'name' => 'Hai Bà Trưng', 'code' => 'HAIBATRUNG'],
                ['id' => 8, 'name' => 'Hoàng Mai', 'code' => 'HOANGMAI'],
                ['id' => 9, 'name' => 'Thanh Xuân', 'code' => 'THANHXUAN'],
                ['id' => 10, 'name' => 'Sóc Sơn', 'code' => 'SOCSON'],
                ['id' => 11, 'name' => 'Đông Anh', 'code' => 'DONGANH'],
                ['id' => 12, 'name' => 'Gia Lâm', 'code' => 'GIALAM'],
                ['id' => 13, 'name' => 'Nam Từ Liêm', 'code' => 'NAMTULIEM'],
                ['id' => 14, 'name' => 'Thanh Trì', 'code' => 'THANHTRI'],
                ['id' => 15, 'name' => 'Bắc Từ Liêm', 'code' => 'BACTULIEM'],
                ['id' => 16, 'name' => 'Mê Linh', 'code' => 'MELINH'],
                ['id' => 17, 'name' => 'Hà Đông', 'code' => 'HADONG'],
                ['id' => 18, 'name' => 'Sơn Tây', 'code' => 'SONTAY'],
                ['id' => 19, 'name' => 'Ba Vì', 'code' => 'BAVI'],
                ['id' => 20, 'name' => 'Phúc Thọ', 'code' => 'PHUCTHO'],
                ['id' => 21, 'name' => 'Đan Phượng', 'code' => 'DANPHUONG'],
                ['id' => 22, 'name' => 'Hoài Đức', 'code' => 'HOAIDUC'],
                ['id' => 23, 'name' => 'Quốc Oai', 'code' => 'QUOCOAI'],
                ['id' => 24, 'name' => 'Thạch Thất', 'code' => 'THACHTHAT'],
                ['id' => 25, 'name' => 'Chương Mỹ', 'code' => 'CHUONGMY'],
                ['id' => 26, 'name' => 'Thanh Oai', 'code' => 'THANHOAI'],
                ['id' => 27, 'name' => 'Thường Tín', 'code' => 'THUONGTIN'],
                ['id' => 28, 'name' => 'Phú Xuyên', 'code' => 'PHUXUYEN'],
                ['id' => 29, 'name' => 'Ứng Hòa', 'code' => 'UNGHOA'],
                ['id' => 30, 'name' => 'Mỹ Đức', 'code' => 'MYDUC']
            ],
            2 => [ // Hồ Chí Minh
                ['id' => 31, 'name' => 'Quận 1', 'code' => 'QUAN1'],
                ['id' => 32, 'name' => 'Quận 2', 'code' => 'QUAN2'],
                ['id' => 33, 'name' => 'Quận 3', 'code' => 'QUAN3'],
                ['id' => 34, 'name' => 'Quận 4', 'code' => 'QUAN4'],
                ['id' => 35, 'name' => 'Quận 5', 'code' => 'QUAN5'],
                ['id' => 36, 'name' => 'Quận 6', 'code' => 'QUAN6'],
                ['id' => 37, 'name' => 'Quận 7', 'code' => 'QUAN7'],
                ['id' => 38, 'name' => 'Quận 8', 'code' => 'QUAN8'],
                ['id' => 39, 'name' => 'Quận 9', 'code' => 'QUAN9'],
                ['id' => 40, 'name' => 'Quận 10', 'code' => 'QUAN10'],
                ['id' => 41, 'name' => 'Quận 11', 'code' => 'QUAN11'],
                ['id' => 42, 'name' => 'Quận 12', 'code' => 'QUAN12'],
                ['id' => 43, 'name' => 'Tân Bình', 'code' => 'TANBINH'],
                ['id' => 44, 'name' => 'Bình Tân', 'code' => 'BINHTAN'],
                ['id' => 45, 'name' => 'Tân Phú', 'code' => 'TANPHU'],
                ['id' => 46, 'name' => 'Phú Nhuận', 'code' => 'PHUNHUAN'],
                ['id' => 47, 'name' => 'Gò Vấp', 'code' => 'GOVAP'],
                ['id' => 48, 'name' => 'Bình Thạnh', 'code' => 'BINHTHANH'],
                ['id' => 49, 'name' => 'Thủ Đức', 'code' => 'THUDUC'],
                ['id' => 50, 'name' => 'Củ Chi', 'code' => 'CUCHI'],
                ['id' => 51, 'name' => 'Hóc Môn', 'code' => 'HOCMON'],
                ['id' => 52, 'name' => 'Bình Chánh', 'code' => 'BINHCHANH'],
                ['id' => 53, 'name' => 'Nhà Bè', 'code' => 'NHABE'],
                ['id' => 54, 'name' => 'Cần Giờ', 'code' => 'CANGIO']
            ]
        ];

        return $districts[$provinceId] ?? [];
    }

    /**
     * Lấy danh sách phường xã theo quận huyện từ database
     */
    public function getWards($districtCode)
    {
        try {
            // Lấy dữ liệu từ bảng wards theo district_code
            $wards = DB::table('wards')
                ->where('district_code', $districtCode)
                ->select('code as id', 'name')
                ->get();
            
            return $wards->map(function ($ward) {
                return [
                    'id' => $ward->id,
                    'name' => $ward->name,
                    'code' => $ward->id
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error("Failed to get wards for district {$districtCode}: " . $e->getMessage());
            return $this->getDefaultWards($districtCode);
        }
    }

    /**
     * Lấy danh sách phường xã mặc định theo quận huyện
     */
    protected function getDefaultWards($districtId)
    {
        $wards = [
            5 => [ // Cầu Giấy - Hà Nội
                ['id' => 1, 'name' => 'Phường Dịch Vọng', 'code' => 'DICHVONG'],
                ['id' => 2, 'name' => 'Phường Dịch Vọng Hậu', 'code' => 'DICHVONGHAU'],
                ['id' => 3, 'name' => 'Phường Mai Dịch', 'code' => 'MAIDICH'],
                ['id' => 4, 'name' => 'Phường Nghĩa Đô', 'code' => 'NGHIADO'],
                ['id' => 5, 'name' => 'Phường Nghĩa Tân', 'code' => 'NGHIATAN'],
                ['id' => 6, 'name' => 'Phường Quan Hoa', 'code' => 'QUANHOA'],
                ['id' => 7, 'name' => 'Phường Trung Hòa', 'code' => 'TRUNGHOA'],
                ['id' => 8, 'name' => 'Phường Xuân La', 'code' => 'XUANLA'],
                ['id' => 9, 'name' => 'Phường Yên Hòa', 'code' => 'YENHOA']
            ],
            31 => [ // Quận 1 - Hồ Chí Minh
                ['id' => 10, 'name' => 'Phường Bến Nghé', 'code' => 'BENNGHE'],
                ['id' => 11, 'name' => 'Phường Bến Thành', 'code' => 'BENTHANH'],
                ['id' => 12, 'name' => 'Phường Cầu Kho', 'code' => 'CAUKHO'],
                ['id' => 13, 'name' => 'Phường Cầu Ông Lãnh', 'code' => 'CAUONGLANH'],
                ['id' => 14, 'name' => 'Phường Đa Kao', 'code' => 'DAKAO'],
                ['id' => 15, 'name' => 'Phường Nguyễn Cư Trinh', 'code' => 'NGUYENCUTRINH'],
                ['id' => 16, 'name' => 'Phường Nguyễn Thái Bình', 'code' => 'NGUYENTHAIBINH'],
                ['id' => 17, 'name' => 'Phường Phạm Ngũ Lão', 'code' => 'PHAMNGULAO'],
                ['id' => 18, 'name' => 'Phường Phú Nhuận', 'code' => 'PHUNHUAN'],
                ['id' => 19, 'name' => 'Phường Tân Định', 'code' => 'TANDINH']
            ]
        ];

        return $wards[$districtId] ?? [];
    }

    /**
     * Tính phí ship tự động
     */
    public function calculateShippingFee($pickupAddress, $deliveryAddress, $weight = 0.5, $value = 0)
    {
        // Kiểm tra xem có token active không
        if (!$this->hasActiveToken()) {
            Log::error('GHTK API: Không có token active để tính phí ship');
            return ['success' => false, 'message' => 'Chưa cấu hình API token GHTK. Vui lòng liên hệ admin.'];
        }

        try {
            $payload = [
                'pickup_province' => $pickupAddress['province'],
                'pickup_district' => $pickupAddress['district'],
                'delivery_province' => $deliveryAddress['province'],
                'delivery_district' => $deliveryAddress['district'],
                'weight' => $weight, // kg
                'value' => $value, // VND
                'transport' => 'road', // road, fly
                'deliver_option' => 'xteam', // xteam, cod
            ];

            $response = Http::withHeaders($this->headers)
                ->post($this->baseUrl . '/services/shipment/v2/rate', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    return [
                        'success' => true,
                        'fee' => $data['fee']['fee'],
                        'insurance_fee' => $data['fee']['insurance_fee'],
                        'total_fee' => $data['fee']['total_fee'],
                        'estimated_deliver_time' => $data['fee']['estimated_deliver_time'],
                        'delivery_type' => $data['fee']['delivery_type'],
                        'data' => $data['fee']
                    ];
                }
            }

            Log::error('GHTK shipping fee calculation failed: ' . $response->body());
            
            // Fallback: Tính phí ship mặc định dựa trên khoảng cách
            return $this->calculateDefaultShippingFee($pickupAddress, $deliveryAddress, $weight, $value);

        } catch (\Exception $e) {
            Log::error('GHTK shipping fee calculation error: ' . $e->getMessage());
            
            // Fallback: Tính phí ship mặc định
            return $this->calculateDefaultShippingFee($pickupAddress, $deliveryAddress, $weight, $value);
        }
    }

    /**
     * Tính phí ship mặc định dựa trên khoảng cách
     */
    protected function calculateDefaultShippingFee($pickupAddress, $deliveryAddress, $weight, $value)
    {
        // Xác định khoảng cách dựa trên tỉnh
        $pickupProvince = strtolower($pickupAddress['province']);
        $deliveryProvince = strtolower($deliveryAddress['province']);
        
        $baseFee = 0;
        $distanceMultiplier = 1.0;
        
        // Tính phí cơ bản theo khoảng cách
        if ($pickupProvince === $deliveryProvince) {
            // Cùng tỉnh
            $baseFee = 15000;
            $distanceMultiplier = 1.0;
        } elseif (in_array($pickupProvince, ['hà nội', 'hanoi']) && in_array($deliveryProvince, ['hồ chí minh', 'hcm', 'tp hcm'])) {
            // Hà Nội - Hồ Chí Minh
            $baseFee = 35000;
            $distanceMultiplier = 1.5;
        } else {
            // Tỉnh khác
            $baseFee = 25000;
            $distanceMultiplier = 1.2;
        }
        
        // Tính phí theo cân nặng
        $weightFee = max(0, ($weight - 0.5)) * 5000; // 5k/kg cho mỗi kg vượt 0.5kg
        
        // Tính phí bảo hiểm (0.5% giá trị hàng hóa)
        $insuranceFee = $value * 0.005;
        
        // Tổng phí
        $totalFee = ($baseFee + $weightFee) * $distanceMultiplier;
        
        return [
            'success' => true,
            'fee' => round($totalFee),
            'insurance_fee' => round($insuranceFee),
            'total_fee' => round($totalFee + $insuranceFee),
            'estimated_deliver_time' => '2-3 ngày',
            'delivery_type' => 'road',
            'data' => [
                'base_fee' => $baseFee,
                'weight_fee' => $weightFee,
                'distance_multiplier' => $distanceMultiplier
            ]
        ];
    }

    /**
     * Tạo đơn hàng GHTK
     */
    public function createOrder($orderData)
    {
        try {
            $payload = [
                'products' => $orderData['products'],
                'order' => [
                    'id' => $orderData['order_id'],
                    'pickup_money' => $orderData['cod_amount'] ?? 0,
                    'note' => $orderData['note'] ?? '',
                ],
                'pickup' => [
                    'address' => $orderData['pickup_address'],
                    'ward' => $orderData['pickup_ward'],
                    'district' => $orderData['pickup_district'],
                    'province' => $orderData['pickup_province'],
                    'contact' => [
                        'name' => $orderData['pickup_contact_name'],
                        'phone' => $orderData['pickup_contact_phone'],
                    ],
                ],
                'delivery' => [
                    'address' => $orderData['delivery_address'],
                    'ward' => $orderData['delivery_ward'],
                    'district' => $orderData['delivery_district'],
                    'province' => $orderData['delivery_province'],
                    'contact' => [
                        'name' => $orderData['delivery_contact_name'],
                        'phone' => $orderData['delivery_contact_phone'],
                    ],
                ],
            ];

            $response = Http::withHeaders($this->headers)
                ->post($this->baseUrl . '/services/shipment/order', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    return [
                        'success' => true,
                        'ghtk_order_id' => $data['order']['label_id'],
                        'tracking_url' => $data['order']['tracking_url'],
                        'estimated_deliver_time' => $data['order']['estimated_deliver_time'],
                        'fee' => $data['order']['fee'],
                        'data' => $data['order']
                    ];
                }
            }

            Log::error('GHTK order creation failed: ' . $response->body());
            return ['success' => false, 'message' => 'Không thể tạo đơn hàng GHTK'];

        } catch (\Exception $e) {
            Log::error('GHTK order creation error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi kết nối API'];
        }
    }

    /**
     * Lấy trạng thái đơn hàng
     */
    public function getOrderStatus($ghtkOrderId)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseUrl . "/services/shipment/v2/order/{$ghtkOrderId}");

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Failed to get GHTK order status for {$ghtkOrderId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy danh sách ca giao hàng
     */
    public function getDeliveryShifts()
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get($this->baseUrl . '/services/shipment/v2/delivery-shifts');

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? [];
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Failed to get GHTK delivery shifts: ' . $e->getMessage());
            return [];
        }
    }
}
