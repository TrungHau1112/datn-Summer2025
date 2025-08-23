<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Slide\SlideService;
use App\Repositories\Slide\SlideRepository;
// use App\Repositories\Collection\CollectionRepository;
use App\Services\Setting\SettingService;
use App\Repositories\Setting\SettingRepository;
use App\Models\Slide;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Traits\HasDynamicMiddleware;
class SettingController extends Controller implements HasMiddleware
{
    use HasDynamicMiddleware;
    public static function middleware(): array
    {
        return self::getMiddleware('Setting');
    }
    protected $sliderService;
    protected $sliderRepository;
    protected $collectionRepository;
    protected $settingService;
    protected $settingRepository;
    function __construct(
        SlideService $sliderService,
        SlideRepository $sliderRepository,
        // CollectionRepository $collectionRepository,
        SettingService $settingService,
        SettingRepository $settingRepository
    ) {
        $this->sliderService = $sliderService;
        $this->sliderRepository = $sliderRepository;
        // $this->collectionRepository = $collectionRepository;
        $this->settingService = $settingService;
        $this->settingRepository = $settingRepository;
    }


    public function index()
    {
        $config = $this->config();
        $config['breadcrumb'] = $this->breadcrumb('index');
        return view('admin.pages.setting.index', compact(
            'config'
        ));
    }

    public function slide(Request $request)
    {
        $config = $this->config();
        $config['model'] = 'collection';
        $config['breadcrumb'] = $this->breadcrumb('slider');
        $slides = $this->sliderRepository->getAll();
        return view('admin.pages.setting.slide', compact(
            'config',
            'slides',
        ));
    }

    public function sliderUpdate(Request $request)
    {
        $data = $request->all();
        Slide::create([
            'image' => $data['image'],
        ]);
        return redirect()->route('setting.slide')->with('success', 'Cập nhật thành công');
    }

    public function sliderDelete($id)
    {
        Slide::find($id)->delete();
        return redirect()->route('setting.slide')->with('success', 'Xóa thành công');
    }

    public function general()
    {
        $config = $this->config();
        $config['model'] = 'collection';
        $config['breadcrumb'] = $this->breadcrumb('general');
        $setting = $this->settingRepository->getAll()->first();
        return view('admin.pages.setting.general', compact(
            'config',
            'setting'
        ));
    }

    public function generalUpdate(Request $request)
    {
        if ($this->settingService->update($request)) {
            return redirect()->route('setting.general')->with('success', 'Cập nhật thành công');
        }
        return redirect()->route('setting.general')->with('error', 'Cập nhật thất bại');
    }



    public function config()
    {
        return [
            'css' => [
            ],
            'js' => [

            ],
            'model' => 'setting',
        ];
    }

    private function breadcrumb($key)
    {
        $breadcrumb = [
            'index' => [
                'name' => 'Cài đặt hệ thống',
                'list' => ['Cài đặt hệ thống']
            ],
            'slider' => [
                'name' => 'Quản lý slider',
                'list' => ['Cài đặt hệ thống', 'Quản lý slider']
            ],

            'general' => [
                'name' => 'Thông tin website',
                'list' => ['Cài đặt hệ thống', 'Thông tin website']
            ],
            'update' => [
                'name' => 'Cập nhật người dùng',
                'list' => ['QL người dùng', 'Cập nhật người dùng']
            ],
            'delete' => [
                'name' => 'Xóa người dùng',
                'list' => ['QL người dùng', 'Xóa người dùng']
            ]
        ];
        return $breadcrumb[$key];
    }

}
