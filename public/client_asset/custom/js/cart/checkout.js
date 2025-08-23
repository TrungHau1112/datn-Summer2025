(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    TGNT.addDiscount = () => {
        $(document).on("click", ".apply-discount", function () {
            let url = "/thanh-toan/addDiscount";
            let code = $(".code-discount").val();
            let checkCode = $(".list-discount").find(`#discount-${code}`);
            if (checkCode.length == 0) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    type: "POST",
                    url: url,
                    data: {
                        code,
                    },
                    success: function (e) {
                        console.log(e);
                        if (e.data) {
                            $(".list-discount").html(`
                                <div class="discount mb-2 alert alert-success position-relative" id="discount-${e.data.code}" data-code="${e.data.code}"
                                    role="alert">
                                    <div class="discount-inner">
                                        <p class="discount-code mb-1">
                                            <span class="text-uppercase">Mã giảm giá</span>:
                                            <b id="pc-clipboard-${e.data.id}">${e.data.code}</b>
                                        </p>
                                        <p class="discount-desc text-muted mb-0">
                                            ${e.data.title}
                                        </p>
                                    </div>
                                    <div class="remove-discount position-absolute btn btn-outline-tgnt p-2" id="remove-discount-${e.data.code}" data-code="${e.data.code}"
                                    style="top:50%; right:10%; transform: translate(40%, -50%);">x</div>
                                </div>
                                `);
                            TGNT.applyDiscount();
                        } else {
                            VDmessage.show(
                                "error",
                                e.message
                            );
                        }
                    },
                    error: function (data) {
                        // VDmessage.show(
                        //     "error",
                        //     "Mã giảm giá không khả dụng"
                        // );
                        VDmessage.show("error", data.responseJSON.message);
                    },
                });
            } else {
                VDmessage.show("warning", "Mã giảm giá đang sử dụng");
            }
        });
    };
    TGNT.applyDiscount = () => {
        let allDiscount = $(".list-discount").find(".discount");
        let codeExists = [];
        allDiscount.each(function () {
            codeExists.push($(this).data("code"));
        });
        let url = "/thanh-toan/applyDiscount";
        if (allDiscount.length > 0) {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: url,
                data: {
                    code: codeExists,
                },
                success: function (e) {
                    let afterDiscount = 0;
                    let savePrice = 0;
                    let savePriceT = 0;
                    let price = $("#cart-total-input").val();
                    if (e.min_order_amount < $("#cart-total-input").val()) {
                        if (e.discount_type == 1) {
                            savePrice = (price * e.discount_value) / 100;
                            afterDiscount =
                                price - (price * e.discount_value) / 100;
                        } else {
                            savePrice = e.discount_value;
                            afterDiscount = price - e.discount_value;
                        }
                        price = afterDiscount;
                        savePriceT += parseFloat(savePrice);
                        VDmessage.show("success", "Đã dùng mã giảm giá");
                        let currentArray = $(`.discount-code`).val()
                            ? JSON.parse($(`.discount-code`).val())
                            : [];
                        if (!currentArray.includes(e.id)) {
                            currentArray.push(e.id);
                        }
                        $(`.discount-code`).val(JSON.stringify(currentArray));
                    } else {
                        $(`#discount-${e.code}`).remove();
                        TGNT.updateTotalCart();
                        VDmessage.show(
                            "error",
                            `Đơn hàng phải tối thiểu ${TGNT.formatNumber(
                                e.min_order_amount
                            )}đ`
                        );
                    }
                    $("#save-price-checkout").html(TGNT.formatNumber(savePriceT));
                    $("#cart-total-discount").html(
                        TGNT.formatNumber(afterDiscount)
                    );
                    $("#cart-total-discount-input").val(afterDiscount);
                    $("#total-cart-input").val(
                        TGNT.formatNumber(afterDiscount)
                    );
                    $(".total-cart-input").val(
                        TGNT.formatNumber(afterDiscount)
                    );
                },
                error: function (data) {},
            });
        } else {
            $("#save-price").html("");
            $("#cart-total-discount").html($("#cart-total").text());
            $("#total-cart-input").val($("#cart-total").text());
            // $("#total-cart-input").val($("#cart-total-input").val());
        }
    };
    TGNT.removeDiscount = () => {
        $(document).on("click", ".remove-discount", function () {
            let code = $(this).data("code");
            let allDiscount = $(".list-discount").find(".discount");
            let codeExists = [];
            allDiscount.each(function () {
                codeExists.push($(this).data("code"));
            });
            let codeIndex = codeExists.indexOf(code);
            if (codeIndex !== -1) {
                codeExists.splice(codeIndex, 1);
            }
            $(`#discount-${code}`).remove();
            TGNT.applyDiscount();
        });
    };
    TGNT.updateTotalCart = () => {
        let url = "/gio-hang/totalCart";
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: url,
            success: function (data) {
                let price = $("#cart-total-input").val();
                $("#cart-total-discount").html(TGNT.formatNumber(data.afterDiscount));
                $("#total-cart-input").val(TGNT.formatNumber(data.afterDiscount));
                // $("#total-cart-input").val(data);
                let allDiscount = $(".list-discount").find(".discount");
                if (allDiscount.length > 0) {
                    TGNT.applyDiscount();
                }
            },
            error: function () {
                console.log("lỗi - checkout.js:171");
            },
        });
    };
    TGNT.formatNumber = (number) => {
        number = Math.floor(number);
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    TGNT.form_payment = () => {
        $(".radio_input_tgnt").on("change", function () {
            $(".form_payment").attr("action", $(this).data("url"));
        });
    };
    
    // Validation cho trường phone
    TGNT.validatePhone = () => {
        const phoneInput = $('#customer_phone');
        
        // Chỉ cho phép nhập số
        phoneInput.on('input', function() {
            let value = $(this).val();
            // Loại bỏ tất cả ký tự không phải số
            value = value.replace(/[^0-9]/g, '');
            $(this).val(value);
        });
        
        // Kiểm tra khi blur
        phoneInput.on('blur', function() {
            let value = $(this).val();
            if (value && (value.length < 10 || value.length > 11)) {
                VDmessage.show('error', 'Số điện thoại phải có 10-11 chữ số');
                $(this).focus();
                return false;
            }
            
            // Kiểm tra định dạng số điện thoại Việt Nam
            if (value && !/^(0[3|5|7|8|9][0-9]{8}|01[2689][0-9]{7})$/.test(value)) {
                VDmessage.show('error', 'Số điện thoại không đúng định dạng');
                $(this).focus();
                return false;
            }
        });
        
        // Validation khi submit form
        $('.form_payment').on('submit', function(e) {
            const phoneValue = phoneInput.val();
            
            if (!phoneValue) {
                VDmessage.show('error', 'Vui lòng nhập số điện thoại');
                phoneInput.focus();
                e.preventDefault();
                return false;
            }
            
            if (phoneValue.length < 10 || phoneValue.length > 11) {
                VDmessage.show('error', 'Số điện thoại phải có 10-11 chữ số');
                phoneInput.focus();
                e.preventDefault();
                return false;
            }
            
            if (!/^(0[3|5|7|8|9][0-9]{8}|01[2689][0-9]{7})$/.test(phoneValue)) {
                VDmessage.show('error', 'Số điện thoại không đúng định dạng');
                phoneInput.focus();
                e.preventDefault();
                return false;
            }
        });
    };
    $(document).ready(function () {
        TGNT.updateTotalCart();
        TGNT.addDiscount();
        TGNT.removeDiscount();
        TGNT.form_payment();
        TGNT.validatePhone();
    });
})(jQuery);

