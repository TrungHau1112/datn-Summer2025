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
                                "Mã giảm giá không khả dụng"
                            );
                        }
                    },
                    error: function (data) {
                        // VDmessage.show(
                        //     "error",
                        //     "Mã giảm giá không khả dụng"
                        // );
                        VDmessage.show("error", "Mã giảm giá không khả dụng");
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
                console.log("lỗi");
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
    $(document).ready(function () {
        TGNT.updateTotalCart();
        TGNT.addDiscount();
        TGNT.removeDiscount();
        TGNT.form_payment();
    });
})(jQuery);
