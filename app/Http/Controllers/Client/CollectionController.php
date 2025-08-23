<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;


use App\Services\Comment\CommentService;
use App\Services\Collection\CollectionService;

use App\Repositories\Collection\CollectionRepository;
use App\Repositories\Collection\CollectionProductRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\ForbiddenWordRepository;
use App\Repositories\User\UserRepository;
class CollectionController extends Controller
{
    protected $commentService;
    protected $collectionService;

    protected $forbiddenWordRepository;
    protected $collectionRepository;
    protected $collectionProductRepository;
    protected $productVariantRepository;
    protected $productRepository;
    protected $commentRepository;
    protected $userRepository;
    public function __construct(
        CommentService $commentService,
        CollectionService $collectionService,

        ForbiddenWordRepository $forbiddenWordRepository,
        CollectionRepository $collectionRepository,
        CollectionProductRepository $collectionProductRepository,
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        CommentRepository $commentRepository,
        UserRepository $userRepository,
    ) {
        $this->commentService = $commentService;
        $this->collectionService = $collectionService;

        $this->forbiddenWordRepository = $forbiddenWordRepository;
        $this->collectionRepository = $collectionRepository;
        $this->collectionProductRepository = $collectionProductRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }
    public function index(Request $request)
    {
        $config = $this->config();
        $collections = $this->collectionService->paginateClient(Collection::all());     
        $allCollection = Collection::all();     
        return view('client.pages.collection.index', compact('config', 'collections','allCollection'));
    }
    public function detail($slug)
    {
        $config = $this->config();
        $collections = successResponse(Collection::all()->where('publish', 1))->getData()->data;
        $collection = $this->collectionRepository->findByField('slug', $slug)->first();
        $id_collections = $this->collectionProductRepository->findByField('collection_id', $collection->id)->get();
        $products = $this->collectionService->getDetail($id_collections);
        return view('client.pages.collection.detail', compact('config', 'collection', 'collections', 'products'));
    }
    public function list(Request $request)
    {
        $collections = successResponse(Collection::all())->getData()->data;
        return $collections;
    }
    

    private function config()
    {
        return [
            'css' => [
                'client_asset/custom/css/collection.css',
                'client_asset/custom/css/cart.css',
                'client_asset/custom/css/checkbox.css',
            ],
            'js' => [
                "https://freshcart.codescandy.com/assets/libs/rater-js/index.js",
                'client_asset/custom/js/collection/collection.js',
                'client_asset/custom/js/collection/comment.js',
            ],
            'model' => 'collection'
        ];
    }

    private function breadcrumb()
    {
        return [
            "detail" => [
                "title" => "Product Detail",
                "url" => route('client.product.detail')
            ]
        ];
    }


}
