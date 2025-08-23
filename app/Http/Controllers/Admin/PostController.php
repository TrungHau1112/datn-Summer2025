<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Repositories\Post\PostRepository;
use App\Services\Post\PostService;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;


class PostController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Post');
    }
    protected $postRepository;
    protected $postService;
    function __construct(
        PostRepository $postRepository,
        PostService $postService,
    ) {
        $this->postRepository = $postRepository;
        $this->postService = $postService;
    }
    public function index(Request $request)
    {
        // dd($this->postRepository->findByField('id',93)->first());
        $posts = $this->postService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.post.index', compact(
            'config',
            'posts'
        ));
    }
    public function getData($request)
    {
        $posts = $this->postService->paginate($request);
        $config = $this->config();
        return view('admin.pages.post.components.table', compact('posts', 'config'));
    }
    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        // random sku
        return view('admin.pages.post.save', compact(
            'config',
        ));
    }

    public function store(StorePostRequest $request)
    {
        if ($this->postService->create($request)) {
            return redirect()->route('post.index')->with('success', 'Tạo bài viết thành công');
        } else {
            return redirect()->back()->with('error', 'Tạo bài viết thất bại');
        }
    }

    public function edit($id)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        $post = $this->postRepository->findByid($id, ['user']);
        return view('admin.pages.post.save', compact(
            'config',
            'post',
        ));
    }

    public function update(UpdatePostRequest $request, $id)
    {
        if ($this->postService->update($request, $id)) {
            return redirect()->route('post.index')->with('success', 'Cập nhật bài viết thành công');
        } else {
            return redirect()->back()->with('error', 'Cập nhật bài viết thất bại');
        }
    }


    public function trash()
    {
        $posts = $this->postRepository->getOnlyTrashed();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('trash');
        return view('admin.pages.post.trash', compact(
            'config',
            'posts'
        ));
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Danh sách bài viết',
                'list' => ['Danh sách bài viết']
            ],
            'create' => [
                'name' => 'Tạo bài viết',
                'list' => ['QL bài viết', 'Tạo bài viết']
            ],
            'update' => [
                'name' => 'Cập nhật bài viết',
                'list' => ['QL bài viết', 'Cập nhật bài viết']
            ],
            'delete' => [
                'name' => 'Xóa bài viết',
                'list' => ['QL bài viết', 'Xóa bài viết']
            ],
            'trash' => [
                'name' => 'Thùng rác',
                'list' => ['QL bài viết', 'Thùng rác']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [
                'admin_asset\plugins\nice-select\css\nice-select.css',
                'https://unpkg.com/slim-select@latest/dist/slimselect.css'
            ],
            'js' => [
                'admin_asset\plugins\nice-select\js\jquery.nice-select.min.js',
                'https://unpkg.com/slim-select@latest/dist/slimselect.min.js'
            ],
            'model' => 'post'
        ];
    }


}
