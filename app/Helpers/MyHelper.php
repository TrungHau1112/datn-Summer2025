<?php
use Illuminate\Support\Carbon;

if (!function_exists('loadClass')) {
    function loadClass($modelName = '', $classType = '', $isSubFolder = true)
    {

        $type = [
            'Service' => 'Services',
            'Repository' => 'Repositories'
        ];

        $modelParts = preg_split('/(?=[A-Z])/', $modelName);
        $baseModel = $modelParts[1];
        if ($baseModel == 'Staff') {
            $baseModel = 'User';
        }
        $classWithSubFolder = 'App\\' . $type[$classType] . '\\' . $baseModel . '\\' . $baseModel . $classType;
        $classFullWithoutSubFolder = 'App\\' . $type[$classType] . '\\' . $modelName . $classType;
        $classFullWithSubFolder = 'App\\' . $type[$classType] . '\\' . $baseModel . '\\' . $modelName . $classType;
        $classWithoutSubFolder = 'App\\' . $type[$classType] . '\\' . $baseModel . $classType;

        // dd(class_exists($classWithSubFolder),
        //     class_exists($classFullWithSubFolder),
        //     class_exists($classWithoutSubFolder),
        //     class_exists($classFullWithoutSubFolder)
        // );

        // Trả về class tồn tại
        if (class_exists($classFullWithSubFolder)) {
            return app($classFullWithSubFolder);
        }

        if (class_exists($classFullWithoutSubFolder)) {
            return app($classFullWithoutSubFolder);
        }

        if (class_exists($classWithSubFolder)) {
            return app($classWithSubFolder);
        }

        if (class_exists($classWithoutSubFolder)) {
            return app($classWithoutSubFolder);
        }

        // Nếu không tìm thấy class
        throw new Exception("Class {$modelName} không tồn tại trong {$type[$classType]}");
    }
}

if (!function_exists('generateSelect')) {
    function generateSelect($root = 'Choose', $options = null, $keyName = 'id', $valueName = 'name')
    {
        $select = [];

        // Xử lý giá trị root
        if (!is_string($root) || empty($root)) {
            $root = 'Choose'; // Giá trị mặc định
        }

        $select[0] = $root;

        // Validate $options: kiểm tra xem $options có phải là iterable không
        if (is_iterable($options)) {
            foreach ($options as $option) {
                // Kiểm tra nếu là mảng và có key tương ứng
                if (is_array($option) && isset($option[$keyName], $option[$valueName])) {
                    $select[$option[$keyName]] = $option[$valueName];
                }
                // Kiểm tra nếu là object và có thuộc tính tương ứng
                elseif (is_object($option) && isset($option->{$keyName}, $option->{$valueName})) {
                    $select[$option->{$keyName}] = $option->{$valueName};
                } else {
                    // Nếu không thỏa mãn điều kiện, bỏ qua phần tử đó
                    continue;
                }
            }
        }
        return $select;
    }
}

if (!function_exists('changeDateFormat')) {
    function changeDateFormat($date, $format = 'Y-m-d')
    {
        return Carbon::parse($date)->format($format);

    }
}

if (!function_exists('cutUrl')) {
    function cutUrl($url, $host = "http://127.0.0.1:8000/")
    {
        // dd(str_replace($host, '', $url));
        return str_replace($host, '', $url);
    }
}

if (!function_exists('getSlug')) {
    function getSlug($string)
    {
        return \Illuminate\Support\Str::slug($string);
    }
}

if (!function_exists('statusOrder')) {
    function statusOrder($status)
    {
        $status = strtolower($status);
        $statusList = __('order.status');
        return $statusList[$status] ?? 'Không xác định';
    }
}

if (!function_exists('paymentStatusOrder')) {
    function paymentStatusOrder($status)
    {
        $status = strtolower($status);
        $statusList = __('order.payment_status');
        return $statusList[$status] ?? 'Không xác định';
    }
}

if (!function_exists('getActionRoute')) {
    function getActionRoute()
    {
        $allRoutes = Route::getRoutes();
        $permissionAll = [];
        $allowedActions = ['index', 'create', 'edit', 'delete'];
        $excludedControllers = ['Dashboard','Permission', 'Staff'];
        foreach ($allRoutes as $route) {
            if (in_array('GET', $route->methods())) {
                if (in_array('authenticated', $route->middleware())) {
                    $actionName = $route->getActionName();
                    if (strpos($actionName, '@') !== false) {
                        list($controller, $action) = explode('@', $actionName);
                        $controller = class_basename($controller);
                        $controller = str_replace('Controller', '', $controller);
                        if (in_array($controller, $excludedControllers)) {
                            continue;
                        }
                        if (in_array($action, $allowedActions)) {
                            $permission = "$controller $action";
                            if (!in_array($permission, $permissionAll)) {
                                $permissionAll[] = $permission;
                            }
                        }
                    }
                }
            }
        }
        return $permissionAll;
    }

}

