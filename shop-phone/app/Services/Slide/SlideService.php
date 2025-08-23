<?php
namespace App\Services\Slide;
use App\Services\BaseService;
use App\Repositories\Slide\SlideRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;



class SlideService extends BaseService
{
    protected $slideRepository;
    public function __construct(
        SlideRepository $slideRepository
    ) {
        $this->slideRepository = $slideRepository;
    }


    private function paginateAgrument($request)
    {
        // dd($request);
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['name', 'email', 'phone', 'address', 'created_at']
            ],
            'condition' => [
                'publish' => isset($request['publish'])
                    ? (int) $request['publish']
                    : null,
            ],
            'sort' => isset($request['sort']) && $request['sort'] != 0
                ? explode(',', $request['sort'])
                : ['id', 'asc'],
            'perpage' => (int) (isset($request['perpage']) && $request['perpage'] != 0 ? $request['perpage'] : 10),
        ];
    }


    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->slideRepository->delete($id);
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


}