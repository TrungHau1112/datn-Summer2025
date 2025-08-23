(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();

    TGNT.select_status = () => {
        $(document).on('focus', 'select[name="status"]', function () {
            $(this).attr('data-original-value', $(this).val());
        });
        $(document).on("change", ".select_status", function () {
            const statusId = $(this).val();
            const orderId = $(this).data("id");
            const name = $(this).attr("name");
            const old_value = $(this).attr('data-original-value');

            if (name === 'status') {
                const paymentStatus = $(this).parents('tr').find('select[name="payment_status"]').val();
                if (paymentStatus === "completed") {
                    if (statusId === "delivered") {
                        $(this).parents('tr').addClass('bg-blue-100')
                            .find('input, select, button, a, ul').attr('disabled', true);
                        $(this).parents('tr').find('.btn_delete').addClass('disabled_row');
                        $(this).parents('tr').find('.btn_edit').addClass('disabled_row');
                        $(this).parents('tr').find('.btn_link').addClass('disabled_row');
                        $(this).parents('tr').find('input[type="checkbox"]').remove();
                    }
                } else {
                    if (statusId === "delivered") {
                        VDmessage.show('warning', 'Trạng thái thanh toán chưa hoàn thành');
                        $(this).val(old_value);
                        return false;
                    }
                }
            }

            $.ajax({
                url: `/admin/order/payment-status/${orderId}`,
                type: "PUT",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    [name]: statusId,
                },
                dataType: "JSON",
                success: () => {
                    VDmessage.show("success", "Trạng thái đã được cập nhật!");
                },
                error: (xhr) => {
                    VDmessage.show("error", xhr.responseJSON.message);
                },
            });
        });
    };

    TGNT.select2 = () => {
        if ($(".select2_order").length) {
            $(".select2_order").select2({
                theme: "bootstrap4",
            });
        }
    };


    // Tìm kiếm khách hàng theo số đt
    TGNT.searchCustomer = () => {
        let debounceTimer;
        $('.search-customer').on('input', function () {
            const phoneNumber = $(this).val().trim();            
            clearTimeout(debounceTimer); // Xóa bỏ debounce trước đó nếu có
            debounceTimer = setTimeout(() => {
                if (phoneNumber) {
                    $.ajax({
                        url: `/admin/order/search_customer`,
                        method: 'GET',
                        data: { phone: phoneNumber },
                        dataType: 'json',
                        success: function (response) {
                            
                            if (response.data?.success && response.data.customer) {
                                VDmessage.show('success', 'Đã tìm thấy khách hàng');
                                const customer = response.data.customer;
                                $('input[name="name"]').val(customer.name || '');
                                $('input[name="email"]').val(customer.email || '');
                                $('input[name="phone"]').val(customer.phone || '');
                                $('input[name="address"]').val(customer.address || '');
                                $('#customer_note').val(customer.note || '');
    
                                if (customer.province_id) {
                                    $('select[name="province_id"] option[value="' + customer.province_id + '"]').prop('selected', true);
                                }
    
                                if (customer.district_id) {
                                    $('select[name="district_id"] option[value="' + customer.district_id + '"]').prop('selected', true);
                                }
    
                                if (customer.ward_id) {
                                    $('select[name="ward_id"] option[value="' + customer.ward_id + '"]').prop('selected', true);
                                }
                                TGNT.select2();
                            } else {
                                VDmessage.show('error', 'Không tồn tại khách hàng');
                            }
                        },
                        error: function () {
                            VDmessage.show('error', 'Không tồn tại khách hàng');
                        }
                    });
                } else {
                    VDmessage.show('warning', 'Vui lòng nhập số điện thoại trước khi tìm kiếm.');
                }
            }, 500); // Debounce 500ms
        });
    };
    

    $(document).ready(function () {
        TGNT.select_status();
        TGNT.select2();
        TGNT.searchCustomer();
    });
})(jQuery);
