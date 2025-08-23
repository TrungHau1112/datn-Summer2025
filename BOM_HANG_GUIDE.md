# Hướng dẫn sử dụng Logic Bom Hàng

## Tổng quan
Logic bom hàng được thiết kế để phát hiện và đánh dấu khách hàng có nhiều lần giao hàng thất bại (từ 2 lần trở lên).

## Cách hoạt động

### 1. Logic chính
- **Điều kiện**: Khi một số điện thoại có tổng số lần giao hàng thất bại >= 2
- **Hành động**: Tất cả đơn hàng của số điện thoại đó sẽ được đánh dấu `is_bom = 1`
- **Trường dữ liệu**: 
  - `delivery_failed_count`: Số lần giao hàng thất bại của đơn hàng
  - `is_bom`: Trạng thái bom hàng (0/1)
  - `last_delivery_failed_at`: Thời gian giao hàng thất bại cuối cùng

### 2. Các cách cập nhật bom hàng

#### A. Tự động khi cập nhật trạng thái đơn hàng
- Khi admin cập nhật trạng thái đơn hàng thành `delivery_failed`
- Middleware `CheckBomHang` sẽ tự động kiểm tra và cập nhật

#### B. Sử dụng nút "Giao hàng thất bại"
- Trong admin panel, click nút ⚠️ trên đơn hàng
- Chọn lý do thất bại và xác nhận
- Hệ thống sẽ tăng `delivery_failed_count` và kiểm tra bom hàng

#### C. Force update từ admin panel
- Click nút "Cập nhật Bom Hàng" trên trang danh sách đơn hàng
- Hệ thống sẽ cập nhật bom hàng cho tất cả số điện thoại

#### D. Command line
```bash
# Cập nhật cho tất cả đơn hàng
php artisan order:force-update-bom-hang

# Cập nhật cho số điện thoại cụ thể
php artisan order:force-update-bom-hang --phone=0123456789
```

### 3. API Endpoints

#### Kiểm tra bom hàng
```
GET /admin/order/check-bom-hang?phone=0123456789
```

#### Đánh dấu giao hàng thất bại
```
POST /admin/order/mark-delivery-failed/{id}
Body: { "reason": "Lý do thất bại" }
```

#### Force update bom hàng
```
POST /admin/order/force-update-bom-hang
```

## Cách sử dụng

### 1. Trong Admin Panel

#### Đánh dấu giao hàng thất bại:
1. Vào trang danh sách đơn hàng
2. Tìm đơn hàng có trạng thái "Đang giao" hoặc "Đã giao"
3. Click nút ⚠️ (Giao hàng thất bại)
4. Chọn lý do thất bại và xác nhận

#### Force update bom hàng:
1. Vào trang danh sách đơn hàng
2. Click nút "Cập nhật Bom Hàng" ở góc phải
3. Chờ hệ thống xử lý và reload trang

### 2. Kiểm tra trạng thái bom hàng

#### Trong bảng đơn hàng:
- Cột "Bom Hàng" hiển thị:
  - `⚠️ BOM HÀNG` (màu đỏ) nếu `is_bom = 1`
  - `✅ BÌNH THƯỜNG` (màu xanh) nếu `is_bom = 0`
- Số lần giao hàng thất bại được hiển thị bên dưới

#### Cảnh báo khi tạo đơn hàng:
- Khi nhập số điện thoại, hệ thống sẽ kiểm tra và hiển thị cảnh báo nếu có vấn đề

### 3. Debug và Troubleshooting

#### Kiểm tra log:
```bash
tail -f storage/logs/laravel.log | grep "bom"
```

#### Debug thông tin bom hàng:
```php
// Trong code
$debugInfo = $orderService->checkBomHangStatus('0123456789');
dd($debugInfo);
```

#### Kiểm tra database:
```sql
-- Xem tất cả đơn hàng bom hàng
SELECT * FROM orders WHERE is_bom = 1;

-- Xem số lần giao hàng thất bại theo số điện thoại
SELECT phone, SUM(delivery_failed_count) as total_failed 
FROM orders 
WHERE delivery_failed_count > 0 
GROUP BY phone;
```

## Lưu ý quan trọng

1. **Logic nhất quán**: Tất cả các nơi đều sử dụng logic dựa trên `delivery_failed_count`
2. **Transaction**: Các thao tác cập nhật đều sử dụng database transaction
3. **Logging**: Tất cả hoạt động đều được ghi log để debug
4. **Performance**: Force update có thể mất thời gian với dữ liệu lớn
5. **Cache**: Thống kê được cache để tối ưu hiệu suất

## Troubleshooting

### Vấn đề thường gặp:

1. **Bom hàng không hiển thị sau khi cập nhật**
   - Chạy force update: `php artisan order:force-update-bom-hang`
   - Kiểm tra log để debug

2. **Logic không hoạt động**
   - Kiểm tra migration đã chạy chưa
   - Kiểm tra middleware đã đăng ký chưa
   - Kiểm tra JavaScript đã load chưa

3. **Performance chậm**
   - Sử dụng force update cho từng số điện thoại cụ thể
   - Kiểm tra index trên database

## Cập nhật gần đây

- ✅ Sửa logic bom hàng dựa trên `delivery_failed_count` thay vì `cancelled`
- ✅ Thêm nút "Giao hàng thất bại" trong admin panel
- ✅ Thêm nút "Force Update Bom Hàng"
- ✅ Thêm command line tool
- ✅ Cải thiện logging và debugging
- ✅ Thêm middleware tự động cập nhật
### 2. Các cách cập nhật bom hàng