if (!function_exists('orderCode')) {
    function orderCode()
    {
        return 'TGNT' . uniqid();
    }
}

if (!function_exists('convertNumber')) {
    function convertNumber($number)
    {
        return str_replace('.', '', $number);
    }
}

if (!function_exists('formatMon')) {
    function formatNumber($number)
    {
        if ($number == '') {
            return '';
        }
        return number_format($number, 0, '.', '.');
    }
}


//format money vnd 
if (!function_exists('formatMoney')) {
    function formatMoney($number)
    {
        if ($number == '') {
            return '';
        }
        return number_format(ceil($number), 0, '.', '.');
    }

}

// lấy danh mục phòng
if (!function_exists('getCategory')) {
    function getCategory($type)
    {
        if ($type == 'room') {
            $categoryRepository = loadClass('Category', 'Repository');
            return $categoryRepository->getCategoryRoom();
        }
        $categoryRepository = loadClass('Category', 'Repository');
        return $categoryRepository->getCategory();
    }
}

//tính tăng trưởng
if (!function_exists('growthRate')) {
    function growthRate($current, $previous)
    {
        if ($previous == 0) {
            return 0;
        }
        // làm tròn 2 chữ số thập phân
        return round((($current - $previous) / $previous) * 100, 2);
    }
}

//tính tăng trưởng
if (!function_exists('growthRateHtml')) {
    function growthRateHtml($value)
    {
        if ($value > 0) {
            return '<span class="text-success fw-medium" data-bs-toggle="tooltip" data-bs-title="Tăng trưởng so với tháng trước"><i class="ti ti-arrow-up-right"></i>' . $value . '%</span>';
        } else if ($value < 0) {
            return '<span class="text-danger fw-medium" data-bs-toggle="tooltip" data-bs-title="Giảm so với tháng trước">' . $value . '%</span>';
        } else {
            return '<span class="text-dark fw-medium" data-bs-toggle="tooltip" data-bs-title="Không thay đổi so với tháng trước">' . $value . '%</span>';
        }
    }
}

//check ngày hết hạn
if (!function_exists('checkExpiredDate')) {
    function checkExpiredDate($endDate)
    {
        $now = Carbon::now();
        $end = Carbon::parse($endDate);
        return $now->gt($end);
    }
}

// lấy danh mục thuộc tính
if (!function_exists('getAttributeCategory')) {
    function getAttributeCategory()
    {
        $data = loadClass('AttributeCategory', 'Repository');
        return $data->getAllWith();
    }
}

// lấy setting site
if (!function_exists('getSetting')) {
    function getSetting()
    {
        $data = loadClass('Setting', 'Repository');
        $newData = $data->getAll()->first();
        $newData->site_social = json_decode($newData->site_social);
        return $newData;
    }
}

// lấy số lượng sản phẩm trong giỏ hàng
if (!function_exists('getCartCount')) {
    function getCartCount()
    {
        $data = loadClass('Cart', 'Repository');
        $user_id = Auth::user()->id;
        // dd($data->getCartCount($user));
        return $data->getCartCount($user_id);
    }
}

// lấy số lượng sản phẩm yêu thích
if (!function_exists('getWishlistCount')) {
    function getWishlistCount()
    {
        $data = loadClass('Wishlist', 'Repository');
        $user_id = Auth::user()->id;
        return $data->getWishlistCount($user_id);
    }
}

// lấy sản phẩm vừa xem gần đây
if (!function_exists('getHistoryProduct')) {
    function getHistoryProduct()
    {
        $data = Illuminate\Support\Facades\Session::get('historyProduct');
        $data = collect($data)->take(8);
        return $data;
    }
}
// lấy tên bộ sưu tập theo tên
if (!function_exists('getNamebyIdCollection')) {
    function getNamebyIdCollection($id)
    {
        $collectionRepository = loadClass('Collection', 'Repository');
        return $collectionRepository->findByField('id', $id)->first()->name;
    }
}