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
            const _this = $(this);
            const attribute_id = _this.attr("data-attributeId");
            const attribute_name = _this.text();
            _this
                .addClass("active")
                .attr("disabled", true)
                .siblings()
                .removeClass("active")
                .attr("disabled", false);
            TGNT.handleAttribute();
        });
    };

    TGNT.handleAttribute = () => {
        const attribute_ids = $(".attribute-value .choose-attribute.active")
            .map(function () {
                return $(this).attr("data-attributeId");
            })
            .get();
        const url = new URL(window.location.href);
        const attrParams = attribute_ids;
        attrParams.sort((a, b) => a - b);
        if (attrParams.length > 0) {
            url.searchParams.set("attr", attrParams.join(","));
        } else {
            url.searchParams.delete("attr");
        }
        const newUrl = url.toString().replace(/%2C/g, ",");
        window.history.pushState({}, "", newUrl);

        const allSelected = $(".attribute")
            .toArray()
            .every((item) => {
                return $(item).find(".choose-attribute.active").length > 0;
            });
        if (allSelected) {
            $.ajax({
                url: "/san-pham/ajax/get-variant",
                type: "GET",
                data: {
                    attribute_id: attribute_ids,
                    product_id: $("input[name=product_id]").val(),
                },
                dataType: "json",
                success: function (res) {
                    window.location.reload();
                },
            });
        }
    };


    TGNT.formatMoney = () => {
        $(".price").each(function () {
            var value = $(this).text();
            $(this).text(value.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
        });
    };



    $(document).ready(function () {
        TGNT.updateQuantity();
        TGNT.selectVariantProduct();
        TGNT.formatMoney()
    });
})(jQuery);
