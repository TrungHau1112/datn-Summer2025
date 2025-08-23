<?php
namespace App\Services\Attribute;
use App\Services\BaseService;
use App\Repositories\Attribute\AttributeCategoryRepository;
use App\Repositories\Attribute\AttributeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\Attribute;


class AttributeCategoryService extends BaseService
{

    protected $attributeCategoryRepository;
    protected $attributeRepository;

    public function __construct(
        AttributeCategoryRepository $attributeCategoryRepository,
        AttributeRepository $attributeRepository
    ) {
        $this->attributeCategoryRepository = $attributeCategoryRepository;
        $this->attributeRepository = $attributeRepository;
    }


    private function paginateAgrument($request)
    {
        return [
            'keyword' => [
                'search' => $request['keyword'] ?? '',
                'field' => ['name'],
            ],
            'condition' => [
                'publish' => isset($request['publish'])
                    ? (int) $request['publish']
                    : null,
                'deleted_at' => null,
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
        $users = $this->attributeCategoryRepository->pagination($agruments);
        return $users;
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $id_attribute = $this->attributeCategoryRepository->create([
                'name' => $payload['name'],
                'publish' => $payload['publish'],
            ])->id;
            if ($request->has('attribute_value')) {
                $newDataEdit = [];
                foreach ($request->input('attribute_value') as $key => $values) {
                    $newDataEdit[$key] = $values[0];
                }
                $attribute_values = [];
                foreach ($newDataEdit as $key => $value) {
                    $attribute_values[] = [
                        'value' => $value,
                        'attribute_category_id' => $id_attribute,
                    ];
                }
                $this->attributeRepository->insert($attribute_values);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            // $this->log($e);
            // return false;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send', '_method']);
            $id_attribute = $id;
            $this->attributeCategoryRepository->update($id, $payload);
            $oldData = $this->attributeRepository->findByField('attribute_category_id', $id)->pluck('value', 'id')->toArray();
            $newData = $request->input('attribute_value', []);
            $newDataEdit = [];
            foreach ($newData as $key => $values) {
                $newDataEdit[$key] = $values[0];
            }
            if (empty($newDataEdit)) {
                foreach ($oldData as $id => $value) {
                    $this->attributeRepository->delete($id);
                }
            } else {
                foreach ($oldData as $id => $value) {
                    if (array_key_exists($id, $newDataEdit)) {
                        if ($newDataEdit[$id] !== $value) {
                            $this->attributeRepository->update($id, [
                                'value' => $newDataEdit[$id],
                            ]);
                        }
                        unset($newDataEdit[$id]);
                    } else {
                        $this->attributeRepository->delete($id);
                    }
                }

                foreach ($newDataEdit as $key => $value) {
                    if (!in_array($value, $oldData)) {
                        $this->attributeRepository->create([
                            'value' => $value,
                            'attribute_category_id' => $id_attribute,
                        ]);
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
            // $this->log($e);
            // return false;
        }
    }


    public function delete($id)
    {
        DB::beginTransaction();
        try {
            // $this->attributeCategoryRepository->delete($id);
            $this->attributeCategoryRepository->update($id, ['deleted_at' => now()]);
            DB::commit();
            return true;
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
            $this->attributeCategoryRepository->restore($id);
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
            $this->attributeCategoryRepository->destroy($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->log($e);
            return false;
        }
    }



}