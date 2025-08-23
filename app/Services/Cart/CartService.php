<?php

namespace App\Services\Cart;

use App\Services\BaseService;
use App\Repositories\Cart\CartRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Repositories\Discount\DiscountCodeUserRepository;
use App\Repositories\Discount\DiscountCodeRepository;
use App\Repositories\Collection\CollectionRepository;
use App\Repositories\Collection\CollectionProductRepository;
use App\Services\Collection\CollectionService;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartService extends BaseService
{
    protected $productService;
    protected $collectionService;
    protected $cartRepository;
    protected $userRepository;
    protected $productRepository;
    protected $productVariantRepository;
    protected $discountCodeRepository;
    protected $discountCodeUserRepository;
    protected $collectionRepository;
    protected $collectionProductRepository;

    public function __construct(
        ProductService $productService,
        CollectionService $collectionService,
        CartRepository $cartRepository,
        UserRepository $userRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        DiscountCodeRepository $discountCodeRepository,
        DiscountCodeUserRepository $discountCodeUserRepository,
        CollectionRepository $collectionRepository,
        CollectionProductRepository $collectionProductRepository,
    ) {
        $this->productService = $productService;
        $this->collectionService = $collectionService;
        $this->cartRepository = $cartRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->discountCodeRepository = $discountCodeRepository;
        $this->discountCodeUserRepository = $discountCodeUserRepository;
        $this->collectionRepository = $collectionRepository;
        $this->collectionProductRepository = $collectionProductRepository;
    }

    private function paginateAgrument($request)
    {
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['content']
            ],
            'condition' => [
                'publish' => $request->integer('publish'),
            ],
            'sort' => isset($request['sort']) && $request['sort'] != 0
                ? explode(',', $request['sort'])
                : ['id', 'asc'],
            'perpage' => $request->integer('perpage') ?? 20,
        ];
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', 'price']);
            $payload['user_id'] = Auth::id();
            $payload['quantity'] = (int) $payload['quantity'];
            
            // Lấy thông tin sản phẩm
            $product = $this->productService->getProductBySku($payload['sku']);
            if (!$product) {
                throw new \Exception('Sản phẩm không tồn tại');
            }
            
            $inventory = $product->quantity;
            
            // Kiểm tra số lượng tồn kho
            if ($payload['quantity'] > $inventory) {
                throw new \Exception('Số lượng vượt quá tồn kho');
            }
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $cart = $this->cartRepository->findByField('user_id', $payload['user_id'])->get();
            $found = false;
            foreach ($cart as $value) {
                if ($value->sku === $payload['sku']) {
                    $newQuantity = $value->quantity + $payload['quantity'];
                    if ($newQuantity > $inventory) {
                        $newQuantity = $inventory;
                    }
                    $value->quantity = $newQuantity;
                    $value->save();
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $this->cartRepository->create($payload);
            }
            
            // Trừ số lượng sản phẩm
            $this->updateProductQuantity($payload['sku'], $payload['quantity']);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    // Method để cập nhật số lượng sản phẩm (quantity có thể âm để hoàn trả)
    private function updateProductQuantity($sku, $quantity)
    {
        // Tìm sản phẩm chính
        $product = $this->productRepository->findByField('sku', $sku)->first();
        if ($product) {
            $product->quantity = max(0, $product->quantity - $quantity);
            $product->save();
            return;
        }
        
        // Tìm variant sản phẩm
        $variant = $this->productVariantRepository->findByField('sku', $sku)->first();
        if ($variant) {
            $variant->quantity = max(0, $variant->quantity - $quantity);
            $variant->save();
            
            // Cập nhật số lượng tổng của sản phẩm chính
            $mainProduct = $variant->product;
            if ($mainProduct) {
                $this->productService->updateProductTotalQuantity($mainProduct->id);
            }
        }
    }
    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method', 'idCart']);
            
            // Lấy thông tin cart item cũ
            $oldCartItem = $this->cartRepository->findById($id);
            $oldQuantity = $oldCartItem->quantity;
            $newQuantity = (int) $payload['quantity'];
            
            // Cập nhật cart
            $this->cartRepository->update($id, $payload);
            
            // Cập nhật số lượng sản phẩm
            $quantityDiff = $newQuantity - $oldQuantity;
            if ($quantityDiff != 0) {
                $this->updateProductQuantity($oldCartItem->sku, -$quantityDiff);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            // Lấy thông tin cart item trước khi xóa
            $cartItem = $this->cartRepository->findById($id);
            $quantity = $cartItem->quantity;
            $sku = $cartItem->sku;
            
            // Xóa cart item
            $this->cartRepository->delete($id);
            
            // Hoàn trả số lượng sản phẩm
            $this->updateProductQuantity($sku, -$quantity);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }
    public function checkDiscount($user_id, $code)
    {
        $code = $this->discountCodeRepository->findByField('code', $code)->first();
        if($code) {
            $existCode = $this->discountCodeUserRepository->findByField('discount_code_id', $code->id)->where('user_id', $user_id)->first();
            return $existCode;
        }
        return false;
        
    }
    public function submitDiscount($user_id, $discountCode)
    {
        DB::beginTransaction();
        try {
            $discountCode = json_decode($discountCode, true);
            $payload['user_id'] = $user_id;
            foreach ($discountCode as $codeId) {
                $payload['discount_code_id'] = $codeId;
                $this->discountCodeUserRepository->create($payload);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }
    public function getCart($carts)
    {
        $products = [];
        foreach ($carts as $key => $value) {
            $data = $this->productRepository->findByField('sku', $value->sku)->first();
            if (empty($data)) {
                $data = $this->productVariantRepository->findByField('sku', $value->sku)->first();
            }
            $products[] = $data;
        }
        return $products;
    }
    public function fetchCartData($carts)
    {
        $products = [];
        $total = 0;
        foreach ($carts as $value) {
            $data = $this->productRepository->findByField('sku', $value->sku)->first();
            if (isset($data)) {
                $data->quantityCart = $value->quantity ?? '';
            }
            if (empty($data)) {
                $data = $this->productVariantRepository->findByField('sku', $value->sku)->first();
                $data->discount = $data->product->discount;
                $data->name = $data->product->name . " - $data->title";
                $data->quantityCart = $value->quantity;
                if (isset($data->albums) && !empty($data->albums)) {
                    $albums = json_decode($data->albums, true);
                    if (isset($albums) && !empty($albums)) {
                        $data->thumbnail = explode(',', $albums)[0] ?? '';
                    }
                }
            }
            $cart[] = $data;
            $total += ($data->price - ($data->price * $data->discount) / 100) * $data->quantityCart;
        }
        return ['cart' => $cart, 'total' => $total];
    }
    public function getProduct($item)
    {
        if (isset($item)) {
            $data = $this->productRepository->findByField('sku', $item->sku)->first();
            if (empty($data)) {
                $data = $this->productVariantRepository->findByField('sku', $item->sku)->first();
                $data->discount = $data->product->discount ?? '';
                $data->name = $data->product->name ?? '';
                $data->slug = $data->product->slug ?? '';
                // if (isset($data->albums) && !empty($data->albums)) {
                //     $albums = json_decode($data->albums, true);
                //     if (isset($albums) && !empty($albums)) {
                //         $data->thumbnail = explode(',', $albums)[0] ?? 'https://placehold.co/600x600?text=Hinh%20Anh';
                //     }
                // }
                $data->thumbnail = $data->product->thumbnail;
                $category = $data->product->categories->where('is_room', 2)->first();
                $data->category = $category ? strtolower($category->name) : '';
            }
            $data->idCart = $item->id ?? '';
            $data->quantityCart = $item->quantity ?? '';
            $data->quantity = $data->product->quantity ?? $data->quantity;
        }
        return $data;
    }
    public function getTotalCart($carts)
    {
        $total = 0;
        foreach ($carts as $value) {
            $data = $this->productRepository->findByField('sku', $value->sku)->first();
            if (empty($data)) {
                $data = $this->productVariantRepository->findByField('sku', $value->sku)->first();
            }
            $discount = $data->discount ?? $data->product->discount;
            $total += ((int) $data->price - ((int) $data->price * $discount) / 100) * (int) $value->quantity;
        }
        return $total;
    }
    public function getDiscountCollection($carts)
    {
        $id_collections = DB::table('collection_product')->pluck('collection_id')->unique();
        $cart = $carts->pluck('sku');
        $collections = [];
        $totalDiscountAmout = 0;
        foreach ($id_collections as $id_collection) {
            $collection = $this->collectionProductRepository->findByField('collection_id', $id_collection)->get();
            $filterCollection = $this->collectionService->filterCollection($collection);
            $missing = collect($filterCollection['sku'])->diff($cart);
            $results = [
                'collection_id' => $filterCollection['collection_id'],
                'is_full' => $missing->isEmpty(),
                'missing_sku' => $missing->values()->all(),
            ];
            if ($results['is_full'] == true) {
                $collections[] = $filterCollection;
            }
        }
        foreach ($collections as $collection) {
            $discountAmout = $this->collectionService->getDiscountByIdCollection($collection['collection_id']);
            $totalDiscountAmout += $discountAmout;
        }
        $result = $collections;
        $result['totalDiscountAmount'] = $totalDiscountAmout;
        // dd($result);
        return $result;
    }
    // public function getDiscountCollection($carts)
    // {
    //     $id_collections = DB::table('collection_product')->pluck('collection_id')->unique();
    //     $cart = $carts->pluck('sku');
    //     $collections = [];
    //     $totalDiscountAmout = 0;
    //     foreach ($id_collections as $id_collection) {
    //         $collection = $this->collectionProductRepository->findByField('collection_id', $id_collection)->get();
    //         $filterCollection = $this->collectionService->filterCollection($collection);
    //         $collections[] = $filterCollection;
    //     }
    //     $results = collect($collections)->map(function ($collection) use ($cart) {
    //         $missing = collect($collection['sku'])->diff($cart);
    //         return [
    //             'collection_id' => $collection['collection_id'],
    //             'is_full' => $missing->isEmpty(),
    //             'missing_sku' => $missing->values()->all(),
    //         ];
    //     });
    //     $filteredCollectionIds = $results->filter(function ($item) {
    //         return $item['is_full'] === true;
    //     })->pluck('collection_id')->values()->all();
    //     foreach ($filteredCollectionIds as $id) {
    //         $discountAmout = $this->collectionService->getDiscountByIdCollection($id);
    //         $totalDiscountAmout += $discountAmout;
    //     }
    //     $result = $collections;
    //     $result['totalDiscountAmount'] = $totalDiscountAmout;
    //     return $result;
    // }
}
