<?php

namespace App\Services\Review;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Comment\ReviewRepository;



class ReviewService extends BaseService
{

    protected $reviewRepository;

    public function __construct(
        ReviewRepository $reviewRepository
    ) {
        $this->reviewRepository = $reviewRepository;
    }


    private function paginateAgrument($request)
    {
        $condition = [
            'parent_id' => null,
        ];

        if (isset($request['rating']) && $request['rating'] != 0) {
            $condition['rating'] = $request['rating'] ?? 0;
        }

        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['content'],
            ],
            'condition' => $condition,
            'sort' => isset($request['sort']) && $request['sort'] != 0
                ? explode(',', $request['sort'])
                : ['id', 'asc'],
            'perpage' => (int) (isset($request['perpage']) && $request['perpage'] != 0 ? $request['perpage'] : 10),
        ];

    }

    public function paginate($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $categories = $this->reviewRepository->pagination($agruments);
        return $categories;
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token']);
            $payload['slug'] = getSlug($payload['name']);
            $review = $this->reviewRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method']);
            $payload['slug'] = getSlug($payload['name']);
            $review = $this->reviewRepository->update($id, $payload);
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
            $this->reviewRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }
    public function renderreviewOptions($categories, $level = 0)
    {
        $html = '';
        foreach ($categories as $review) {
            $indent = str_repeat('&nbsp;', $level * 4);
            $html .= '<option value="' . $review->id . '">' . $indent . $review->name . '</option>';
            if ($review->children->isNotEmpty()) {
                $html .= $this->renderreviewOptions($review->children, $level + 1);
            }
        }
        return $html;
    }

    public function reply($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method']);
            $hasReply = $this->reviewRepository->findByField('parent_id', $id)->first();
            if ($hasReply) {
                $this->reviewRepository->update($hasReply->id, $payload);
            } else {
                $payload['parent_id'] = $id;
                $this->reviewRepository->create($payload);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }

}
