<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Cart\CartRepository;
use App\Services\Cart\CartService;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductService;
use App\Repositories\Product\ProductVariantRepository;


class CartController extends Controller
{
    protected $cartRepository;
    protected $cartService;
    protected $productRepository;
    protected $productService;
    protected $productVariantRepository;


    public function __construct(
        CartRepository $cartRepository,
        CartService $cartService,
        ProductRepository $productRepository,
        ProductService $productService,
        ProductVariantRepository $productVariantRepository,

    ) {
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->productVariantRepository = $productVariantRepository;

    }
    public function index(Request $request)
    {
        $title = 'Giỏ hàng';
        $config = $this->config();
        $carts = $this->cartRepository->findByField('user_id', Auth::id())->get();
        $listCart = '';
        foreach ($carts as $cart) {
            $listCart .= $this->getProductFull($cart);
        }
        $product_featureds = $this->productRepository->getFeatured();

        // Calculate total cart value
        $totalCart = $carts->count() > 0 ? $this->cartService->getTotalCart($carts) : 0;

        // $discountCollection = $this->cartService->getDiscountCollection($carts);
        return view('client.pages.cart.index', compact(
            'config',
            'product_featureds',
            'carts',
            'listCart',
            'title',
            'totalCart'
            // 'discountCollection'
        ));
    }

    public function listCart(Request $request)
    {
        $data = $this->cartRepository->findByField('user_id', Auth::id())->get();
        return $data;
    }

    public function getProductFull($data)
    {
        $carts = $this->cartRepository->findByField('user_id', Auth::id())->get();
        $zIndex = 1000 - $data->id;
        $product = $this->cartService->getProduct($data);
        return view('client.pages.cart.components.item', compact('product', 'carts', 'zIndex'))->render();
    }

    public function getProduct(Request $request)
    {
        $carts = $this->cartRepository->findByField('user_id', Auth::id())->get();
        $zIndex = 1000 - $request->id;
        $cart = $this->cartRepository->findById($request->id);
        $product = $this->cartService->getProduct($cart);
        return view('client.pages.cart.components.item', compact('product', 'carts', 'zIndex'))->render();
    }

    public function count()
    {
        $data = $this->cartRepository->findByField('user_id', Auth::id())->get();
        return count($data);
    }
    public function store(Request $request)
    {
        if (Auth::check()) {
            $data = $this->cartService->create($request);
            if ($data) {
                return successResponse('', 'Thành công');
            }
            return errorResponse('Thất bại');
        } else {
            return errorResponse('Bạn phải đăng nhập');
        }
    }
    public function remove(Request $request)
    {
        $data = $this->cartService->delete($request->id);
        if ($data) {
            return successResponse('', 'xoá sản phẩm thành công');
        }
        return errorResponse('xoá sản phẩm thất bại');
    }
    public function changeVariant(Request $request)
    {
        $id = $request->idCart;
        $data = $this->cartService->update($request, $id);
        if ($data) {
            return successResponse($id, 'Đổi phân loại thành công');
        }
        return errorResponse('Đổi phân loại thất bại');
    }
    public function updateQuantity(Request $request)
    {
        $quantity = (int) $request->quantity;
        $cart_item = $this->cartRepository->findById($request->idCart);
        $inventory = $this->productService->getProductBySku($cart_item->sku)->quantity;
        $id = $request->idCart;
        $data = [];
        if ($quantity <= $inventory) {
            $this->cartService->update($request, $id);
            return successResponse(
                $inventory,
                'Cập nhật số lượng'
            );
        } else {
            $data['inventory'] = $inventory;
            $data['quantity'] = $cart_item->quantity;
            return $data;
        }
    }

    public function totalCart(Request $request)
    {
        $carts = $this->cartRepository->findByField('user_id', Auth::id())->get();
        $discountCollection = $this->cartService->getDiscountCollection($carts);
        $data = [];
        $data['totalCart'] = $this->cartService->getTotalCart($carts);
        if (isset($discountCollection)) {
            $data['afterDiscount'] = $data['totalCart'] - $discountCollection['totalDiscountAmount'];
        } else {
            $data['afterDiscount'] = $data['totalCart'];
        }
        $data['nameCollection'] = [];
        if (isset($discountCollection) && is_array($discountCollection)) {
            foreach ($discountCollection as $discount) {
                if (is_array($discount)) {
                    $nameCollection = getNamebyIdCollection($discount['collection_id']);
                    $data['nameCollection'][] = $nameCollection;
                }
            }
        }
        return response()->json($data);
    }
    private function config()
    {
        return [
            'css' => [
                'client_asset/custom/css/cart.css',
                'client_asset/custom/css/checkbox.css',
            ],
            'js' => [
                "https://freshcart.codescandy.com/assets/libs/rater-js/index.js",
                "client_asset/custom/js/cart/cart.js",
            ],
            'model' => 'cart'
        ];
    }



}
