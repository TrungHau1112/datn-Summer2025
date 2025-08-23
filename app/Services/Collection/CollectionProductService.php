<?php

namespace App\Services\Collection;
use App\Services\BaseService;
use App\Repositories\Collection\CollectionRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use Illuminate\Support\Facades\DB;
class CollectionProductService extends BaseService
{
    protected $collectionRepository;
    protected $productRepository;
    protected $productVariantRepository;
    public function __construct(
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        CollectionRepository $collectionRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->collectionRepository = $collectionRepository;
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
            $payload = $request->except(['categoriesOther', 'categoriesRoom', '_token', 'send', 'idProduct', 'keyword','_method']);
            $collection = $this->collectionRepository->findById($id);
            $this->collectionRepository->update($id, $payload);
            $productIds = $request->idProduct;
            $collection->products()->sync($productIds);
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
            $this->collectionRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }
}
