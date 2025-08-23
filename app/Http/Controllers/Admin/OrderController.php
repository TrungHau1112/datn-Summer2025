<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\order\StoreOrderRequest;
use App\Http\Requests\order\UpdateOrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Location\ProvinceRepository;
use App\Repositories\Location\DistrictRepository;
use App\Repositories\Location\WardRepository;
use App\Services\Order\OrderService;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Repositories\Payment\PaymentMethodRepository;

class OrderController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Order');
    }
    protected $order;
    protected $orderService;
    protected $orderRepository;
    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;
    protected $categoryRepository;
    protected $productRepository;
    protected $productVariantRepository;
    protected $paymentMethodRepository;

    public function __construct(
        Order $order,
        OrderService $orderService,
        OrderRepository $orderRepository,
        ProvinceRepository $provinceRepository,
        DistrictRepository $districtRepository,
        WardRepository $wardRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        PaymentMethodRepository $paymentMethodRepository
    ) {
        $this->order = $order;
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.order.index', compact(
            'orders',
            'config'
        ));
    }

    public function getData($request)
    {
        $orders = $this->orderService->paginate($request);
        $config = $this->config();
        return view('admin.pages.order.components.table', compact('orders', 'config'));
    }

    public function create()
    {
        $provinces = $this->provinceRepository->getAllProvinces();
        $districts = $this->districtRepository->getAllDistricts();
        $wards = $this->wardRepository->getAllWards();
        $categories = $this->categoryRepository->findByField('is_room', 2)->pluck('name', 'id')->prepend('Danh mục', 0)->toArray();
        $categoryRoom = $this->categoryRepository->findByField('is_room', 1)->pluck('name', 'id')->prepend('Phòng', 0)->toArray();
        $paymentMethods = $this->paymentMethodRepository->getAllPublic();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';

        $user = User::all();
        return view('admin.pages.order.create', compact(
            'provinces',
            'districts',
            'wards',
            'config',
            'categories',
            'categoryRoom',
            'paymentMethods',
        ));
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->create($request);
        if ($order) {
            return redirect()->route('order.index')->with('success', 'Tạo đơn hàng mới thành công');
        }
        return redirect()->route('order.index')->with('Error', 'Tạo đơn hàng mới thất bại');
    }

    public function edit(string $id)
    {
        $order = $this->orderRepository->findById($id, ['orderDetails.product']);
        $order_details = $order->orderDetails;
        $provinces = $this->provinceRepository->getAllProvinces();
        $districts = $this->districtRepository->getAllDistricts();
        $wards = $this->wardRepository->getAllWards();
        $config = $this->config();
        $paymentMethods = $this->paymentMethodRepository->getAllPublic();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $address = $order->address ?? $order->user->address ?? '';
        return view('admin.pages.order.edit', compact(
            'order',
            'order_details',
            'provinces',
            'districts',
            'wards',
            'address',
            'config',
            'paymentMethods'
        ));
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        // dd($request->payment_method_id);
        $result = $this->orderService->update($request, $id);

        if ($result) {
            return redirect()->route('order.index')->with('success', 'Cập nhật đơn hàng thành công.');
        } else {
            return redirect()->route('order.index')->with('error', 'Cập nhật đơn hàng thất bại');
        }
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $result = $this->orderService->updatePaymentStatus($request, $id);
        if ($result) {
            return successResponse();
        } else {
            return errorResponse();
        }
    }

    public function dataProduct(Request $request)
    {
        $query = $request->query('query', '');
        $page = $request->query('page', 1);
        $perPage = 5; // Số lượng sản phẩm mỗi trang

        $products = Product::where('name', 'like', "%$query%")
            ->paginate($perPage);

        return response()->json([
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'pages' => $products->lastPage(),
            ],
        ]);
    }

    public function dataVariantsProduct($id)
    {
        $product = $this->productRepository->findById($id, ['productVariants']);
        return successResponse($product->productVariants);
    }


    public function searchCustomer(Request $request)
    {
        $phone = $request->get('phone');
        $customer = Order::where('phone', $phone)->first();

        if ($customer) {
            $latestOrder = Order::where('phone', $phone)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($latestOrder) {
                $location = [
                    'province' => $latestOrder->province_id,
                    'district' => $latestOrder->district_id,
                    'ward' => $latestOrder->ward_id,
                ];
            }
            $new = [
                'success' => true,
                'customer' => [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'province_id' => $customer->province_id,
                    'district_id' => $customer->district_id,
                    'ward_id' => $customer->ward_id,
                    'location' => $location ?? null,
                ],
            ];
            return successResponse($new);
        }
        return errorResponse('Không tìm thấy khách hàng');
    }

    public function show(string $id)
    {
        $order = $this->orderRepository->findById($id, ['orderDetails']);
        $order_details = $order->orderDetails;
        $provinces = $this->provinceRepository->getAllProvinces();
        $districts = $this->districtRepository->getAllDistricts();
        $wards = $this->wardRepository->getAllWards();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('show');
        $address = $order->address ?? $order->user->address ?? '';
        $ward = $order->ward ?? '';
        $district = $order->district ?? '';
        $province = $order->province ?? '';

        return view('admin.pages.order.show', compact(
            'order',
            'order_details',
            'provinces',
            'districts',
            'wards',
            'address',
            'ward',
            'district',
            'province',
            'config'
        ));
    }
    public function getProduct(Request $request)
    {
        // dd($request);
        $product = $this->productRepository->findByField('sku', $request->sku)->first();
        // rỗng nếu mình chọn biến thể
        if (empty($product)) {
            $product = $this->productVariantRepository->findByField('sku', $request->sku)->first();
            $product->name = $product->product->name . ' - ' . $product->title; // tên sản phẩm + biến thể
            $product->slug = $product->product->slug;
            $product->thumbnail = $product->product->thumbnail;

        }
        $product->quantity = 1;
        return successResponse($product);
    }

    public function trash()
    {
        $orders = $this->orderRepository->getOnlyTrashed();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.order.trash', compact(
            'config',
            'orders'
        ));
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Danh sách đơn hàng',
                'list' => ['Danh sách đơn hàng']
            ],
            'update' => [
                'name' => 'Cập nhật trạng thái đơn hàng',
                'list' => ['QL đơn hàng', 'Cập nhật trạng thái']
            ],
            'create' => [
                'name' => 'Tạo đơn hàng mới',
                'list' => ['QL đơn hàng', 'Tạo đơn hàng']
            ],
            'show' => [
                'name' => 'Thông tin hóa đơn',
                'list' => ['Chi tiết đơn hàng', 'Thông tin hóa đơn']
            ],
            'trash' => [
                'name' => 'Thùng rác',
                'list' => ['Thùng rác']
            ]
        ];

        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [
                'admin_asset/css/order.css'
            ],
            'js' => [
                'admin_asset/library/order_product.js',
                'admin_asset/library/order.js',
                'admin_asset/library/location.js'
                // 'admin_asset/library/dataTables_order.js'
            ],
            'model' => 'order'
        ];
    }
}


