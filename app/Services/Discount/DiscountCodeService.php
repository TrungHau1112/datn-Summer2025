<?php
namespace App\Services\Discount;

use App\Services\BaseService;
use App\Repositories\Discount\DiscountCodeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DiscountCodeService extends BaseService
{

    protected $discountCodeRepository;

    public function __construct(DiscountCodeRepository $discountCodeRepository)
    {
        $this->discountCodeRepository = $discountCodeRepository;
    }

    /**
     * Chuẩn bị các tham số phân trang cho mã giảm giá
     */
    private function paginateArguments($request)
    {
        return [
            'keyword' => [
                'search' => $request->input('keyword'),
                'field' => ['code', 'title']
            ],
            'condition' => [
                'publish' => $request->integer('publish'),
                'discount_type' => $request->integer('discount_type'),
                'deleted_at' => null,
            ],
            'sort' => $request->input('sort')
                ? array_map('trim', explode(',', $request->input('sort')))
                : ['id', 'desc'],
            'perpage' => $request->integer('perpage') ?? 20,
        ];
    }

    /**
     * Phân trang mã giảm giá dựa trên các tham số yêu cầu
     */
    public function paginate($request)
    {
        $arguments = $this->paginateArguments($request);
        $cacheKey = 'pagination: ' . md5(json_encode($arguments));
        $users = $this->discountCodeRepository->pagination($arguments);
        return $users;
    }

    /**
     * Tạo mã giảm giá mới
     */
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send', 'page');
            $payload['discount_value'] = convertNumber($payload['discount_value']);
            $payload['min_order_amount'] = convertNumber($payload['min_order_amount']);
            // dd($payload);
            $discountCode = $this->discountCodeRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            die();
            $this->log($e); // Ghi log lỗi nếu có
            // return false;
        }
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send', 'page', '_method');
            $payload['discount_value'] = convertNumber($payload['discount_value']);
            $payload['min_order_amount'] = convertNumber($payload['min_order_amount']);
            // dd($payload);
            $discountCode = $this->discountCodeRepository->update($id, $payload);
            DB::commit();
            return $discountCode;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e); // Ghi log lỗi nếu có
            return false;
        }
    }

    /**
     * Xóa mã giảm giá
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            // $discountCode = $this->discountCodeRepository->delete($id);
            $this->discountCodeRepository->update($id, ['deleted_at' => now()]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e); // Ghi log lỗi nếu có
            return false;
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->discountCodeRepository->restore($id);
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
            $this->discountCodeRepository->destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }
}
