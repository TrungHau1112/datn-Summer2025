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
                    function checkAndHighlight(idsArray, dataAttr) {
                        idsArray.forEach((sku) => {
                            $(`.checkInput[${dataAttr}='${sku}']`).prop(
                                "checked",
                                true
                            );
                            $(`#product-item${sku}`).css(
                                "background-color",
                                "#cce6e6"
                            );
                        });
                    }
                    checkAndHighlight(array.idArray, "data-sku");
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
                    $("#skus").val(array.idArray.join(","));
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
        var point_value = $("#point_value").val();
        if (point_value && point_value.trim() !== "") {
            $("#description_value").html(point_value);
            array["idArray"] = $("#skus").val().split(",");
            array["idArray"].forEach((skuabc) => {
                TGNT.initializePopovers(skuabc);
            });
            if (productElement) productElement.classList.remove("hidden");
            $(this).css("display", "none");
            $(".add-product").css("display", "none");
            TGNT.fetchData(array);
        } else {
            if (skus && skus.length > 0) {
                array["idArray"] = skus.split(",");
                var point_value = $("#point_value").val();
                $("#description_value").html(point_value);
                array["idArray"].forEach((sku) => {
                    TGNT.initializePopovers(sku);
                });
                if (productElement) productElement.classList.remove("hidden");
                $(this).css("display", "none");
                $(".add-product").css("display", "none");
                TGNT.fetchData(array);
            }
        }

        $(".add-product").on("click", function () {
            let key = $(this).data("show");
            if (productElement) productElement.classList.remove("hidden");
            $(this).css("display", "none");
            if (key) {
                array["idArray"] = [];
                array["idArray2"] = [];
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
            const sku = $(this).data("sku");
            const item = $("#product-item" + sku);
            // const itemParent = item.attr("data-parentSku");
            if ($(this).prop("checked")) {
                if (!array.idArray.includes(sku) && sku) {
                    array.idArray.push(sku);
                    console.log(sku);
                    item.css("background-color", "#cce6e6");
                    // itemParent.css("background-color", "#cce6e6");
                }
                TGNT.addPoint(sku);
            } else {
                array.idArray = array.idArray.filter((i) => i !== sku);
                item.css("background-color", "");
                TGNT.removePoint(sku);
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
                    url: "/admin/collection/getProductPoint",
                    data: {
                        sku: sku ? sku : "",
                    },
                    success: function (data) {
                        $("#renderPoints").append(
                            `<div id="point${
                                data.sku
                            }" class="point" data-bs-toggle="popover${data.sku}"
                                title="Thông tin sản phẩm" data-bs-html="true" data-bs-trigger="hover" tabindex="0" role="button"
                                data-bs-content='
                                <div class="point_item">
                                    <div class="point_content">
                                        <img src="${data.thumbnail}" alt="">
                                        <p class="name">
                                            ${
                                                data.title
                                                    ? data.title + " / "
                                                    : ""
                                            } ${data.name ?? ""}
                                        </p>
                                        <p class="href m-0">
                                            <a href="/san-pham/${
                                                data.slug
                                            }">Xem chi tiết sản phẩm</a>
                                        </p>
                                    </div>
                                </div>'>
                                <div class="btn btn-icon btn-light-primary avtar-s">
                                    ${TGNT.selectIcon(data.category)}
                                </div>
                            </div>`
                        );
                        $("#point_value").val(
                            $("#description_value").prop("outerHTML")
                        );
                        TGNT.initializePopovers(data.sku.replace(/\s+/g, ""));
                        TGNT.collectionJs();
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
    TGNT.removePoint = (sku) => {
        if (sku !== undefined) {
            $.ajax({
                type: "GET",
                url: "/admin/collection/getProductPoint",
                data: {
                    sku: sku ? sku : "",
                },
                success: function (data) {
                    $(`#point${data.sku}`).remove();
                    $("#point_value").val($("#renderPoints").prop("outerHTML"));
                    let pointCount = $("#renderPoints").find(".point").length;
                    if (pointCount == 0) {
                        $("#point_value").val("");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("lỗi");
                },
            });
        }
    };
    TGNT.initializePopovers = (sku) => {
        if (sku !== undefined) {
            new bootstrap.Popover(document.getElementById("point" + sku), {
                trigger: "hover",
            });
        }
    };
    TGNT.collectionJs = () => {
        const point_value = document.getElementById("point_value");
        const description_value = document.getElementById("description_value");
        let isDragging = false;
        let offsetX, offsetY, currentPoint;

        // Lắng nghe sự kiện "mousedown" trên tất cả các phần tử có lớp "point" hoặc "point1"
        document.querySelectorAll(".point").forEach((point) => {
            point.addEventListener("mousedown", (e) => {
                isDragging = true;
                currentPoint = e.target.closest(".point"); // Lấy phần tử đang được kéo
                offsetX = e.clientX - currentPoint.offsetLeft;
                offsetY = e.clientY - currentPoint.offsetTop;
                currentPoint.style.cursor = "grabbing";
            });
        });

        document.addEventListener("mousemove", (e) => {
            if (isDragging && currentPoint) {
                let x = e.clientX - offsetX;
                let y = e.clientY - offsetY;

                // Chuyển đổi vị trí thành phần trăm
                let leftPercent = (x / description_value.clientWidth) * 100;
                let topPercent = (y / description_value.clientHeight) * 100;

                // Giới hạn vị trí trong khoảng từ 0% đến 100%
                leftPercent = Math.max(0, Math.min(leftPercent, 100));
                topPercent = Math.max(0, Math.min(topPercent, 100));

                currentPoint.style.left = leftPercent + "%";
                currentPoint.style.top = topPercent + "%";
                point_value.value = `Top: ${topPercent.toFixed(
                    2
                )}%, Left: ${leftPercent.toFixed(2)}% for ${currentPoint.id}`;
            }
        });

        document.addEventListener("mouseup", () => {
            if (isDragging && currentPoint) {
                isDragging = false;
                currentPoint.style.cursor = "pointer";
                const pointHtml = description_value.outerHTML;
                point_value.value = pointHtml;
                currentPoint = null;
            }
        });
    };
    TGNT.selectIcon = (category) => {
        if (category) {
            switch (category) {
                case "ghế":
                    return '<i class="fa-solid fa-chair"></i>';
                case "giường":
                    return '<i class="fa-sharp fa-solid fa-bed-front"></i>';
            }
        } else {
            return '<i class="fa-brands fa-shopify"></i>';
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
        TGNT.removePoint();
        TGNT.initializePopovers();
        TGNT.collectionJs();
    });
})(jQuery);
