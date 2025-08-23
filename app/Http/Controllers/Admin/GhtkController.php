<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GhtkApiToken;
use App\Services\Ghtk\GhtkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GhtkController extends Controller
{
    protected $ghtkService;

    public function __construct(GhtkService $ghtkService)
    {
        $this->ghtkService = $ghtkService;
    }

    /**
     * Hiển thị danh sách API tokens
     */
    public function index()
    {
        $tokens = GhtkApiToken::orderBy('created_at', 'desc')->get();
        
        $config = [
            'css' => ['admin_asset/css/ghtk.css'],
            'js' => ['admin_asset/library/ghtk.js'],
            'model' => 'ghtk'
        ];

        return view('admin.pages.ghtk.index', compact('tokens', 'config'));
    }

    /**
     * Hiển thị form tạo token mới
     */
    public function create()
    {
        $config = [
            'css' => ['admin_asset/css/ghtk.css'],
            'js' => ['admin_asset/library/ghtk.js'],
            'model' => 'ghtk'
        ];

        return view('admin.pages.ghtk.create', compact('config'));
    }

    /**
     * Lưu token mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token_name' => 'required|string|max:255',
            'api_token' => 'required|string',
            'access_rights' => 'nullable|array',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $token = new GhtkApiToken();
            $token->token_name = $request->token_name;
            $token->api_token = $request->api_token;
            $token->access_rights = $request->access_rights ?? [];
            $token->expires_at = $request->expires_at;
            $token->is_active = true;
            $token->created_by = Auth::id();
            $token->save();

            // Test kết nối API
            if ($this->ghtkService->testConnection()) {
                return redirect()->route('ghtk.index')
                    ->with('success', 'Token đã được tạo và kết nối thành công!');
            } else {
                return redirect()->route('ghtk.index')
                    ->with('warning', 'Token đã được tạo nhưng không thể kết nối API. Vui lòng kiểm tra lại.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị thông tin token
     */
    public function show($id)
    {
        $token = GhtkApiToken::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'token' => $token->api_token,
            'expires_at' => $token->expires_at ? $token->expires_at->format('d/m/Y H:i:s') : 'Không có hạn',
            'access_rights' => $token->access_rights
        ]);
    }

    /**
     * Cập nhật trạng thái token
     */
    public function update(Request $request, $id)
    {
        $token = GhtkApiToken::findOrFail($id);
        
        if ($request->has('is_active')) {
            $token->is_active = $request->boolean('is_active');
            $token->save();

            return response()->json([
                'success' => true,
                'message' => 'Trạng thái token đã được cập nhật'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không có thay đổi nào'
        ]);
    }

    /**
     * Xóa token
     */
    public function destroy($id)
    {
        try {
            $token = GhtkApiToken::findOrFail($id);
            $token->delete();

            return response()->json([
                'success' => true,
                'message' => 'Token đã được xóa'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test kết nối API
     */
    public function testConnection()
    {
        // Kiểm tra xem có token nào trong database không
        $tokenCount = GhtkApiToken::count();
        
        if ($tokenCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa có API token nào được tạo. Vui lòng tạo token trước.'
            ]);
        }

        $isConnected = $this->ghtkService->testConnection();
        
        return response()->json([
            'success' => $isConnected,
            'message' => $isConnected ? 'Kết nối API thành công!' : 'Không thể kết nối API. Vui lòng kiểm tra token và quyền truy cập.'
        ]);
    }

    /**
     * Refresh token từ database
     */
    public function refreshToken()
    {
        $isRefreshed = $this->ghtkService->refreshToken();
        
        if ($isRefreshed) {
            return response()->json([
                'success' => true,
                'message' => 'Token đã được refresh thành công!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không thể refresh token. Vui lòng kiểm tra token trong database.'
            ]);
        }
    }

    /**
     * Lấy danh sách tỉnh thành từ GHTK
     */
    public function getProvinces()
    {
        // Kiểm tra xem có token nào trong database không
        $tokenCount = GhtkApiToken::count();
        
        if ($tokenCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa có API token nào được tạo. Vui lòng tạo token trước.'
            ]);
        }

        $provinces = $this->ghtkService->getProvinces();
        
        if (empty($provinces)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách tỉnh thành. Vui lòng kiểm tra token và quyền truy cập.'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Lấy danh sách quận huyện
     */
    public function getDistricts(Request $request)
    {
        $provinceId = $request->get('province_id');
        $districts = $this->ghtkService->getDistricts($provinceId);
        
        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    /**
     * Lấy danh sách phường xã
     */
    public function getWards(Request $request)
    {
        $districtId = $request->get('district_id');
        $wards = $this->ghtkService->getWards($districtId);
        
        return response()->json([
            'success' => true,
            'data' => $wards
        ]);
    }

    /**
     * Tính phí ship
     */
    public function calculateShippingFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_province' => 'required|string',
            'pickup_district' => 'required|string',
            'delivery_province' => 'required|string',
            'delivery_district' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $pickupAddress = [
            'province' => $request->pickup_province,
            'district' => $request->pickup_district,
        ];

        $deliveryAddress = [
            'province' => $request->delivery_province,
            'district' => $request->delivery_district,
        ];

        $result = $this->ghtkService->calculateShippingFee(
            $pickupAddress,
            $deliveryAddress,
            $request->weight,
            $request->value ?? 0
        );

        return response()->json($result);
    }
}
