(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    
    TGNT.changeQuantity = function (e) {
        e.preventDefault();
        var t = e.target,
            n = t.getAttribute("data-field"),
            o = $(t).closest('.input-quantity, .input-group, .input-spinner').find('input[name="' + n + '"]')[0],
            a = parseInt(o.value, 10) || 0;
        var inventory = parseInt($('.inventory').val()) || 0;
        
        // Đảm bảo số lượng không bao giờ âm
        if (a < 0) {
            a = 0;
            o.value = 0;
        }
        
        if (t.classList.contains("btn-plus")) {
            if (a < inventory) {
                o.value = a + 1;
                TGNT.updateAddToCartButton();
                return true;
            } else {
                VDmessage.show("warning", `Chỉ còn ${inventory} sản phẩm trong kho!`);
                return false;
            }
        } else if (t.classList.contains("btn-minus")) {
            if (a > 1) {
                o.value = a - 1;
                TGNT.updateAddToCartButton();
                return true;
            } else {
                o.value = 1; // Đảm bảo không bao giờ dưới 1
                return false;
            }
        }
    };

    TGNT.updateQuantity = () => {
        let timeUpdate = {};
        $(document).on("click", ".btn-plus, .btn-minus", function (e) {
            let checkQuantity = TGNT.changeQuantity(e);
            const _this = $(this)
                .closest(".input-quantity, .input-group, .input-spinner")
                .find(".quantity-field");
            let sku = _this.data("sku");
            const quantity = _this.val();
            let url = "/san-pham/ajax/change-quantity";
            if (checkQuantity) {
                clearTimeout(timeUpdate);
                timeUpdate = setTimeout(function () {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        type: "POST",
                        url: url,
                        data: {
                            sku,
                            quantity,
                        },
                        success: function (data) {
                            if (data.status) {
                                // VDmessage.show("success", "Cập nhật số lượng");
                            } else {
                                const inventory = data;
                                $(".quantity-field").val(inventory);
                                VDmessage.show(
                                    "warning",
                                    `Chúng tôi chỉ còn ${inventory} sản phẩm`
                                );
                            }
                        },
                        error: function (data) {
                            console.log("lỗi");
                        },
                    });
                }, 500);
            }
        });
    };

    TGNT.selectVariantProduct = () => {
        $(document).on("click", ".choose-attribute", function (e) {
            e.preventDefault();
            console.log("Attribute clicked:", $(this).text());
            
            const _this = $(this);
            const attribute_id = _this.attr("data-attributeId");
            const attribute_name = _this.text();
            
            console.log("Attribute ID:", attribute_id, "Name:", attribute_name);
            
            // Xóa active từ tất cả siblings trong cùng nhóm attribute
            _this.closest('.attribute-value').find('.choose-attribute').removeClass("active");
            
            // Thêm active cho button được click
            _this.addClass("active");
            
            console.log("Updated button states");
            TGNT.handleAttribute();
        });
    };

    TGNT.handleAttribute = () => {
        console.log("Handling attribute selection...");
        
        const attribute_ids = $(".attribute-value .choose-attribute.active")
            .map(function () {
                return $(this).attr("data-attributeId");
            })
            .get();
            
        console.log("Selected attribute IDs:", attribute_ids);
        
        const url = new URL(window.location.href);
        const attrParams = attribute_ids;
        attrParams.sort((a, b) => a - b);
        
        if (attrParams.length > 0) {
            url.searchParams.set("attr", attrParams.join(","));
        } else {
            url.searchParams.delete("attr");
        }
        
        const newUrl = url.toString().replace(/%2C/g, ",");
        console.log("New URL:", newUrl);
        window.history.pushState({}, "", newUrl);

        // Kiểm tra xem có bao nhiêu nhóm attribute
        const totalAttributeGroups = $(".attribute").length;
        const selectedAttributeGroups = $(".attribute")
            .toArray()
            .filter((item) => {
                return $(item).find(".choose-attribute.active").length > 0;
            }).length;
            
        console.log("Total attribute groups:", totalAttributeGroups);
        console.log("Selected attribute groups:", selectedAttributeGroups);
        
        // Chỉ gọi AJAX khi tất cả nhóm attribute đã được chọn
        if (totalAttributeGroups > 0 && selectedAttributeGroups === totalAttributeGroups) {
            console.log("Making AJAX request to get variant...");
            $.ajax({
                url: "/san-pham/ajax/get-variant",
                type: "GET",
                data: {
                    attribute_id: attribute_ids,
                    product_id: $("input[name=product_id]").val(),
                },
                dataType: "json",
                success: function (res) {
                    console.log("Variant response:", res);
                    if (res && res.data) {
                        // Cập nhật thông tin sản phẩm mà không reload trang
                        TGNT.updateProductInfo(res.data);
                    } else {
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error getting variant:", error);
                    console.log("Response:", xhr.responseText);
                    // Không reload nếu có lỗi
                }
            });
        }
    };


    TGNT.formatMoney = () => {
        $(".price").each(function () {
            var value = $(this).text();
            $(this).text(value.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
        });
    };



    // Validation input số lượng với kiểm tra tồn kho
    TGNT.validateQuantityInput = () => {
        $(document).on("input", "input[name='quantity']", function() {
            let value = parseInt($(this).val()) || 0;
            let inventory = parseInt($('.inventory').val()) || 0;
            
            // Không cho phép số âm
            if (value < 0) {
                $(this).val(0);
                VDmessage.show("warning", "Số lượng không thể âm!");
            }
            
            // Kiểm tra tồn kho
            if (value > inventory) {
                $(this).val(inventory);
                VDmessage.show("warning", `Chỉ còn ${inventory} sản phẩm trong kho!`);
            }
            
            // Đảm bảo tối thiểu là 1
            if (value === 0) {
                $(this).val(1);
            }
            
            // Cập nhật trạng thái nút thêm vào giỏ
            TGNT.updateAddToCartButton();
        });
        
        // Ngăn chặn nhập ký tự không phải số
        $(document).on("keypress", "input[name='quantity']", function(e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });
    };
    
    // Cập nhật trạng thái nút thêm vào giỏ hàng
    TGNT.updateAddToCartButton = () => {
        let quantity = parseInt($("input[name='quantity']").val()) || 0;
        let inventory = parseInt($('.inventory').val()) || 0;
        let addToCartBtn = $('.btn-buy.addToCart');
        
        if (inventory <= 0) {
            addToCartBtn.addClass('disabled').text('Hết hàng');
            $('#stock-display').addClass('out-of-stock').text('0');
        } else if (quantity > inventory) {
            addToCartBtn.addClass('disabled').text('Vượt quá tồn kho');
        } else {
            addToCartBtn.removeClass('disabled').text('Thêm vào giỏ hàng');
        }
    };
    
    // Cập nhật thông tin sản phẩm khi chọn variant
    TGNT.updateProductInfo = (variantData) => {
        console.log("Updating product info with:", variantData);
        
        // Cập nhật giá
        if (variantData.price) {
            $('.price-main').text(variantData.price);
        }
        
        // Cập nhật SKU
        if (variantData.sku) {
            $('input[name="sku"]').val(variantData.sku);
            $('.rating span:last').text(variantData.sku);
        }
        
        // Cập nhật tồn kho
        if (variantData.quantity !== undefined) {
            $('.inventory').val(variantData.quantity);
            $('#stock-display').text(variantData.quantity);
            
            // Cập nhật trạng thái
            if (variantData.quantity > 0) {
                $('.status-text').removeClass('out-of-stock').addClass('in-stock').text('Còn hàng');
            } else {
                $('.status-text').removeClass('in-stock').addClass('out-of-stock').text('Hết hàng');
            }
            
            // Cập nhật max của input quantity
            $('input[name="quantity"]').attr('max', variantData.quantity);
            
            // Cập nhật trạng thái nút thêm vào giỏ
            TGNT.updateAddToCartButton();
        }
        
        // Cập nhật hình ảnh nếu có
        if (variantData.albums) {
            $('.gallery-container').html(variantData.albums);
        }
        
        // Hiển thị thông báo thành công
        VDmessage.show("success", "Đã chọn biến thể sản phẩm!");
    };

    $(document).ready(function () {
        console.log('Attribute.js loaded');
        
        TGNT.updateQuantity();
        TGNT.selectVariantProduct();
        TGNT.formatMoney();
        TGNT.validateQuantityInput();
        
        // Khởi tạo trạng thái nút thêm vào giỏ hàng
        TGNT.updateAddToCartButton();
        
        // Debug: Kiểm tra các element
        console.log('Choose attribute buttons:', $('.choose-attribute').length);
        console.log('Product ID:', $('input[name=product_id]').val());
        
        // Test click event trực tiếp
        $(document).on('click', '.choose-attribute', function() {
            console.log('Direct click on attribute button:', $(this).text());
        });
        
        // Kiểm tra xem có attribute nào không
        if ($('.choose-attribute').length === 0) {
            console.log('No attribute buttons found - product may not have variants');
        } else {
            console.log('Found', $('.choose-attribute').length, 'attribute buttons');
        }
    });
})(jQuery);
