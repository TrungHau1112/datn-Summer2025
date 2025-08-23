<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Comment\ReviewRepository;
use App\Services\Review\ReviewService;
use App\Models\Review;

use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
class ReviewController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Review'); 
    }
    protected $reviewRepository;
    protected $reviewService;
    function __construct(
        ReviewRepository $reviewRepository,
        ReviewService $reviewService
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->reviewService = $reviewService;
    }
    public function index(Request $request)
    {
        $reviews = $this->reviewService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.comment.review.index', compact(
            'config',
            'reviews'
        ));
    }
    public function getData(Request $request)
    {
        $reviews = $this->reviewService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.comment.review.components.table', compact(
            'config',
            'reviews'
        ));
    }


    public function reply($id)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('reply');
        $review = $this->reviewRepository->findById($id, ['user', 'children']);
        return view('admin.pages.comment.review.reply', compact(
            'config',
            'review'
        ));
    }

    public function store(Request $request, $id)
    {
        $this->reviewService->reply($request, $id);
        return redirect()->back()->with('success', 'Phản hồi đánh giá thành công');
    }


    public function edit($id)
    {
    }

    public function delete($id)
    {
        $review = $this->reviewRepository->findById($id);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('delete');
        $config['method'] = 'delete';
        return view('admin.pages.comment.review.delete', compact(
            'config',
            'review'
        ));
    }


    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Quản lý đánh giá',
                'list' => ['Đánh giá', 'Danh sách']
            ],
            'reply' => [
                'name' => 'Phản hồi đánh giá',
                'list' => ['Đánh giá', 'Phản hồi đánh giá']
            ],
            'delete' => [
                'name' => 'Xóa đánh giá',
                'list' => ['đánh giá', 'Xóa đánh giá']
            ]

        ];
        return isset($breadcrumb[$key]) ? $breadcrumb[$key] : 'Trang chủ';
    }
    private function config()
    {
        return [
            'css' => [],
            'js' => [],
            'model' => 'review'
        ];
    }
}
