(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
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
            `<tr>
                <td colspan="100%" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </td>
            </tr>`
        );
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
                    $(".countProduct").html(array.idArray.length);
                    $(".filterProduct .title")
                        .removeClass("alert alert-primary alert-danger")
                        .addClass(
                            `alert ${
                                array.idArray.length > 1
                                    ? "alert-primary"
                                    : "alert-danger"
                            }`
                        );
                    TGNT.checkAndHighlight(array.idArray);
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

    TGNT.checkAndHighlight = (idsArray) => {
        idsArray.forEach((sku) => {
            $(`#checkInput${sku}`).prop("checked", true); // Check input
            $(`#product-item${sku}`).css("background-color", "#cce6e6"); //chuyển màu nền
        });
        function countVariant() {
            $(".product-main").each(function () {
                if ($(this).find(".product-focus").length > 0) {
                    let countVariant = $(this).find(
                        ".product-focus .checkInput:checked"
                    ).length;
                    if (countVariant > 0) {
                        $(this)
                            .find(".countVariant")
                            .html(
                                `<span class="text-muted">${countVariant}</span>`
                            );
                        $(this)
                            .find(".product-item")
                            .css("background-color", "#cce6e6");
                    } else {
                        $(this).find(".countVariant").html("");
                        $(this)
                            .find(".product-item")
                            .css("background-color", "");
                    }
                }
            });
        }
        countVariant();
        $(".checkInput").on("change", countVariant);
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
        if (typeof skuInData !== "undefined" && skuInData.length > 0) {
            array["idArray"] = skuInData;
            if (productElement) productElement.classList.remove("hidden");
            $(this).css("display", "none");
            $(".add-product").css("display", "none");
            TGNT.fetchData(array);
        } else {
            $(".add-product").on("click", function () {});
            if (productElement) productElement.classList.remove("hidden");
            array["idArray"] = [];
            array["idArray2"] = [];
            TGNT.fetchData(array);
        }
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
            const sku = $(this).data("sku");
            const item = $("#product-item" + sku);
            if ($(this).prop("checked")) {
                if (!array.idArray.includes(sku) && sku) {
                    console.log(array.idArray);
                    array.idArray.push(sku);
                    item.css("background-color", "#cce6e6");
                }

                TGNT.addPoint(sku);
            } else {
                array.idArray = array.idArray.filter((i) => i !== sku);
                console.log(array.idArray);
                item.css("background-color", "");
                $(`#product-row-${sku}`).remove();
                TGNT.calculateTotalAmount();
            }
            $(".countProduct").html(array.idArray.length);
            $(".filterProduct .title")
                .removeClass("alert alert-primary alert-danger")
                .addClass(
                    array.idArray.length > 1
                        ? "alert alert-primary"
                        : "alert alert-danger"
                );
            $("#skus").val(array.idArray.join(","));
        });
    };

    TGNT.addPoint = (sku) => {
        if (sku !== undefined) {
            if (sku.length > 0) {
                $.ajax({
                    type: "GET",
                    url: "/admin/order/getProduct",
                    data: {
                        sku: sku ? sku : "",
                    },
                    success: function (data) {
                        TGNT.renderRow(data.data);
                        TGNT.calculateTotalAmount();
                    },
                    error: function (xhr, status, error) {
                        console.log("lỗi");
                    },
                });
            } else {
                $("#renderPoints").html("");
            }
        }
    };

    TGNT.renderRow = (product) => {
        if (product) {
            const product_id = product.product_id ?? product.id;
            $("#product-table-body").append(`   
                    <tr id="product-row-${product.sku}" data-sku="${
                product.sku
            }">
                       <td>
                           <a href="${
                               product.thumbnail
                           }" data-fancybox="gallery">
                               <img src="${product.thumbnail}" alt="${
                product.name
            }" class="product-thumbnail">
                           </a>
                       </td>
                        <td>${product.sku}</td> 
                        <td class="text-wrap">
                            ${product.name}
                        </td>
                        <td class='fix-input'>
                            <input type="number" value="${
                                product.quantityOld ?? 1
                            }" class="product-quantity form-control" data-price="${
                product.price
            }" min="1" max="5" style="width: 60px;">
                        </td> 
                        <td>${TGNT.formatNumber(product.price)}</td>
                        <td class="total-price">${TGNT.formatNumber(
                            product.total ?? product.price * 1
                        )}</td>
                        <td><button type="button" class="btn btn-danger btn-sm rounded delete-product-btn" data-sku="${
                            product.sku
                        }">Xóa</button></td>
                        <td class="hidden">
                            <input type="text" name="product_id[]" value="${product_id}">
                            <input type="text" class="product_quantity_input" name="quantity[]" value="${
                                product.quantityOld ?? 1
                            }">
                            <input type="text"  name="price[]" value="${
                                product.price
                            }">
                            <input type="text" name="name_orderDetail[]" value="${
                                product.name
                            }">
                            <input type="text" name="sku[]" value="${
                                product.sku
                            }">
                            <input type="text" name="thumbnail[]" value="${
                                product.thumbnail
                            }">
                        </td>
                    </tr>
                `);
            TGNT.deleteProduct();
        }
    };
    TGNT.deleteProduct = () => {
        $(".delete-product-btn").on("click", function () {
            let _this = $(this);
            _this.parents("tr").remove();
            let sku = _this.parents("tr").find('input[name="sku[]"]').val();
            $(`#checkInput${sku}`).prop("checked", false);
            array.idArray = array.idArray.filter((i) => i !== sku);
            TGNT.checkAndHighlight(array.idArray);
            $("#product-item" + sku).css("background-color", "");
            $(".countProduct").html($(".checkInput:checked").length);
            TGNT.calculateTotalAmount();
        });
    };
    TGNT.formatNumber = (number) => {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    TGNT.calculateTotalAmount = () => {
        if(array.idArray.length == 0) {
            $('.submit-form').attr("disabled", true);
        }else{
            $('.submit-form').attr("disabled", false);
        }
        let totalAmount = 0;
        $("#product-table-body .total-price").each(function () {
            let priceText = $(this)
                .text()
                .replace("", "")
                .replace(/\./g, "")
                .trim();
            let price = parseInt(priceText);
            // totalAmount += price;
            totalAmount = totalAmount + price;
        });
        $("#total_amount").val(TGNT.formatNumber(totalAmount));
        $(".total_amount").text(TGNT.formatNumber(totalAmount));
    };

    TGNT.updateQuantity = () => {
        $(document).on("input", ".product-quantity", function () {
            let quantity = $(this).val();
            let price = $(this).data("price");
            let totalPrice = quantity * price;

            $(this).closest("tr").find(".product_quantity_input").val(quantity);
            $(this)
                .closest("tr")
                .find(".total-price")
                .text(TGNT.formatNumber(totalPrice));

            TGNT.calculateTotalAmount();
        });
    };

    TGNT.checkOld = () => {
        if (typeof productOld !== "undefined" && productOld.length > 0) {
            productOld.forEach((product) => {
                TGNT.renderRow(product);
                array.idArray.push(product.sku);
            });
            TGNT.checkAndHighlight(array.idArray);
            TGNT.calculateTotalAmount();
        }
    };

    $(document).ready(function () {
        TGNT.searchForm();
        TGNT.filterForm();
        TGNT.paginationForm();
        TGNT.fetchData();
        TGNT.showProduct();
        TGNT.checkInput();
        TGNT.addPoint();
        TGNT.updateQuantity();
        TGNT.deleteProduct();
        TGNT.calculateTotalAmount();
        TGNT.checkOld();
    });
})(jQuery);
