<?php
//Việc sử dụng helper này giúp trả về dữ liệu đều có chung một cấu trúc, ko lăng tăng

if (!function_exists('successResponse')) {
    function successResponse($data = null, $message = "Thành công!", $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse($message = "Không thành công!", $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null
        ], $code);
    }
}
