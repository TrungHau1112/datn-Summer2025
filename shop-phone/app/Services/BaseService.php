<?php  
namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BaseService {
    
    public function __construct(){

    }

    public function log($e){
        Log::error('Error Message: '. $e->getMessage());
        Log::error('File: '. $e->getFile(). ' Line: '. $e->getLine());
        Log::error('Stack trace: ' . $e->getTraceAsString());
    }

    public function changeStatusByField($data){
        DB::beginTransaction();
        try {
            $repositoryClass = loadClass($data['model'], 'Repository');
            $payload[$data['field']] = ($data['value'] == 1) ? 2 : 1;
            $repositoryClass->update($data['id'], $payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
           DB::rollback();
            echo $e->getMessage();die();
            // $this->log($e);
            // return false;
        }
    }

    public function bulkChangeStatus($data){
        DB::beginTransaction();
        try {
            $repositoryClass = loadClass($data['model'], 'Repository');
            $option = explode('-', $data['option']);
            // dd($option);
            $payload[$option[0]] = (int)$option[1];
            $agruments = [
                'whereInField' => 'id',
                'whereIn' => $data['ids'],
                'payload' => $payload
            ];
            $repositoryClass->updateByWhereIn(...$agruments);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();die();
            // $this->log($e);
            // return false;
        }
    }

    public function bulkDelete($data){
        DB::beginTransaction();
        try {
            $repositoryClass = loadClass($data['model'], 'Repository');
            $agruments = [
                'whereInField' => 'id',
                'whereIn' => $data['ids'],
            ];
            $repositoryClass->deleteByWhereIn(...$agruments);

            DB::commit();
            return true;
        } catch (\Exception $e) {
           DB::rollback();
            echo $e->getMessage();die();
            // $this->log($e);
            // return false;
        }
    }

    public function formatJson($data)
    {   if(!is_array($data || $data != null || $data != '')){
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }

    public function filterPrice($price) {
        $priceString = str_replace(" VNĐ", "", $price);

        // Bước 2: Loại bỏ dấu chấm
        $priceString = str_replace(".", "", $priceString);

        // Bước 3: Chuyển đổi thành số nguyên
        return (int)$priceString;
    }
    

}