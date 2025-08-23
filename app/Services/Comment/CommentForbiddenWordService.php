<?php

namespace App\Services\Comment;

use App\Services\BaseService;
use App\Repositories\Comment\ForbiddenWordRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class CommentForbiddenWordService extends BaseService
{
    protected $forbiddenwordRepository;
    public function __construct(
        ForbiddenWordRepository $forbiddenwordRepository
    ) {
        $this->forbiddenwordRepository = $forbiddenwordRepository;
    }

    private function paginateAgrument($request)
    {
        return [
            'keyword' => [
                'search' => $request->input('keyword'),
                'field' => ['word', 'actions']
            ],
            'sort' => $request->input('sort')
                ? array_map('trim', explode(',', $request->input('sort')))
                : ['id', 'desc'],
            'perpage' => $request->integer('perpage') ?? 20,
        ];
    }
    public function paginate($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));

        $forbiddenwords = $this->forbiddenwordRepository->pagination($agruments);

        return $forbiddenwords;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $forbiddenword = $this->forbiddenwordRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            die();
        }
    }
    // public function update($request, $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $payload = $request->except(['_token', 'send', '_method']);
    //         $forbiddenword = $this->forbiddenwordRepository->update($id, $payload);
    //         DB::commit();
    //         return true;
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         $this->log($e);
    //         return false;
    //     }
    // }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->forbiddenwordRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }
}
