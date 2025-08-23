<?php

namespace App\Services\Category;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Category\CategoryRepository;



class CategoryService extends BaseService
{

    protected $categoryRepository;

    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }


    private function paginateAgrument($request)
    {
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['name']
            ],
            'condition' => [
                'publish' => isset($request['publish'])
                    ? (int) $request['publish']
                    : null,
                'parent_id' => null,
                'deleted_at' => null
            ],
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
        $categories = $this->categoryRepository->pagination($agruments);
        return $categories;
    }

    public function paginationCategory($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $categories = $this->categoryRepository->paginationCategory($agruments);
        return $categories;
    }

    public function paginationRoom($request)
    {
        $agruments = $this->paginateAgrument($request);
        $cacheKey = 'pagination: ' . md5(json_encode($agruments));
        $categories = $this->categoryRepository->paginationRoom($agruments);
        return $categories;
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token']);
            $payload['slug'] = getSlug($payload['name']);
            $payload['parent_id'] = $payload['parent_id'] == 0 ? null : $payload['parent_id'];
            $category = $this->categoryRepository->create($payload);
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
            $payload['parent_id'] = $payload['parent_id'] == 0 ? null : $payload['parent_id'];

            $category = $this->categoryRepository->update($id, $payload);

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
            $category = $this->categoryRepository->findById($id);
            $this->categoryRepository->update($id, ['deleted_at' => now()]);
            // if ($category->children()->count() == 0) {
            //     $this->categoryRepository->delete($id);
            DB::commit();
            return true;
            // }
        } catch (\Exception $e) {
            DB::rollback();
            // echo $e->getMessage();die();
            $this->log($e);
            return false;
        }
    }


    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->categoryRepository->restore($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->categoryRepository->destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }



    public function renderCategoryOptions($categories, $category = null, $level = 0)
    {
        $html = '';
        foreach ($categories as $category) {
            $indent = str_repeat('', $level * 4);
            $html .= '<option value="' . $category->id . '">' . $indent . $category->name . '</option>';
            // if ($category->children->isNotEmpty()) {
            //     $html .= $this->renderCategoryOptions($category->children, $level + 1);
            //     //         foreach ($categories as $childCategory) {
            //     //             if ($category === null || $childCategory->id !== $category->id) {
            //     //                 $indent = str_repeat('&nbsp;', $level * 4);
            //     //                 $html .= '<option value="' . $childCategory->id . '">' . $indent . $childCategory->name . '</option>';
            //     //                 if ($childCategory->children->isNotEmpty()) {
            //     //                     $html .= $this->renderCategoryOptions($childCategory->children, $category, $level + 1);
            //     //                 }
            // }
        }
        return $html;
    }
}
