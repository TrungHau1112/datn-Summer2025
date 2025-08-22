$(document).ready(function() {
    // Xử lý nút "Giao hàng thất bại"
    $(document).on('click', '.markDeliveryFailed', function() {
        const orderId = $(this).data('order-id');
        const phone = $(this).data('phone');
        
        // Hiển thị confirm dialog
        Swal.fire({
            title: 'Xác nhận giao hàng thất bại?',
            html: `
                <p>Bạn có chắc muốn đánh dấu đơn hàng này là <strong>giao hàng thất bại</strong>?</p>
                <div class="mt-3">
                    <label for="failReason" class="form-label">Lý do thất bại:</label>
                    <select id="failReason" class="form-select">
                        <option value="Không có người nhận">Không có người nhận</option>
                        <option value="Địa chỉ không chính xác">Địa chỉ không chính xác</option>
                        <option value="Khách hàng từ chối nhận">Khách hàng từ chối nhận</option>
                        <option value="Số điện thoại không liên lạc được">Số điện thoại không liên lạc được</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            preConfirm: () => {
                const reason = document.getElementById('failReason').value;
                if (!reason) {
                    Swal.showValidationMessage('Vui lòng chọn lý do thất bại');
                    return false;
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;
                
                // Gửi AJAX request
                $.ajax({
                    url: `/admin/order/mark-delivery-failed/${orderId}`,
                    method: 'POST',
                    data: {
                        reason: reason,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Đã đánh dấu giao hàng thất bại',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload trang để cập nhật UI
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: response.message || 'Có lỗi xảy ra',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra khi xử lý';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Lỗi!',
                            text: message,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Kiểm tra cảnh báo bom hàng khi nhập số điện thoại trong form tạo đơn
    $(document).on('blur', 'input[name="phone"]', function() {
        const phone = $(this).val();
        const warningDiv = $('#bom-warning');
        
        if (phone.length >= 10) {
            $.ajax({
                url: '/admin/order/check-bom-hang',
                method: 'GET',
                data: { phone: phone },
                success: function(response) {
                    if (response.warning_message) {
                        if (warningDiv.length === 0) {
                            // Tạo div cảnh báo nếu chưa có
                            $('input[name="phone"]').after(`
                                <div id="bom-warning" class="alert alert-warning mt-2" role="alert">
                                    <i class="ti ti-alert-triangle"></i>
                                    <span id="warning-text">${response.warning_message}</span>
                                </div>
                            `);
                        } else {
                            // Cập nhật nội dung cảnh báo
                            $('#warning-text').text(response.warning_message);
                            warningDiv.removeClass('d-none');
                        }
                    } else {
                        // Ẩn cảnh báo nếu không có vấn đề
                        warningDiv.addClass('d-none');
                    }
                },
                error: function() {
                    // Bỏ qua lỗi kiểm tra
                }
            });
        } else {
            // Ẩn cảnh báo nếu số điện thoại chưa đủ dài
            warningDiv.addClass('d-none');
        }
    });

    // ✅ THÊM MỚI: Xử lý nút Force Update Bom Hàng
    $(document).on('click', '#forceUpdateBomHang', function() {
        const button = $(this);
        const originalText = button.html();
        
        // Disable button và hiển thị loading
        button.prop('disabled', true).html('<i class="ti ti-loader ti-spin me-1"></i>Đang cập nhật...');
        
        $.ajax({
            url: '/admin/order/force-update-bom-hang',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: response.message,
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload trang để cập nhật UI
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: response.message || 'Có lỗi xảy ra khi cập nhật bom hàng',
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra khi cập nhật bom hàng';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Lỗi!',
                    text: message,
                    icon: 'error'
                });
            },
            complete: function() {
                // Restore button
                button.prop('disabled', false).html(originalText);
            }
        });
    });

    // ✅ THÊM MỚI: Xử lý nút Sửa lỗi Bom Hàng
    $(document).on('click', '#fixDeliveryFailedCount', function() {
        const button = $(this);
        const originalText = button.html();
        
        // Hiển thị confirm dialog
        Swal.fire({
            title: 'Xác nhận sửa lỗi?',
            text: 'Bạn có chắc muốn sửa lại delivery_failed_count cho các đơn hàng có trạng thái giao hàng thất bại?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable button và hiển thị loading
                button.prop('disabled', true).html('<i class="ti ti-loader ti-spin me-1"></i>Đang sửa...');
                
                $.ajax({
                    url: '/admin/order/fix-delivery-failed-count',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload trang để cập nhật UI
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: response.message || 'Có lỗi xảy ra khi sửa lỗi',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra khi sửa lỗi';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Lỗi!',
                            text: message,
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        // Restore button
                        button.prop('disabled', false).html(originalText);
                    }
                });
            }
        });
    });
});
