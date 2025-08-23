<?php
namespace App\Services\Setting;
use App\Services\BaseService;
use App\Repositories\Setting\SettingRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;



class SettingService extends BaseService
{
    protected $settingRepository;
    public function __construct(
        SettingRepository $settingRepository
    ) {
        $this->settingRepository = $settingRepository;
    }


    public function update($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', '_method', 'send');
            $payload['site_social'] = json_encode($payload['site_social']);
            $this->settingRepository->update(1, $payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}