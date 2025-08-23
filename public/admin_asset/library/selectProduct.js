(function ($) {
    "use strict";
    var TGNT = {};
    let searchTimeout = "";
    let array = {
        actions: 0,
        perpage: 10,
        publish: 0,
        sort: "id,asc",
        keyword: null,
        filter: null,
    };

    TGNT.getModel = () => {
        let model = $("#filter").data("model");
        return model || "user";
    };

    TGNT.fetchData = (params = {}) => {
        $("#tbody").html(
            `<tr><td colspan="100%" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div></td></tr>`
        );
        // console.log(array);
        const model = TGNT.getModel();
        const url =
            Object.values(array).length > 6
                ? `/getProduct`
                : `/${model}/getData`;
        $.ajax({
            type: "GET",
            url: url,
            data: { ...array, ...params },
            success: function (data) {
                if (url.includes("getProduct")) {
                    $("#content").html(data);
                    array.idArray.forEach((id) => {
                        $(".checkInput[data-id='" + id + "']").prop(
                            "checked",
                            true
                        );
                        $("#product-item" + id).css(
                            "background-color",
                            "#cce6e6"
                        );
                    });
                    $(".countProduct").html(array.idArray.length);
                    $(".filterProduct .title").addClass(
                        array.idArray.length > 2
                            ? "alert alert-primary"
                            : "alert alert-danger"
                    );
                    $("#idProduct").val(array.idArray.join(","));
                } else {
                    $("#tbody").html(data);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data:", error);
                $("#tbody").html(
                    `<tr><td colspan="100%" class="text-center">Lỗi khi tải dữ liệu</td></tr>`
                );
            },
        });
    };

    TGNT.searchForm = () => {
        $("#keyword").on("input", function () {
            clearTimeout(searchTimeout);
            array.keyword = $(this).val();
            searchTimeout = setTimeout(
                () => TGNT.fetchData({ keyword: array.keyword }),
                500
            );
        });
    };

    TGNT.filterForm = () => {
        $(".filter-option").on("change", function () {
            array[$(this).attr("name")] = $(this).val();
            TGNT.fetchData(array);
        });
    };

    TGNT.showProduct = () => {
        const productElement = document.querySelector(".show-product");
        if (idProduct && idProduct.length > 0) {
            if (productElement) productElement.classList.remove("hidden");
            $(this).css("display", "none");
            array["idArray"] = idProduct;
            $(".add-product").css("display", "none");
            TGNT.fetchData(array);
        }
        $(".add-product").on("click", function () {
            let key = $(this).data("show");
            if (productElement) productElement.classList.remove("hidden");
            $(this).css("display", "none");
            if (key) {
                array["idArray"] = [];
                TGNT.fetchData(array);
            }
        });
    };

    TGNT.paginationForm = () => {
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault();
            let page = $(this).attr("href").split("page=")[1];
            TGNT.fetchData({ page });
        });
    };
    TGNT.checkInput = () => {
        $(document).on("change", ".checkInput", function () {
            const id = $(this).data("id");
            const item = $("#product-item" + id);
            if ($(this).prop("checked")) {
                if (!array.idArray.includes(id)) {
                    array.idArray.push(id);
                    item.css("background-color", "#cce6e6");
                }
            } else {
                item.css("background-color", "");
                array.idArray = array.idArray.filter((i) => i !== id);
            }
            $(".countProduct").html(array.idArray.length);
            $(".filterProduct .title")
                .removeClass("alert alert-primary alert-danger")
                .addClass(
                    array.idArray.length > 2
                        ? "alert alert-primary"
                        : "alert alert-danger"
                );
            $("#idProduct").val(array.idArray.join(","));
        });
    };

    $(document).ready(function () {
        TGNT.searchForm();
        TGNT.filterForm();
        TGNT.paginationForm();
        TGNT.fetchData();
        TGNT.showProduct();
        TGNT.checkInput();
    });
})(jQuery);
