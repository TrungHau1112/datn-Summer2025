<?php

namespace App\Services\Collection;
use App\Services\BaseService;
use App\Repositories\Collection\CollectionRepository;
use App\Repositories\Collection\CollectionProductRepository;
use App\Services\Product\ProductService;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use Illuminate\Support\Facades\DB;
class CollectionService extends BaseService
{
    protected $productService;
    protected $collectionRepository;
    protected $productRepository;
    protected $productVariantRepository;
    protected $collectionProductRepository;
    public function __construct(
        productService $productService,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        CollectionRepository $collectionRepository,
        CollectionProductRepository $collectionProductRepository,
    ) {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->collectionRepository = $collectionRepository;
        $this->collectionProductRepository = $collectionProductRepository;
    }
    private function paginateAgrument($request)
    {
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['name']
            ],
            'condition' => [
                'publish' => isset($request['publish'])
                    ? (int) $request['publish']
                    : null,
                'deleted_at' => null,
            ],
            'sort' => isset($request['sort']) && $request['sort'] != 0
                ? explode(',', $request['sort'])
                : ['id', 'asc'],
            'perpage' => (int) (isset($request['perpage']) && $request['perpage'] != 0 ? $request['perpage'] : 10),
        ];
    }
    public function paginate($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $data = $this->collectionRepository->pagination($agruments);
        return $data;
    }
    private function paginateAgrumentClient($request)
    {
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['name']
            ],
            'condition' => [
                'publish' => 1,
            ],
            'sort' => ['id', 'asc'],
            'perpage' => 10,
        ];
    }
    public function paginateClient($request)
    {
        $agruments = $this->paginateAgrumentClient($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $data = $this->collectionRepository->pagination($agruments);
        return $data;
    }
    public function getDetail($id_collections)
    {
        $products = [];
        foreach ($id_collections as $value) {
            $data = $this->productRepository->findByField('sku', $value->product_sku)->first();
            if ($data) {
                $category = $data->categories->where('is_room', 2)->first();
                $data->category = $category ? strtolower($category->name) : '';
            }
            if (empty($data->sku)) {
                $data = $this->productVariantRepository->findByField('sku', $value->productVariant_sku)->first();
                if ($data && $data->product) {
                    $data->name = $data->product->name ?? '';
                    $data->slug = $data->product->slug ?? '';
                    $data->thumbnail = $data->product->thumbnail ?? '';
                    $category = $data->product->categories->where('is_room', 2)->first();
                    $data->category = $category ? strtolower($category->name) : '';
                }
            }
            $products[] = $data;
        }
        return $products;
    }
    public function getDiscountByIdCollection($id)
    {
        $collection = $this->collectionRepository->findByField('id', $id)->first();
        if (isset($collection['discount'])) {
            $totalAmount = 0;
            $collectionProduct = $this->collectionProductRepository->findByField('collection_id', $id)->get();
            $filterCollection = $this->filterCollection($collectionProduct);
            foreach ($filterCollection['sku'] as $sku) {
                $product = $this->productService->getProductBySku($sku);
                $price = $product->price - ($product->price * $product->discount) / 100;
                $totalAmount += $price;
            }
            // dd($totalAmount);
            $discountAmount = ($totalAmount * $collection['discount']) / 100;
            return $discountAmount;
        }
        return 0;
    }
    public function filterCollection($collection)
    {
        $data = $collection->groupBy('collection_id')->map(function ($group) {
            return [
                'collection_id' => $group->first()->collection_id,
                'sku' => array_filter(array_merge(
                    $group->pluck('product_sku')->all(),
                    $group->pluck('productVariant_sku')->all()
                )),
            ];
        })->values()->first();
        return $data;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['categoriesOther', 'categoriesRoom', '_token', 'send', 'idProduct', 'keyword']);
            if (empty($payload['slug'])) {
                $payload['slug'] = getSlug($payload['name']);
            }
            $collection = $this->collectionRepository->create($payload);
            foreach ($request->skus as $sku) {
                $product = $this->productRepository->findByField('sku', $sku)->first();
                $productSku = null;
                $productVariantSku = null;
                if ($product) {
                    $productSku = $product->sku;
                } else {
                    $productVariant = $this->productVariantRepository->findByField('sku', $sku)->first();
                    if ($productVariant) {
                        $productVariantSku = $productVariant->sku;
                    }
                }
                if ($productSku || $productVariantSku) {
                    DB::table('collection_product')->insert([
                        'collection_id' => $collection->id,
                        'product_sku' => $productSku ?? null,
                        'productVariant_sku' => $productVariantSku ?? null,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }
    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            DB::table('collection_product')->where('collection_id', $id)->delete();
            $payload = $request->except(['categoriesOther', 'categoriesRoom', '_token', 'send', 'idProduct', 'keyword', '_method']);
            $collection = $this->collectionRepository->findById($id);
            if (empty($payload['slug'])) {
                $payload['slug'] = getSlug($payload['name']);
            }
            $this->collectionRepository->update($id, $payload);
            foreach ($request->skus as $sku) {
                $product = $this->productRepository->findByField('sku', $sku)->first();
                $productSku = null;
                $productVariantSku = null;
                if ($product) {
                    $productSku = $product->sku;
                } else {
                    $productVariant = $this->productVariantRepository->findByField('sku', $sku)->first();
                    if ($productVariant) {
                        $productVariantSku = $productVariant->sku;
                    }
                }
                if ($productSku || $productVariantSku) {
                    DB::table('collection_product')->insert(
                        [
                            'collection_id' => $collection->id,
                            'product_sku' => $productSku ?? null,
                            'productVariant_sku' => $productVariantSku ?? null,
                        ]
                    );
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            // $this->collectionRepository->delete($id);
            $this->collectionRepository->update($id, ['deleted_at' => now()]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->collectionRepository->restore($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->collectionRepository->destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }


}
