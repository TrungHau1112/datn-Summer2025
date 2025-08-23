<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use App\Repositories\Order\OrderRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Support\Facades\DB;

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
        // $statistic = $this->orderService->orderStatistic();
        // $statistic['total_customer'] = $this->userRepository->totalCustomer();
        // $statistic['total_product'] = $this->productRepository->totalProduct();
        // $topProducts = $this->topSellingProducts();
        // $topLeastProducts = $this->topLeastSellingProducts();
        // $lowStockProducts = $this->lowStockProducts();
        // $topCustomersByQuantity = $this->topCustomersByQuantity();
        return view('admin.pages.dashboard.index', compact(
            'config',
            // 'statistic',
            // 'topProducts',
            // 'topLeastProducts',
            // 'lowStockProducts',
            // 'topCustomersByQuantity'
        ));
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
        $products = DB::table('products')
            ->leftJoin('product_variants', function ($join) {
                $join->on('products.id', '=', 'product_variants.product_id')
                    ->where('products.has_attribute', '=', 1);
            })
            ->leftJoin('order_details', function ($join) {
                $join->on('products.sku', '=', 'order_details.sku')
                    ->orOn('product_variants.sku', '=', 'order_details.sku');
            })
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->select(
                DB::raw('CASE 
                            WHEN product_variants.title IS NOT NULL THEN CONCAT(products.name, " - ", product_variants.title) 
                            ELSE products.name 
                        END AS product_name'),
                'products.thumbnail',
                'products.slug',
                DB::raw('COALESCE(product_variants.sku, products.sku) AS sku'),
                DB::raw('COALESCE(product_variants.code, NULL) AS variant_code'),
                DB::raw('COALESCE(SUM(CASE WHEN orders.status = "delivered" THEN order_details.quantity ELSE 0 END), 0) as total_quantity'),
                DB::raw('COALESCE(SUM(CASE WHEN orders.status = "delivered" THEN order_details.quantity * order_details.price ELSE 0 END), 0) as total_revenue')
            )
            ->groupBy(
                'products.id',
                'products.name',
                'products.thumbnail',
                'products.slug',
                'products.sku',
                'product_variants.title',
                'product_variants.sku',
                'product_variants.code'
            )
            ->orderBy('total_quantity', 'asc')
            ->take(10)
            ->get();

        return $products;
    }



    public function lowStockProducts()
    {
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
                'product_variants.code AS variant_code' // Thêm cột variant_code
            )
            ->where(function ($query) {
                $query->where('products.quantity', '<=', 10)
                    ->orWhere('product_variants.quantity', '<=', 10);
            })
            ->orderBy('quantity', 'asc')
            ->take(10)
            ->get();

        return $products;
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
        $customers = DB::table('users')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->whereNotIn('users.id', function ($query) {
                $query->select('model_id')
                    ->from('model_has_roles');
            })
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