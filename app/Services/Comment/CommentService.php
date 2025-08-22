<?php

namespace App\Services\Comment;

use App\Services\BaseService;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\ForbiddenWord;


class CommentService extends BaseService
{
    protected $commentRepository;
    protected $userRepository;

    public function __construct(
        CommentRepository $commentRepository,
        UserRepository $userRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
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
    public function paginate($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $comments = $this->commentRepository->paginationComment($agruments);
        return $comments;
    }

    public function handleForbiddenWords($id)
    {
        $comment = $this->commentRepository->findById($id);
        if ($comment) {
            $forbiddenWords = ForbiddenWord::all();
            foreach ($forbiddenWords as $word) {
                if (preg_match("/\b{$word->word}\b/i", $comment->content)) {
                    $actions = $word->actions;
                    foreach ($actions as $action) {
                        if ($action === 'delete') {
                            $this->commentRepository->delete($comment->id);
                        }
                        if ($action === 'ban_user') {
                            $this->banUser($comment->user_id);
                        }
                    }
                    break;
                }
            }
        }
        return true;
    }


    public function banUser($id)
    {
        $payload['is_banned'] = true;
        $payload['ban_expires_at'] = now()->addHours(12);

        try {
            return $this->userRepository->update($id, $payload);
        } catch (\Exception $e) {
            $this->log($e);
            return false;
        }
    }

    public function isUserBanned($user)
    {
        //kiểm tra người dungf có bị cấm không, thời gian cấm bình luận, so sánh mốc tgian đó với mốc tgian hiện tại
        return $user->is_banned && isset($user->ban_expires_at) && (new \DateTime($user->ban_expires_at) > new \DateTime());
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $request['user_id'] = Auth::user()->id;
            $request['collection'] = (int) $request['collection'];
            $request['parent_id'] = (int) $request['parent_id'] == 0 ? null :  (int) $request['parent_id'];
            $payload = $request->except(['_token', 'send']);
            $comment = $this->commentRepository->create($payload);
            // $this->handleForbiddenWords($comment->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
        }
    }
    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method']);
            $comment = $this->commentRepository->update($id, $payload);
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
            $childs = $this->commentRepository->getChilds($id)->get();
            foreach ($childs as $child) {
                $this->commentRepository->delete($child->id);
            }
            $this->commentRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            $this->log($e);
            return false;
        }
    }
}
