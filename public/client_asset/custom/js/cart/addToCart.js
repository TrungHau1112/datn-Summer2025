(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    let skuItems = [];
    TGNT.addItem = (message = null, sku, quantity, price) => {
        if (!sku || !quantity || !price) {
            VDmessage.show("error", "Dữ liệu không hợp lệ!");
            return;
        }
        
        // Validation số lượng
        quantity = parseInt(quantity) || 0;
        if (quantity <= 0) {
            VDmessage.show("error", "Số lượng phải lớn hơn 0!");
            return;
        }
        
        if (quantity > 1000) {
            VDmessage.show("error", "Số lượng tối đa là 1000!");
            return;
        }
        let url = "/gio-hang/store";
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: url,
            data: {
                sku,
                quantity,
                price,
            },
            success: function (data) {
                if (message) {
                    VDmessage.show("success", message);
                }
                TGNT.cartCount();
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    VDmessage.show(
                        "error",
                        "Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!"
                    );
                } else {
                    VDmessage.show(
                        "error",
                        "Lỗi hệ thống, vui lòng thử lại sau!"
                    );
                }
            },
        });
    };

    TGNT.addToCart = () => {
        $(document).on("click", ".btn-buy.addToCart", function (e) {
            e.preventDefault();
            
            // Kiểm tra nếu nút bị disable
            if ($(this).hasClass('disabled')) {
                return;
            }
            
            if (!window.isLoggedIn) {
                VDmessage.show("error", "Chức năng của người đăng nhập!");
            } else {
                let message = "Thêm vào giỏ hàng thành công";
                let sku = $(this).closest('.buy-product').find("input[name='sku']").val();
                let quantity = parseInt($(this).closest('.buy-product').find("input[name='quantity']").val()) || 0;
                let price = $(this).closest('.buy-product').find("input[name='price']").val();
                let inventory = parseInt($(this).closest('.buy-product').find(".inventory").val()) || 0;
                console.log(sku, quantity, price, inventory);
                
                // Validation số lượng
                if (quantity <= 0) {
                    VDmessage.show("error", "Số lượng phải lớn hơn 0!");
                    return;
                }
                
                if (quantity > inventory) {
                    VDmessage.show("error", `Chỉ còn ${inventory} sản phẩm trong kho!`);
                    return;
                }
                
                if (inventory > 0) {
                    TGNT.addItem(message, sku, quantity, price);
                } else {
                    VDmessage.show("error", "Đã hết hàng");
                }
            }
        });
    };
    TGNT.addMultiToCart = () => {
        $(document).on("click", ".addMultiToCart", function () {
            if (!window.isLoggedIn) {
                VDmessage.show("error", "Chức năng của người đăng nhập!");
                return;
            } else {
                let outOfStockItem = skuItems.find((e) => e.inventory <= 0);
                if (outOfStockItem) {
                    VDmessage.show(
                        "error",
                        `Sản phẩm: "${outOfStockItem.name}" đã hết hàng`
                    );
                    return;
                }
                skuItems.forEach((e) => {
                    TGNT.addItem(null, e.sku, e.quantity, e.price);
                });
                let message = "Thêm bộ sưu tập vào giỏ hàng";
                VDmessage.show("success", message);
            }
        });
    };
    TGNT.getProductInCollection = () => {
        $(document).on("click", ".getCollection", function () {
            $(".checkboxsku").each(function () {
                if (!$(this).is(":checked")) {
                    $(this).trigger("click");
                }
            });
        });
    };
    TGNT.checkProduct = () => {
        $(document).on("change", ".check-collection", function () {
            let sku = $(this).data("sku");
            let inventory = $(this).data("inventory");
            let price = $(this).data("price");
            let name = $(this).data("name");
            if ($(`#checkboxsku-${sku}`).is(":checked")) {
                skuItems.push({
                    sku: sku,
                    quantity: 1,
                    inventory: inventory,
                    price: price,
                    name: name,
                });
            } else {
                skuItems = skuItems.filter((item) => item.sku !== sku);
            }
        });
    };

    TGNT.cartCount = () => {
        let url = "/gio-hang/count";
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "GET",
            url: url,
            success: function (data) {
                $(".cart_count").html(data);
            },
            error: function (data) {
                console.log("Lỗi");
            },
        });
    };
    TGNT.removeItem = () => {
        $(document).on("click", ".removeItem", function () {
            let _this = $(this);
            let sku = _this.data("sku");
            $(`#cart-item-${sku}`).remove();
            skuItems = skuItems.filter((item) => item.sku !== sku);
        });
    };
    $(document).ready(function () {

        TGNT.addToCart();
        TGNT.getProductInCollection();
        TGNT.addMultiToCart();
        TGNT.checkProduct();
        TGNT.removeItem();
    });
})(jQuery);
