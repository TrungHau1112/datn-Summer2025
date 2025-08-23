<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Comment\ForbiddenWordService;
use App\Repositories\Comment\ForbiddenWordRepository;
use App\Repositories\Comment\CommentForbiddenWordRepository;
use App\services\Comment\CommentForbiddenWordService;
use App\Models\ForbiddenWord;

use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
class CommentForbiddenWordController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('CommentForbiddenWord'); 
    }
    protected $forbiddenwordService;
    protected $forbiddenwordRepository;
    function __construct(
        CommentForbiddenWordService $forbiddenwordService,
        CommentForbiddenWordRepository $forbiddenwordRepository

    ) {
        $this->forbiddenwordService = $forbiddenwordService;
        $this->forbiddenwordRepository = $forbiddenwordRepository;
    }

    public function index(Request $request)
    {
        $forbiddenwords = $this->forbiddenwordService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        $config['method'] = 'index';
        return view('admin.pages.comment.forbiddenword.index', compact('config', 'forbiddenwords'));
    }

    public function getData(Request $request)
    {
        $forbiddenwords = $this->forbiddenwordService->paginate($request);
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        $config['method'] = 'index';

        return view('admin.pages.comment.forbiddenword.components.table', compact('config', 'forbiddenwords'));
    }

    public function create()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('create');
        $config['method'] = 'create';
        return view('admin.pages.comment.forbiddenword.index', compact(
            'config',
        ));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'word' => 'required|string|unique:forbidden_words',
                // 'actions' => 'required|array',
                // 'actions.*' => 'in:delete,ban_user',
            ],
            [
                'word.required' => 'Từ không được để trống',
                'word.unique' => 'Từ cấm đã tồn tại',
                // 'actions.required' => 'Phải chọn xử lý',
                // 'actions.*.in' => 'Xử lý phải là xóa hoặc cấm người dùng',
            ]
        );
        if ($this->forbiddenwordService->create($request)) {
            return redirect()->route('CommentForbiddenWord.index')->with('success', 'Thêm từ cấm thành công');
        }
        return redirect()->route('CommentForbiddenWord.index')->with('error', 'Thêm từ cấm thất bại');
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Danh sách từ cấm',
                'list' => ['Danh sách từ cấm']
            ],
            'create' => [
                'name' => 'Thêm từ cấm',
                'list' => ['QL nội dung bình luận', 'Thêm từ cấm']
            ]
        ];
        return $breadcrumb[$key];
    }

    private function config()
    {
        return [

            'css' => [],
            'js' => [],
            'model' => 'CommentForbiddenWord'
        ];
    }
}
