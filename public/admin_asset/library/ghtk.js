$(document).ready(function() {
    // Test kết nối API
    $(document).on('click', '#testConnection', function() {
        const button = $(this);
        const originalText = button.html();
        
        // Disable button và hiển thị loading
        button.prop('disabled', true).html('<i class="ti ti-loader ti-spin me-1"></i>Đang test...');
        
        $.ajax({
            url: '/admin/ghtk/test-connection',
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
                    });
                } else {
                    // Nếu không có token, hiển thị hướng dẫn
                    if (response.message.includes('Chưa có API token')) {
                        Swal.fire({
                            title: 'Chưa có API Token',
                            text: response.message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Tạo Token Mới',
                            cancelButtonText: 'Đóng'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin/ghtk/create';
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: response.message,
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonText: 'Refresh Token',
                            cancelButtonText: 'Đóng'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                refreshGhtkToken();
                            }
                        });
                    }
                }
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra khi test kết nối';
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

    // Refresh GHTK token
function refreshGhtkToken() {
        $.ajax({
            url: '/admin/ghtk/refresh-token',
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
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Test kết nối lại sau khi refresh
                        $('#testConnection').click();
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Không thể refresh token',
                    icon: 'error'
                });
            }
        });
    }

    // Xem thông tin token
    $(document).on('click', '.view-token', function() {
        const tokenId = $(this).data('token-id');
        
        $.ajax({
            url: `/admin/ghtk/show/${tokenId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#tokenValue').val(response.token);
                    $('#tokenExpiry').text(response.expires_at);
                    
                    // Hiển thị quyền truy cập
                    let rightsHtml = '';
                    if (response.access_rights && response.access_rights.length > 0) {
                        response.access_rights.forEach(function(right) {
                            rightsHtml += `<span class="badge bg-primary me-1">${right}</span>`;
                        });
                    } else {
                        rightsHtml = '<span class="text-muted">Không có</span>';
                    }
                    $('#tokenRights').html(rightsHtml);
                    
                    // Hiển thị modal
                    $('#tokenModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Không thể lấy thông tin token',
                    icon: 'error'
                });
            }
        });
    });

    // Copy token
    $(document).on('click', '#copyToken', function() {
        const tokenInput = $('#tokenValue');
        tokenInput.select();
        document.execCommand('copy');
        
        // Hiển thị thông báo
        const button = $(this);
        const originalText = button.html();
button.html('<i class="ti ti-check me-1"></i>Đã copy');
        button.removeClass('btn-outline-secondary').addClass('btn-success');
        
        setTimeout(function() {
            button.html(originalText);
            button.removeClass('btn-success').addClass('btn-outline-secondary');
        }, 2000);
    });

    // Toggle trạng thái token
    $(document).on('change', '.token-status-toggle', function() {
        const tokenId = $(this).data('token-id');
        const isActive = $(this).is(':checked');
        
        $.ajax({
            url: `/admin/ghtk/update/${tokenId}`,
            method: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                is_active: isActive
            },
            success: function(response) {
                if (response.success) {
                    // Reload trang để cập nhật UI
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: response.message || 'Có lỗi xảy ra',
                        icon: 'error'
                    });
                    // Revert checkbox
                    location.reload();
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi cập nhật trạng thái',
                    icon: 'error'
                });
                // Revert checkbox
                location.reload();
            }
        });
    });

    // Xóa token
    $(document).on('click', '.delete-token', function() {
        const tokenId = $(this).data('token-id');
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: 'Bạn có chắc muốn xóa token này? Hành động này không thể hoàn tác.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/ghtk/delete/${tokenId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
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
                    error: function() {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi xóa token',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Paste token từ clipboard
    $(document).on('click', '#pasteToken', function() {
        if (navigator.clipboard) {
            navigator.clipboard.readText().then(function(text) {
                $('#api_token').val(text);
            }).catch(function(err) {
                console.error('Không thể đọc clipboard: - ghtk.js:264', err);
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Không thể đọc clipboard. Vui lòng copy và paste thủ công.',
                    icon: 'warning'
                });
            });
        } else {
            Swal.fire({
                title: 'Thông báo',
                text: 'Trình duyệt không hỗ trợ clipboard API. Vui lòng copy và paste thủ công.',
                icon: 'info'
            });
        }
    });

    // Auto-select quyền truy cập khi tạo token
    $('#right_create_order, #right_products, #right_delivery_shifts, #right_shipping_fee').on('change', function() {
        // Nếu chọn "Tính phí ship", tự động chọn các quyền cần thiết
        if ($('#right_shipping_fee').is(':checked')) {
            $('#right_create_order').prop('checked', true);
            $('#right_products').prop('checked', true);
        }
    });
});