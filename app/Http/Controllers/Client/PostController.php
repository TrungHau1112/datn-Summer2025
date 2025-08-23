<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Post\PostService;
use App\Repositories\Post\PostRepository;
use App\Models\User;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;

    public function __construct(
        PostService $postService,
        PostRepository $postRepository
    ) {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        $title = 'Danh sách bài viết';
        $config = $this->config();
        $posts = $this->postRepository->findByField('publish', 1)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        // dd($posts);
        return view('client.pages.post.index', compact(
            'config',
            'title',
            'posts'
        ));
    }

    public function detail(Request $request, $slug)
    {
        $post = $this->postRepository->findByField('slug', $slug)->first();
        if (!$post) {
            return errorResponse('Không tìm thấy bài viết');
        }
        $postRelateds = $this->postRepository->findByField('user_id', $post->user_id)
            ->where('id', '!=', $post->id)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $config = $this->config();
        return view('client.pages.post.detail', compact(
            'config',
            'post','postRelateds'
        ));
    }

    private function config()
    {
        return [
            'css' => [
                'client_asset/custom/css/post.css'
            ],
            'js' => [],
            'model' => 'user'
        ];
    }
}
