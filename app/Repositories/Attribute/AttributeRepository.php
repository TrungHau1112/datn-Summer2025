<?php  
namespace App\Repositories\Attribute;
use App\Repositories\BaseRepository;
use App\Models\Attribute;

class AttributeRepository extends BaseRepository{
    
    protected $model;

    public function __construct(
        Attribute $model
    ){
        $this->model = $model;
    }

    public function searchValue($attribute_id, $value){
        return $this->model
            ->where('attribute_category_id', $attribute_id)
            ->where('value', 'like', '%' . $value . '%')
            ->get();
    }

    public function getByIds(array $ids,  int $attribute_id){
        return $this->model
            ->whereIn('id', $ids)
            ->where('attribute_category_id', $attribute_id)
            ->get();
    }
    

    


}