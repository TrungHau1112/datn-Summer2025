<?php

namespace App\Services\Post;

use App\Services\BaseService;
use App\Repositories\Post\PostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PostService extends BaseService
{

    protected $cartRepository;
    protected $postRepository;
    public function __construct(
        PostRepository $postRepository,
    ) {
        $this->postRepository = $postRepository;
    }


    private function paginateAgrument($request)
    {
        $defaultSort = ['id', 'asc'];

        $condition = [
            'publish' => isset($request['publish']) ? (int) $request['publish'] : null,
            'deleted_at' => null,
        ];
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' =>  ['title', 'excerpt', 'content'],
            ],
            'condition' => $condition,
            'sort' => isset($request['sort']) && $request['sort'] != 0
                ? explode(',', $request['sort'])
                : $defaultSort,
            'perpage' => (int) ($request['perpage'] ?? 10),
        ];
    }

    public function paginate($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $users = $this->postRepository->pagination($agruments);
        return $users;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send', '_method');
            $payload['slug'] = getSlug($payload['title']);
            $payload['user_id'] = Auth::user()->id;
            $this->postRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
            // $this->log($e);
            // return false;
        }
    }



    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send', 'page', '_method');
            $payload['slug'] = getSlug($payload['title']);
            $this->postRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
        }
    }


    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->findById($id);
            $this->postRepository->update($id, ['deleted_at' => now()]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->postRepository->restore($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->postRepository->destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }
}
