<?php

require_once 'vendor/autoload.php';

// Khởi tạo Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking address data...\n - check_address.php:9";

try {
    // Kiểm tra bảng provinces
    $provinces = DB::table('provinces')->take(5)->get();
    echo "Provinces table:\n - check_address.php:14";
    foreach ($provinces as $province) {
        echo "Province data: - check_address.php:16" . json_encode($province) . "\n";
    }
    
    echo "\n - check_address.php:19";
    
    // Kiểm tra bảng districts
    $districts = DB::table('districts')->where('province_code', '01')->take(5)->get();
    echo "Districts for province_code=01 (Hà Nội):\n - check_address.php:23";
    foreach ($districts as $district) {
        echo "District data: - check_address.php:25" . json_encode($district) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: - check_address.php:29" . $e->getMessage() . "\n";
}
