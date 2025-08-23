<?php
namespace App\Http\Controllers\Ajax;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Location\ProvinceRepository;
use App\Repositories\Location\DistrictRepository;
use App\Repositories\Location\WardRepository;



class LocationController extends Controller
{

    protected $provinceRepository;
    protected $districtRepository;
    protected $wardRepository;


    public function __construct(
        ProvinceRepository $provinceRepository,
        DistrictRepository $districtRepository,
    ) {
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
    }

    public function getLocation(Request $request)
    {
        $get = $request->input();//['target' => 'districts', 'data' => ['location_id' => 1]];
        $html = '';
        if ($get['target'] == 'districts') {
            $province = $this->provinceRepository->findByField('code', $get['data']['location_id'], ['code', 'name'], ['districts'])->first();
            if ($province) {
                $html = $this->renderHtml($province->districts);
            }
        } else if ($get['target'] == 'wards') {
           if(isset($get['data']['location_id'])){
            $district = $this->districtRepository->findByField('code', $get['data']['location_id'], ['code', 'name'], ['wards'])->first(); 
            if ($district) {
                $html = $this->renderHtml($district->wards, '[Chọn Phường/Xã]'); 
            }
           }
        }
        $response = [
            'html' => $html
        ];
        return response()->json($response);
    }


    public function renderHtml($districts, $root = '[Chọn Quận/Huyện]')
    {
        $html = '<option value="" selected >' . $root . '</option>';
        foreach ($districts as $district) {
            $html .= '<option value="' . $district->code . '">' . $district->name . '</option>';
        }
        return $html;
    }

}