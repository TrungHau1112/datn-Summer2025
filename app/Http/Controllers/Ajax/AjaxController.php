<?php
namespace App\Http\Controllers\Ajax;
use App\Http\Controllers\Controller;
use App\Repositories\Attribute\AttributeCategoryRepository;
use App\Repositories\Attribute\AttributeRepository;
use App\Services\Product\ProductService;

use Illuminate\Http\Request;
class AjaxController extends Controller
{


    protected $attributeCategoryRepository;
    protected $attributeRepository;
    protected $productService;
    public function __construct(
        AttributeCategoryRepository $attributeCategoryRepository,
        AttributeRepository $attributeRepository,
        ProductService $productService,
    ) {
        $this->attributeCategoryRepository = $attributeCategoryRepository;
        $this->attributeRepository = $attributeRepository;
        $this->productService = $productService;
    }
    public function getData(Request $request)
    {
        $model = $request['model'];
        // $request = json_decode(base64_decode($request->get('encodedParams')), true); // mã hoá :v
        $Controller = "\\App\\Http\\Controllers\\Admin\\" . ucfirst($model) . "Controller";
        $data = app($Controller)->getData($request);
        return $data;
    }
    public function getProduct(Request $request)
    {
        $array = $request->query();
        foreach ($request->query() as $key => $value) {
            $keyParts = preg_split('/(?=[A-Z])/', $key);
            if (count($keyParts) > 1) {
                $firstKey = $keyParts[0];
                if (!isset($array[$firstKey])) {
                    $array[$firstKey] = [];
                }
                if ($value != 0) {
                    $array[$firstKey][] = $value;
                }
            }
        }
        $products = $this->productService->findProduct($array);
        return view('admin.pages.product.product.components.filterProduct', compact('products'));
    }
    public function updateStatus(Request $request)
    {
        $data = $request->input();
        $serviceClass = loadClass($data['model'], 'Service');
        // dd($serviceClass->changeStatusByField($data));
        if ($serviceClass->changeStatusByField($data)) {
            return response()->json([
                'message' => 'Update Successfully',
                'code' => 10
            ]);
        }
        return response()->json([
            'message' => 'An issue occurred, Please try again',
            'code' => 11
        ]);
    }

    public function updateMultiple(Request $request)
    {
        $data = $request->input();
        $serviceClass = loadClass($data['model'], 'Service');
        if ($serviceClass->bulkChangeStatus($data)) {
            return response()->json([
                'message' => 'Update Successfully',
                'code' => 10
            ]);
        }
        return response()->json([
            'message' => 'An issue occurred, Please try again',
            'code' => 11
        ]);
    }

    public function deleteMultiple(Request $request)
    {
        $data = $request->input();
        $serviceClass = loadClass($data['model'], 'Service');
        if ($serviceClass->bulkDelete($data)) {
            return response()->json([
                'message' => 'Update Successfully',
                'code' => 10
            ]);
        }
        return response()->json([
            'message' => 'An issue occurred, Please try again',
            'code' => 11
        ]);
    }

    public function deleteItem(Request $request)
    {
        $data = $request->all();
        // dd($data['model']);
        $serviceClass = loadClass($data['model'], 'Service');
        if ($serviceClass->delete($data['id'])) {
            return successResponse();
        }
        return errorResponse();

    }

    public function restoreItem(Request $request)
    {
        $data = $request->all();
        $serviceClass = loadClass($data['model'], 'Service');
        if ($serviceClass->restore($data['id'])) {
            return successResponse();
        }
        return errorResponse();
    }

    public function destroyItem(Request $request)
    {
        $data = $request->all();
        $serviceClass = loadClass($data['model'], 'Service');
        if ($serviceClass->destroy($data['id'])) {
            return successResponse();
        }
        return errorResponse();
    }
    public function updateQuick(Request $request)
    {
        $data = $request->all();
        $className = "\\App\\Http\\Requests\\" . ucfirst($data['model']) . "\\Update" . ucfirst($data['model']) . "Request";
        if (class_exists($className)) {
            $rules = (new $className())->rules();
            if (isset($rules[$data['name']])) {
                $request->validate(
                    ['value' => $rules[$data['name']]]
                );
            }
        }
        $serviceClass = loadClass($data['model'], 'Repository');
        $item = $serviceClass->findById($data['id']);
        $item->{$data['name']} = $data['value'];
        $item->save();

        return successResponse(null, $item['message']);
    }



    public function getAttribute()
    {
        $attributes = $this->attributeCategoryRepository->getAll();
        return successResponse($attributes);
    }

    public function getAttributeValue(Request $request)
    {
        $payload = $request->all();
        // dd($payload);
        $attribute = $this->attributeRepository->searchValue($payload['attribute_id'], $payload['search']);

        $attributeFormat = $attribute->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->value
            ];
        })->all();
        return successResponse($attributeFormat);
    }

    public function loadAttributeValue(Request $request)
    {
        $attributeValues = json_decode(base64_decode($request->attributeValue), true);
        $attributeId = $request->attributeCatalogueId;
        $result = [];
        foreach ($attributeValues as $value_ids) {
            $result = array_merge($result, $value_ids);
        }

        $attributeValueData = $this->attributeRepository
            ->getByIds($result, $attributeId)
            ->map(function ($attributeValue) {
                return [
                    'id' => $attributeValue->id,
                    'text' => $attributeValue->value,
                ];
            })
            ->all();

        return successResponse($attributeValueData);
    }

    function test()
    {
        $users = \App\Models\User::paginate(10);
        $title = 'List User';
        return successResponse(
            $users
        );
    }

}