// ✅ THÊM MỚI: Tính phí ship tự động từ GHTK API
$(document).ready(function() {
    let currentShippingFee = 0;
    let currentInsuranceFee = 0;
    let autoCalculateTimeout;
    
    // Tự động tính phí ship khi thay đổi địa chỉ
    function autoCalculateShipping() {
        const province = $('select[name="province_id"]').val();
        const district = $('select[name="district_id"]').val();
        const ward = $('select[name="ward_id"]').val();
        
        // Chỉ tính khi có đủ 3 cấp địa chỉ: tỉnh, quận/huyện, phường/xã
        if (!province || !district || !ward) {
            resetShippingFee();
            return;
        }
        
        // Clear timeout cũ để tránh gọi API quá nhiều
        if (autoCalculateTimeout) {
            clearTimeout(autoCalculateTimeout);
        }
        
        // Delay 500ms để user chọn xong
        autoCalculateTimeout = setTimeout(() => {
            calculateShippingFee(province, district, ward);
        }, 500);
    }
    
    // Hàm tính phí ship
    function calculateShippingFee(province, district, ward) {
        // Hiển thị loading
        showShippingLoading();
        
        // Tính trọng lượng đơn hàng (kg)
        const totalWeight = calculateOrderWeight();
        
        // Tính giá trị đơn hàng (VND)
        const orderValue = calculateOrderValue();
        
        // Lấy địa chỉ kho thật từ trang
        const warehouseAddress = getWarehouseAddress();
        
        // Gọi API tính phí ship
        $.ajax({
            url: '/thanh-toan/calculate-shipping-fee',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                pickup_province: warehouseAddress.province || 'Hà Nội',
                pickup_district: warehouseAddress.district || 'Cầu Giấy',
                delivery_province: getProvinceName(province),
                delivery_district: getDistrictName(district),
                weight: totalWeight,
                value: orderValue
            },
            success: function(response) {
                if (response.success) {
                    // Cập nhật phí ship
                    currentShippingFee = response.shipping_fee;
                    currentInsuranceFee = response.insurance_fee;
                    
                    // Cập nhật UI
                    updateShippingDisplay(response);
                    updateTotalWithShipping(response.shipping_fee);
                    
                    // Hiển thị kết quả
                    $('#shippingResult').removeClass('d-none');
                    $('#shippingError').addClass('d-none');
                    $('#shippingLoading').addClass('d-none');
                    
                } else {
                    showShippingError(response.message || 'Không thể tính phí ship');
                }
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra khi tính phí ship';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showShippingError(message);
            }
        });
    }
    
    // Hiển thị loading
    function showShippingLoading() {
        $('#shippingLoading').removeClass('d-none');
        $('#shippingResult').addClass('d-none');
        $('#shippingError').addClass('d-none');
    }
    
    // Cập nhật hiển thị phí ship
    function updateShippingDisplay(response) {
        $('#shippingFee').text(formatCurrency(response.shipping_fee));
        $('#insuranceFee').text(formatCurrency(response.insurance_fee));
        $('#deliveryTime').text(response.estimated_deliver_time || '2-3 ngày');
        $('#shippingFeeDisplay').text(formatCurrency(response.shipping_fee));
    }
    
    // Reset phí ship
    function resetShippingFee() {
        currentShippingFee = 0;
        currentInsuranceFee = 0;
        $('#shippingFeeDisplay').text('0 ₫');
        updateTotalWithShipping(0);
        $('#shippingResult').addClass('d-none');
        $('#shippingError').addClass('d-none');
        $('#shippingLoading').addClass('d-none');
    }
    
    // Lắng nghe thay đổi địa chỉ
    $('select[name="province_id"], select[name="district_id"], select[name="ward_id"]').on('change', function() {
        autoCalculateShipping();
    });
    
    // Thay đổi địa chỉ lấy hàng
    $('#changePickupAddress').on('click', function() {
        Swal.fire({
            title: 'Thay đổi địa chỉ lấy hàng',
            html: `
                <div class="mb-3">
                    <label class="form-label">Tỉnh/Thành:</label>
                    <select id="pickupProvince" class="form-select">
                        <option value="Hà Nội">Hà Nội</option>
                        <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                        <option value="Đà Nẵng">Đà Nẵng</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quận/Huyện:</label>
                    <select id="pickupDistrict" class="form-select">
                        <option value="Cầu Giấy">Cầu Giấy</option>
                        <option value="Đống Đa">Đống Đa</option>
                        <option value="Ba Đình">Ba Đình</option>
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Cập nhật',
            cancelButtonText: 'Hủy',
            preConfirm: () => {
                const province = $('#pickupProvince').val();
                const district = $('#pickupDistrict').val();
                return { province, district };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Cập nhật hiển thị địa chỉ lấy hàng
                $('.shipping-calculation .badge').text(`${result.value.district}, ${result.value.province}`);
                
                // Tính lại phí ship nếu đã có địa chỉ giao hàng
                const deliveryProvince = $('select[name="province_id"]').val();
                const deliveryDistrict = $('select[name="district_id"]').val();
                const deliveryWard = $('select[name="ward_id"]').val();
                if (deliveryProvince && deliveryDistrict && deliveryWard) {
                    autoCalculateShipping();
                }
            }
        });
    });
    
    // Hàm tính trọng lượng đơn hàng
    function calculateOrderWeight() {
        let totalWeight = 0;
        $('.cart-item').each(function() {
            const quantity = parseInt($(this).find('.quantity').text()) || 1;
            const weight = parseFloat($(this).data('weight')) || 0.5; // Mặc định 0.5kg
            totalWeight += quantity * weight;
        });
        return Math.max(totalWeight, 0.5); // Tối thiểu 0.5kg
    }
    
    // Hàm tính giá trị đơn hàng
    function calculateOrderValue() {
        const totalElement = $('#cart-total-discount');
        const totalText = totalElement.text().replace(/[^\d]/g, '');
        return parseInt(totalText) || 0;
    }
    
    // Hàm lấy địa chỉ kho từ trang
    function getWarehouseAddress() {
        const warehouseText = $('.warehouse-info strong').text();
        
        // Parse địa chỉ kho (ví dụ: "Cầu Giấy, Hà Nội" -> {district: "Cầu Giấy", province: "Hà Nội"})
        if (warehouseText.includes(',')) {
            const parts = warehouseText.split(',').map(part => part.trim());
            return {
                district: parts[0],
                province: parts[1]
            };
        }
        
        // Fallback nếu không parse được
        return {
            district: 'Cầu Giấy',
            province: 'Hà Nội'
        };
    }
    
    // Hàm lấy tên tỉnh từ ID
    function getProvinceName(provinceId) {
        const provinceSelect = $('select[name="province_id"]');
        return provinceSelect.find('option:selected').text() || 'Hà Nội';
    }
    
    // Hàm lấy tên quận/huyện từ ID
    function getDistrictName(districtId) {
        const districtSelect = $('select[name="district_id"]');
        return districtSelect.find('option:selected').text() || 'Cầu Giấy';
    }
    
    // Hàm hiển thị lỗi tính phí ship
    function showShippingError(message) {
        $('#shippingErrorMessage').text(message);
        $('#shippingError').removeClass('d-none');
        $('#shippingResult').addClass('d-none');
        $('#shippingLoading').addClass('d-none');
        
        // Reset phí ship
        resetShippingFee();
    }
    
    // Hàm cập nhật tổng tiền với phí ship
    function updateTotalWithShipping(shippingFee) {
        const baseTotal = calculateOrderValue();
        const newTotal = baseTotal + shippingFee;
        
        $('#cart-total-discount').text(formatCurrency(newTotal));
        
        // Cập nhật hidden field
        $('input[name="total"]').val(newTotal);
        $('input[name="total_amount"]').val(newTotal);
    }
    
    // Hàm format tiền tệ
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
    }
    
    // Thêm phí ship vào form submit
    $('.form_payment').on('submit', function() {
        // Thêm hidden field cho phí ship
        if (!$(this).find('input[name="shipping_fee"]').length) {
            $(this).append(`<input type="hidden" name="shipping_fee" value="${currentShippingFee}">`);
        }
        if (!$(this).find('input[name="insurance_fee"]').length) {
            $(this).append(`<input type="hidden" name="insurance_fee" value="${currentInsuranceFee}">`);
        }
    });
    
    // Tính phí ship ban đầu nếu đã có địa chỉ
    if ($('select[name="province_id"]').val() && $('select[name="district_id"]').val() && $('select[name="ward_id"]').val()) {
        autoCalculateShipping();
    }
});
