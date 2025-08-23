(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    TGNT.loadCart = () => {
        $(".list-cart").html(
            `
                <div class="text-center py-5">
                    <div class="spinner-border" style="color:#a84448" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `
        );
        $.ajax({
            type: "GET",
            url: "/gio-hang/listCart",
            success: function (data) {
                let promises = data.map((e) => TGNT.getProduct(e.id));
                Promise.all(promises).then((results) => {
                    $(".list-cart").html("");
                    results.forEach((product) => {
                        $(`.list-cart`).append(product);
                    });
                });
            },
            error: function (data) {
                console.error("Error loading cart:", data);
            },
        });
    };
    TGNT.getProduct = (id) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: "GET",
                url: "/gio-hang/getProduct",
                data: { id },
                success: function (data) {
                    resolve(data);
                },
                error: function (data) {
                    reject(data);
                },
            });
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
                if (data == 0) {
                    $(".cart-client").html(`
                        <div class="container cart-no-item text-center pt-15">
                                <img src="/uploads/image/system/no_product.webp"
                                    alt="" width="100">
                                <p class="text-muted fw-bold mb-3">Giỏ hàng của bạn còn trống</p>
                                <a class="btn btn-tgnt w-25" href="${homeUrl}">Mua ngay</a>
                            </div>`);
                    $(".checkout-cart").addClass("disabled-link");
                } else {
                    $(".checkout-cart").removeClass("disabled-link");
                }
            },
            error: function (data) {
                console.log("Lỗi");
            },
        });
    };
    TGNT.showVariant = () => {
        $(document).on("click", ".open-box-variant", function (e) {
            e.stopPropagation();
            let idCart = $(this).attr("data-idCart");
            $(".box-variant").addClass("hidden").css("opacity", "0");
            const target = $(`#box-variant-${idCart}`);
            target
                .toggleClass("hidden")
                .css("opacity", target.hasClass("hidden") ? "0" : "1");
        });

        $(document).on("click", function () {
            $(".box-variant").addClass("hidden").css("opacity", "0");
        });

        $(document).on("click", ".box-variant", function (e) {
            e.stopPropagation();
        });
    };
    TGNT.changeVariant = () => {
        $(document).on("click", ".changeVariant", function () {
            let _this = $(this);
            let idCart = _this.data("id");
            let sku = _this.data("sku");
            let skuProduct = _this.attr("data-productSku");
            let divSku = $(`.cart-item[data-productSku="${skuProduct}"]`);
            $(".box-variant").addClass("hidden").css("opacity", "0");
            let url = "/gio-hang/changeVariant";
            setTimeout(() => {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    type: "POST",
                    url: url,
                    data: {
                        idCart,
                        sku,
                    },
                    success: async function () {
                        divSku.each(function () {
                            let id = $(this).data("id");
                            TGNT.getProduct(id)
                                .then((productHtml) => {
                                    $(`#cart-item-${id}`).replaceWith(
                                        productHtml
                                    );
                                })
                                .catch((error) => {
                                    console.error(
                                        "Error fetching product:",
                                        error
                                    );
                                });
                        });
                        TGNT.updateTotalCart();
                        VDmessage.show("success", "Đổi loại hàng thành công");
                    },
                    error: function (data) {},
                });
            }, 500);
        });
    };
    TGNT.updateQuantity = function (e) {
        e.preventDefault();
        var t = e.target,
            n = t.getAttribute("data-field"),
            o = t.closest("div").querySelector('input[name="' + n + '"]'),
            a = parseInt(o.value, 10) || 0;
        if (t.classList.contains("btn-plus")) {
            if (a < 1000) {
                o.value = a + 1;
                return true;
            } else {
                return false;
            }
        } else if (t.classList.contains("btn-minus")) {
            if (a > 1) {
                o.value = a - 1;
                return true;
            } else {
                return false;
            }
        }
    };
    TGNT.updateTotalByQuantity = () => {
        let timeUpdate = {};
        $(document).on("click", ".btn-plus, .btn-minus", function (e) {
            let checkQuantity = TGNT.updateQuantity(e);
            const _this = $(this)
                .closest(".input-group")
                .find(".quantity-field");
            const idCart = _this.attr("data-idCart");
            const quantity = _this.val();
            const price = $(`#origin-price-${idCart}-input`).val();
            let url = "/gio-hang/updateQuantity";
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
                            quantity,
                            idCart,
                        },
                        success: function (data) {
                            if (data.status) {
                                TGNT.updateTotalItem(idCart, quantity, price);
                                TGNT.updateTotalCart();
                                // VDmessage.show("success", "Cập nhật số lượng");
                            } else {
                                const inventory = data.inventory;
                                const quantity = data.quantity;
                                $(".quantity-field").val(quantity);
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
    TGNT.updateTotalItem = (idCart, quantity, price) => {
        const total_price = price * quantity;
        $(`#price-total-${idCart}`).html(TGNT.formatNumber(total_price));
        $(`.price-total`).val(total_price);
    };
    TGNT.updateTotalCart = () => {
        // let url = "/gio-hang/totalCart";
        // $.ajax({
        //     headers: {
        //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        //     },
        //     type: "POST",
        //     url: url,
        //     success: function (data) {
        //         if (data.totalCart == data.afterDiscount) {
        //             $("#save-price").html(``);
        //             $(".cart-discount-collection").html(
        //                 `<p class="text m-0">Không có giảm giá</p>`
        //             );
        //         } else {
        //             $(".cart-discount-collection").html(``);
        //             data.nameCollection.forEach((name, index) => {
        //                 $(".cart-discount-collection").append(`
        //                     <p class="row-1">${index + 1}. ${name}</p>
        //                 `);
        //             });
        //             $("#save-price").html(
        //                 TGNT.formatNumber(data.totalCart - data.afterDiscount)
        //             );
        //         }
        //         $("#cart-total").html(TGNT.formatNumber(data.totalCart));
        //         $("#cart-total-input").val(data.totalCart);
        //         $("#cart-total-discount-collection").html(
        //             TGNT.formatNumber(data.afterDiscount)
        //         );
        //         $("#cart-total-discount-collection-input").val(
        //             data.afterDiscount
        //         );
        //     },
        //     error: function () {
        //         console.log("lỗi");
        //     },
        // });
        let totalCart = 0;
        $(".price-total").each(function () {
            let price = $(this).val();
            totalCart += parseInt(price);
        });
        $("#cart-total").html(TGNT.formatNumber(totalCart));
        $("#cart-total-input").val(totalCart);
        $("#cart-total-discount-collection").html(TGNT.formatNumber(totalCart));
        $("#cart-total-discount-collection-input").val(totalCart);
    };
    TGNT.formatNumber = (number) => {
        number = Math.ceil(number);
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };
    TGNT.removeCart = () => {
        $(document).on("click", ".removeItem", function () {
            let _this = $(this);
            let id = _this.data("id");
            let skuProduct = _this.attr("data-productSku");
            let url = "/gio-hang/remove";
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: url,
                data: {
                    id,
                },
                success: function (data) {
                    VDmessage.show("success", "Đã xoá sản phẩm khỏi giỏ hàng");
                    $(`#cart-item-${id}`).remove();
                    let divSku = $(
                        `.cart-item[data-productSku="${skuProduct}"]`
                    );
                    divSku.each(function () {
                        let id = $(this).data("id");
                        TGNT.getProduct(id)
                            .then((productHtml) => {
                                $(`#cart-item-${id}`).replaceWith(productHtml);
                            })
                            .catch((error) => {
                                console.error("Error fetching product:", error);
                            });
                    });
                    TGNT.updateTotalCart();
                    TGNT.cartCount();
                },
                error: function (data) {},
            });
        });
    };

    $("#slide-featured").slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 2,
    });
    $(document).ready(function () {
        // TGNT.loadCart();
        TGNT.cartCount();
        TGNT.removeCart();
        TGNT.showVariant();
        TGNT.updateTotalByQuantity();
        TGNT.changeVariant();
        TGNT.updateTotalCart();
    });
})(jQuery);
