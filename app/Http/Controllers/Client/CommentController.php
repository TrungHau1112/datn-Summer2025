<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Comment\CommentRepository;
use App\Services\Comment\CommentService;
use App\Repositories\Collection\CollectionRepository;
class CommentController extends Controller
{
    protected $commentService;
    protected $commentRepository;
    protected $collectionRepository;
    public function __construct(
        CommentRepository $commentRepository,
        CommentService $commentService,
        CollectionRepository $collectionRepository,
        )   
    {
        $this->commentRepository = $commentRepository;
        $this->commentService = $commentService;
        $this->collectionRepository = $collectionRepository;
    }
 
    public function getComments(Request $request, $slug)
    {
        $collection = $this->collectionRepository->findByField('slug', $slug)->first();
        $comments = $this->commentRepository->getLimitComments(
            'collection_id',
            [$collection->id],
            (int) $request->limit
        );
        $count_comment_current = $comments->count();
        $count_comments = $collection->comments->whereNull('parent_id')->count();
        return view('client.pages.collection.components.comment', compact('collection', 'comments', 'count_comments', 'count_comment_current'))->render();
    }
    public function getReplies(Request $request, $comment_id, $limit)
    {
        $comment = $this->commentRepository->findById($comment_id);
        $comment_childs = $this->commentRepository->getLimitReplies(
            'parent_id',
            [$comment_id],
            (int) $limit
        );
        $count_comment_childs_current = $request->limit;
        $count_comment_childs = $comment->children->whereNotNull('parent_id')->count();
        return view('client.pages.collection.components.comment_child', compact('comment_childs', 'comment', 'count_comment_childs_current', 'count_comment_childs'))->render();
    }
    public function store(Request $request)
    {
        $forbiddens = DB::table('forbidden_words')->pluck('word');
        foreach ($forbiddens as $forbidden) {
            if (strpos($request->content, $forbidden) !== false) {
                return false;
            }
        }
        $comment = $this->commentService->create($request);
        $user = Auth::user();
        $data = array_merge($request->all(), ['user' => $user]);
        return $data;
    }
    public function update(Request $request)
    {
        $comment = $this->commentService->update($request, $request->id);
        return successResponse($comment);
    }
    public function remove(Request $request)
    {
        $comment = $this->commentService->delete($request->id);
        if ($comment) {
            return successResponse($comment);
        } else {
            return errorResponse('', 400);
        }
    }

    private function config()
    {
        return [
            'css' => [
            ],
            'js' => [
                "https://freshcart.codescandy.com/assets/libs/rater-js/index.js",
            ],
            'model' => 'comment'
        ];
    }


}
