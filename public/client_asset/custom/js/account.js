(function ($) {
    "use strict";

    var TGNT = {};
    const VDmessage = new VdMessage();
    const _token = $('meta[name="csrf-token"]').attr("content");

    let orderDetailCurrent = null;

    TGNT.editUser = () => {
        const form = $("form.edit-account-form");
        const saveButton = $(".saveEditAccount");
        saveButton.on("click", function (e) {
            e.preventDefault();
            const url = form.attr("action");
            const data = form.serialize();
            const name = form.find('input[name="name"]').val().trim();
            const email = form.find('input[name="email"]').val().trim();
            const phone = form.find('input[name="phone"]').val().trim();
            if (!name) {
                VDmessage.show("error", "Vui lòng nhập họ tên");
                return;
            }

            if (phone && phone.length < 10) {
                VDmessage.show("error", "Số điện thoại không hợp lệ");
                return;
            }

            if (!email) {
                VDmessage.show("error", "Vui lòng nhập email");
                return;
            }
            if (!TGNT.validateEmail(email)) {
                VDmessage.show("error", "Email không hợp lệ");
                return;
            }
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: "json",
                success: function (res) {
                    VDmessage.show(
                        res.status ? "success" : "error",
                        res.message
                    );
                    if (res.status) {
                        $(".info_account").html(res.data);
                    }
                },
                error: function () {
                    VDmessage.show("error", "Có lỗi xảy ra, vui lòng thử lại.");
                },
            });
        });
    };

    TGNT.changePassUser = () => {
        const form = $("form.change-pass-account-form");
        const saveButton = $(".changePassAccount");

        saveButton.on("click", function (e) {
            e.preventDefault();
            const url = form.attr("action");
            const data = form.serialize();
            const password_old = form
                .find('input[name="password_old"]')
                .val()
                .trim();
            const password = form.find('input[name="password"]').val().trim();
            const password_c = form
                .find('input[name="password_c"]')
                .val()
                .trim();

            if (!password_old) {
                VDmessage.show("error", "Vui lòng nhập mật khẩu cũ.");
                return;
            }
            if (!password) {
                VDmessage.show("error", "Vui lòng nhập mật khẩu mới.");
                return;
            }
            if (password_old === password) {
                VDmessage.show(
                    "error",
                    "Mật khẩu mới không được trùng với mật khẩu cũ."
                );
                return;
            }
            if (password.length < 8) {
                VDmessage.show(
                    "error",
                    "Mật khẩu mới phải chứa ít nhất 6 ký tự,"
                );
                return;
            }
            if (!password_c) {
                VDmessage.show("error", "Vui lòng nhập xác nhận mật khẩu.");
                return;
            }
            if (password !== password_c) {
                VDmessage.show("error", "Mật khẩu xác nhận không khớp.");
                return;
            }

            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: "json",
                success: function (res) {
                    VDmessage.show(
                        res.status ? "success" : "error",
                        res.message
                    );
                    if (res.status) {
                        $(".change-pass-account-form")[0].reset();
                    }
                },
                error: function () {
                    VDmessage.show("error", "Có lỗi xảy ra, vui lòng thử lại.");
                },
            });
        });
    };

    TGNT.validateEmail = (email) => {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(email);
    };

    // order
    let searchTimeout = "";
    let array = {
        perpage: 5,
        sort: "id,asc",
        keyword: null,
        page: 1,
    };

    TGNT.searchForm = () => {
        $(".keyword_order").on("input", function () {
            clearTimeout(searchTimeout);
            array.keyword = $(this).val();
            array.page = 1;
            searchTimeout = setTimeout(() => TGNT.loadOrderAll(array), 500);
        });
    };

    TGNT.loadOrderAll = (params = {}) => {
        $.ajax({
            url: $("form.search_order_all").attr("action"),
            type: "GET",
            data: params,
            beforeSend: function () {
                $(".list_all_order").html(
                    `
                        <div class="text-center pt-10">
                            <div class="spinner-border text-tgnt" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `
                );
            },
            success: function (res) {
                $(".list_all_order").html(res.data);
            },
        });
    };

    TGNT.loadOrderStatus = (status, url) => {
        console.log(status);

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function () {
                $(`.list_${status}_order`).html(
                    `
                        <div class="text-center pt-10">
                            <div class="spinner-border text-tgnt" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `
                );
            },
            success: function (res) {
                $(`.list_${status}_order`).html(res.data);
            },
        });
    };

    TGNT.paginationForm = () => {
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault();
            let page = $(this).attr("href").split("page=")[1];
            array.page = page;
            TGNT.loadOrderAll(array);
        });
    };

    TGNT.orderStatus = () => {
        $(".order_status").on("click", function () {
            TGNT.loadOrderStatus($(this).data("status"), $(this).data("url"));
        });
    };

    TGNT.checkOrder = () => {
        const exampleModal = document.getElementById("orderDetail");
        if (exampleModal) {
            exampleModal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const orderId = button.getAttribute("data-order-id");
                const url = button.getAttribute("data-order-url");
                orderDetailCurrent = orderId;
                $(".btn-close-review").attr("data-order-id", orderId);
                TGNT.loadDataOrderDetail(orderId, url);
            });
        }
    };

    TGNT.loadDataOrderDetail = (
        orderId,
        url = "/tai-khoan/ajax/get-order-detail"
    ) => {
        $.ajax({
            url: url,
            type: "GET",
            data: { id: orderId },
            dataType: "json",
            beforeSend: function () {
                $(".order_detail_html").html(
                    `
                                <div class="text-center" style="padding-top: 300px !important; padding-bottom: 300px !important">
                                    <div class="spinner-border text-tgnt" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            `
                );
            },
            success: function (res) {
                $(".order_detail_html").html(res.data);
            },
        });
    };

    TGNT.checkCancel = () => {
        const exampleModal = document.getElementById("orderCancel");

        if (exampleModal) {
            exampleModal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const orderId = button.getAttribute("data-order-id");
                const url = button.getAttribute("data-order-url");


                // Gỡ sự kiện click trước khi gắn lại
                $(".cancelOrder")
                    .off("click")
                    .on("click", function () {

                        $.ajax({
                            url: url,
                            type: "GET",
                            data: { id: orderId },
                            dataType: "json",
                            beforeSend: function () {},
                            success: function (res) {
                                VDmessage.show(
                                    res.status ? "success" : "error",
                                    res.message
                                );
                                if (res.status) {
                                    TGNT.loadOrderAll();
                                    $("#orderCancel").modal("hide");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Cancel order error:', error);
                                VDmessage.show("error", "Có lỗi xảy ra khi hủy đơn hàng");
                            }
                        });
                    });
            });
        }
    };

    TGNT.checkModalReview = () => {
        const exampleModal = document.getElementById("modal_danhgia");
        if (exampleModal) {
            exampleModal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const product_id = button.getAttribute("data-product-id");
                TGNT.add_review(product_id);
            });
        }
    };

    TGNT.add_review = (product_id) => {
        $(".add_review_tgnt")
            .off("click")
            .on("click", function () {
                const _this = $(this);
                const form = _this.closest("form");
                let rate = 0;
                form.find("input[name=star-radio]").each(function () {
                    if ($(this).is(":checked")) {
                        rate = Math.max(rate, $(this).val());
                    }
                });
                const content = form.find("textarea[name=content]").val();

                if (!rate) {
                    VDmessage.show("error", "Vui lòng chọn số sao đánh giá");
                    return;
                }
                if (!content) {
                    VDmessage.show("error", "Vui lòng nhập nội dung đánh giá");
                    return;
                }

                $.ajax({
                    url: "/san-pham/ajax/add-review",
                    type: "POST",
                    data: {
                        product_id: product_id,
                        rating: rate,
                        content: content,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res.status) {
                            VDmessage.show("success", res.message);
                            TGNT.loadOrderAll(array);
                            TGNT.loadOrderStatus(
                                "delivered",
                                "/tai-khoan/ajax/get-order-by-status/delivered"
                            );
                            form.find("input[name=star-radio]:checked").prop(
                                "checked",
                                false
                            );
                            form.find("textarea[name=content]").val("");
                            $(".modal").modal("hide");
                            TGNT.loadDataOrderDetail(orderDetailCurrent);
                            $("#orderDetail").modal("show");
                        } else {
                            VDmessage.show(
                                "error",
                                "Có lỗi xảy ra, vui lòng thử lại sau"
                            );
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    },
                });
            });
    };

    TGNT.checkModalViewReview = () => {
        const exampleModal = document.getElementById("modal_xemdanhgia");
        if (exampleModal) {
            exampleModal.addEventListener("show.bs.modal", (event) => {
                const button = event.relatedTarget;
                const product_id = button.getAttribute("data-product-id");
                const orderDetail_id = orderDetailCurrent;
                const product_slug = button.getAttribute("data-product-slug");
                window.location.href = `/san-pham/${product_slug}?tab=danh-gia`;
                // TGNT.loadReview(product_id, orderDetail_id);
            });
        }
    };

    TGNT.loadReview = (product_id, orderDetail_id) => {
        $.ajax({
            url: "/tai-khoan/ajax/get-review",
            type: "GET",
            data: { product_id: product_id, orderDetail_id: orderDetail_id },
            dataType: "json",
            success: function (res) {
                $("#view-review").html(res.data);
            },
            error: function (err) {
                console.log(err);
            },
        });
    };

    $(document).ready(function () {
        TGNT.editUser();
        TGNT.changePassUser();
        TGNT.searchForm();
        TGNT.paginationForm();
        TGNT.loadOrderAll(array);
        TGNT.orderStatus();
        TGNT.checkOrder();
        TGNT.checkCancel();
        TGNT.checkModalReview();
        TGNT.checkModalViewReview();
    });
})(jQuery);
