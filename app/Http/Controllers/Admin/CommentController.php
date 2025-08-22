<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Comment\CommentService;
use App\Repositories\Comment\CommentRepository;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\ForbiddenWord;
use App\Models\User;


use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
class CommentController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Comment'); 
    }
    protected $commentService;
    protected $commentRepository;
    function __construct(
        CommentService $commentService,
        CommentRepository $commentRepository

    ) {
        $this->commentService = $commentService;
        $this->commentRepository = $commentRepository;
    }

    public function index(Request $request)
    {
        $comments = $this->commentService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.comment.index', compact(
            'config',
            'comments',
        ));
    }

    public function getData(Request $request)
    {
        $comments = $this->commentService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.comment.components.table', compact(
            'config',
            'comments',
        ));
    }

    public function reply(Request $request, $id)
    {
        $parentComment = $this->commentRepository->findById($id);
        // $replies = Comment::where('parent_id', $id)->whereNull('deleted_at')->with('replies')->get();
        $replies = Comment::where('parent_id', $id)->with('replies')->get();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('reply');
        return view('admin.pages.comment.reply', compact(
            'config',
            'parentComment',
            'replies'
        ));
    }


    public function create()
    {
        // $comments = Comment::all()->where('deleted_at', null)->all();
        $comments = Comment::all()->all();
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        return view('client.pages.comment.test-comment', compact(
            'config',
            'comments'
        ));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->withErrors('Bạn cần đăng nhập để bình luận.');
        }
        if ($this->commentService->isUserBanned(Auth::user())) {
            $time = Auth::user()->ban_expires_at;
            return redirect()->route('comment.create')->with('error', 'Bạn đã bị cấm bình luận đến ' . $time);
        }
        if ($this->commentService->create($request)) {
            return redirect()->route('comment.create')->with('success', 'Bình luận thành công');
        }
        return redirect()->route('comment.create')->with('error', 'Bình luận thất bại');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        if ($this->commentService->update($request, $id)) {
            return redirect()->route('comment.index')->with('success', 'Cập nhật bình luận thành công.');
        }
        return redirect()->route('comment.index')->with('error', 'Cập nhật bình luận thất bại');
    }


    public function edit($id)
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('update');
        $config['method'] = 'edit';
        return view('admin.pages.comment.save', compact(
            'config',
            'comment'
        ));
    }
    public function destroy($id)
    {
        if ($this->commentService->delete($id)) {
            return redirect()->route('user.index')->with('success', 'Xóa bình luận thành công');
        }
        return redirect()->route('user.index')->with('error', 'Xóa bình luận thất bại');
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Danh sách bình luận',
                'list' => ['Danh sách bình luận']
            ],
            'create' => [
                'name' => 'Tạo bình luận',
                'list' => ['QL bình luận', 'Tạo bình luận']
            ],
            'update' => [
                'name' => 'Cập nhật bình luận',
                'list' => ['QL bình luận', 'Cập nhật bình luận']
            ],
            'delete' => [
                'name' => 'Xóa bình luận',
                'list' => ['QL bình luận', 'Xóa bình luận']
            ],
            'reply' => [
                'name' => 'Danh sách trả lời',
                'list' => ['QL bình luận']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [
            'css' => [],
            'js' => [],
            'model' => 'comment'
        ];
    }
}
