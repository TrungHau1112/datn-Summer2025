<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Repositories\Order\OrderRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected $orderService;
    protected $userRepository;
    protected $productRepository;
    protected $orderRepository;
    public function __construct(
        OrderService $orderService,
        UserRepository $userRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository
    ) {
        $this->orderService = $orderService;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }


    public function index()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');

        // Cache toàn bộ dữ liệu dashboard trong 5 phút
        $dashboardData = Cache::remember('dashboard_data', 300, function () {
            $statistic = $this->orderService->orderStatistic();
            $statistic['total_customer'] = $this->userRepository->totalCustomer();
            $statistic['total_product'] = $this->productRepository->totalProduct();

            return [
                'statistic' => $statistic,
                'topProducts' => $this->topSellingProducts(),
                'topLeastProducts' => $this->topLeastSellingProducts(),
                'lowStockProducts' => $this->lowStockProducts(),
                'topCustomersByQuantity' => $this->topCustomersByQuantity()
            ];
        });

        return view('admin.pages.dashboard.index', array_merge(compact('config'), $dashboardData));
    }

    public function getOrdersAndRevenueByYear()
    {
        $year = now()->year;
        $orders = $this->orderRepository->ordersAndRevenueByYear($year);
        return successResponse($orders);
    }

    public function topSellingProducts()
    {
        return $this->productRepository->getTopSellingProducts();
    }



    public function topLeastSellingProducts()
    {
        return Cache::remember('top_least_selling_products', 600, function () {
            // Sử dụng subquery để tối ưu hiệu suất
            $deliveredOrderDetails = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->where('orders.status', 'delivered')
                ->select(
                    'order_details.sku',
                    DB::raw('SUM(order_details.quantity) as total_quantity'),
                    DB::raw('SUM(order_details.quantity * order_details.price) as total_revenue')
                )
                ->groupBy('order_details.sku');

            $products = DB::table('products')
                ->leftJoin('product_variants', function ($join) {
                    $join->on('products.id', '=', 'product_variants.product_id')
                        ->where('products.has_attribute', '=', 1);
                })
                ->leftJoinSub($deliveredOrderDetails, 'sold_data', function ($join) {
                    $join->on('products.sku', '=', 'sold_data.sku')
                        ->orOn('product_variants.sku', '=', 'sold_data.sku');
                })
                ->select(
                    DB::raw('CASE 
                                WHEN product_variants.title IS NOT NULL THEN CONCAT(products.name, " - ", product_variants.title) 
                                ELSE products.name 
                            END AS product_name'),
                    'products.thumbnail',
                    'products.slug',
                    DB::raw('COALESCE(product_variants.sku, products.sku) AS sku'),
                    DB::raw('COALESCE(product_variants.code, NULL) AS variant_code'),
                    DB::raw('COALESCE(sold_data.total_quantity, 0) as total_quantity'),
                    DB::raw('COALESCE(sold_data.total_revenue, 0) as total_revenue')
                )
                ->groupBy(
                    'products.id',
                    'products.name',
                    'products.thumbnail',
                    'products.slug',
                    'products.sku',
                    'product_variants.title',
                    'product_variants.sku',
                    'product_variants.code',
                    'sold_data.total_quantity',
                    'sold_data.total_revenue'
                )
                ->orderBy('total_quantity', 'asc')
                ->take(10)
                ->get();

            return $products;
        });
    }



    public function lowStockProducts()
    {
        return Cache::remember('low_stock_products', 300, function () {
            $products = DB::table('products')
                ->leftJoin('product_variants', function ($join) {
                    $join->on('products.id', '=', 'product_variants.product_id')
                        ->where('products.has_attribute', '=', 1);
                })
                ->select(
                    'products.id',
                    DB::raw('CASE 
                                WHEN product_variants.title IS NOT NULL THEN CONCAT(products.name, " - ", product_variants.title) 
                                ELSE products.name 
                            END AS product_name'),
                    'products.thumbnail',
                    'products.slug',
                    DB::raw('COALESCE(product_variants.sku, products.sku) AS sku'),
                    DB::raw('COALESCE(product_variants.quantity, products.quantity) AS quantity'),
                    'product_variants.code AS variant_code'
                )
                ->where(function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->whereNull('product_variants.id')
                            ->where('products.quantity', '<=', 10);
                    })
                        ->orWhere(function ($subQuery) {
                            $subQuery->whereNotNull('product_variants.id')
                                ->where('product_variants.quantity', '<=', 10);
                        });
                })
                ->orderBy('quantity', 'asc')
                ->take(10)
                ->get();

            return $products;
        });
    }



    public function newCustomersByMonth()
    {
        $year = now()->year;
        $customers = DB::table('users')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(id) as new_customers')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->get();

        $result = collect(range(1, 12))->map(function ($month) use ($customers) {
            $customerCount = $customers->firstWhere('month', $month);
            return [
                'month' => $month,
                'new_customers' => $customerCount ? $customerCount->new_customers : 0
            ];
        });

        return successResponse($result);
    }

    public function topCustomersByQuantity()
    {
        return Cache::remember('top_customers_by_quantity', 600, function () {
            $customers = DB::table('users')
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->whereNotIn('users.id', function ($query) {
                    $query->select('model_id')
                        ->from('model_has_roles');
                })
                ->where('orders.status', 'delivered') // Chỉ tính orders đã delivered
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    DB::raw('SUM(order_details.quantity) as total_quantity')
                )
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderByDesc('total_quantity')
                ->take(10)
                ->get();

            return $customers;
        });
    }


    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Thống kê',
                'list' => ['Thống kê']
            ],
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [],
            'js' => [
                'https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js',
                'admin_asset/library/dashboard.js'
            ]
        ];
    }

}