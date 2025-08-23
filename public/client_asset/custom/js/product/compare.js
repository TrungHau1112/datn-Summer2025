(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    let arrayCompare = [];
    TGNT.selectProduct = () => {
        $(document).on("click", ".compare", function () {
            let _this = $(this);
            let sku = _this.data("sku");
            $(".tab-compare").removeClass("hidden");
            TGNT.addProductToCompare(sku);
        });
    };
    TGNT.addProductToCompare = (sku) => {
        let url = `/san-pham/ajax/add-compare/${sku}`;
        if (TGNT.existProduct(sku)) {
            $.ajax({
                type: "GET",
                url: url,
                data: {},
                success: function (data) {
                    VDmessage.show("success", "Chọn sản phẩm so sánh");
                    arrayCompare.push(data);
                    TGNT.renderCompare(arrayCompare);
                },
                error: function (data) {},
            });
        }
    };
    TGNT.renderCompare = (arrayCompare) => {
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        ).content;
        if (arrayCompare.length > 0) {
            $(`.tab-compare`).html(`
                <div class="row" style="height: 100%;">
                        <div class="col-md-5 col-6 align-self-center text-center border-end border-3">
                            <div class="add-product item-1 d-flex flex-column w-100 position-relative">
                                {{-- RENDER JS --}}
                            </div>
                        </div>
                        <div class="col-md-5 col-6 align-self-center text-center border-end border-3">
                            <div class="add-product item-2 d-flex flex-column w-100 position-relative" data-bs-toggle="modal" data-bs-target="#compare">
                                <i class="fa-solid fa-plus"></i> <span>Thêm sản phẩm</span>
                            </div>
                        </div>
                        <div class="submit col-md-2 col-12 align-self-center text-center">
                            <button type="submit" class="submit-compare btn btn-tgnt" disabled>So sánh</button>
                        </div>
                </div>
                 <div class="tab-compare-close">Thu gọn</div>`);
            arrayCompare.forEach((element, index) => {
                $(`.item-${index + 1}`).html(`
                    <div id="product-${element.sku}">
                        <input type="hidden" name="sku${index + 1}" value="${
                    element.sku
                }"/>
                        <div class="" data-bs-toggle="modal" data-bs-target="#compare">
                            <img src="${element.thumbnail}"
                                class="w-25 text-center" alt="">
                        </div>
                        <span style="white-space: nowrap; 
                                    text-overflow: ellipsis; 
                                    overflow: hidden">${element.name}
                        </span>
                        <span class="remove-item position-absolute top-0 end-0" id="product-${
                            element.sku
                        }" data-sku="${element.sku}">X</span>
                    </div>
                `);
            });
            if (arrayCompare.length == 2) {
                $(`.item-2`)
                    .removeAttr("data-bs-toggle")
                    .removeAttr("data-bs-target");
                $(".submit-compare").attr("disabled", false);
            }
        } else {
            $(".tab-compare").addClass("hidden");
        }
    };
    TGNT.existProduct = (sku) => {
        let exists = arrayCompare.some((item) => item.sku === sku);
        if (!exists && arrayCompare.length < 2) {
            return true;
        } else if (arrayCompare.length >= 2) {
            return false;
        } else {
            return false;
        }
    };

    TGNT.removeItem = () => {
        $(document).on("click", ".remove-item", function () {
            let sku = $(this).data("sku");
            arrayCompare = arrayCompare.filter((item) => item.sku !== sku);
            $(
                `#product-${sku}`
            ).html(`<i class="fa-solid fa-plus"></i> <span>Thêm sản
                    phẩm</span>`);
            TGNT.renderCompare(arrayCompare);
        });
    };

    TGNT.closeCompare = () => {
        $(document).on("click", ".tab-compare-close", function () {
            $(".tab-compare").addClass("hidden");
        });
    };

    $(document).ready(function () {
        TGNT.selectProduct();
        TGNT.removeItem();
        TGNT.closeCompare();
    });
    TGNT.search = function () {};
    TGNT.debounce = function (fn, delay = 1000) {
        let timerId;
        return function (...args) {
            clearTimeout(timerId);
            timerId = setTimeout(() => fn(...args), delay);
        };
    };
    TGNT.cancelSearch = () => {
        $("#search-close")
            .off("click")
            .on("click", function () {
                $("#search-on").val("");
                $("#search-out").hide();
                $("#search-result").empty();
                $("#search-header").empty();
            });
    };

    TGNT.formatMoney = (money) => {
        return money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
    };

    $(document).ready(function () {
        TGNT.search();
        TGNT.cancelSearch();
        const $searchOn = $("#search-on");
        const $searchOut = $("#search-out");
        const $searchResult = $("#search-result");
        const $searchHeader = $("#search-header");
        const $searchHeading = $("#search-heading");
        const $searchClose = $("#search-close");
        const makeAPICall = (searchValue) => {
            $searchHeading.hide();
            $searchHeader.html(`
                <div class="p-2">
                    <img src="https://i.gifer.com/ZKZg.gif" width="10%"/>
                    <span id="result_api">Kết quả cho '${searchValue}'</span>
                </div>
                <div class="p-2">
                    <a href="#" id="search-close" class="text-muted">Hủy tìm kiếm</a>
                </div>
            `);
            $searchResult.empty();
            if (!searchValue) return;

            $.ajax({
                url: `/san-pham/ajax/search-product?q=${searchValue}`,
                method: "GET",
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    const { products } = response.data;
                    const productItems = products;
                    // console.log(productItems);
                    // console.log(categoryItems);
                    const resultsFound = productItems.length > 0;
                    $searchHeader.html(`
                        <div class="p-2">
                            <span id="result_api">${
                                resultsFound
                                    ? `Kết quả cho '${searchValue}'`
                                    : `Không có kết quả cho '${searchValue}'`
                            }</span>
                        </div>
                        <div class="p-2">
                            <a style="cursor: pointer;" id="search-close" class="text-muted">Hủy tìm kiếm</a>
                        </div>
                    `);
                    if (resultsFound) {
                        $searchHeading.show();
                        if (productItems.length > 0) {
                            $.each(productItems, function (_, item) {
                                $searchResult.append(`
                                    <div class="compare text-dark row align-items-center m-2" data-sku="${
                                        item.sku
                                    }" style="cursor: pointer;">
                                        <div class="col-3">
                                            <img class="img-fluid w-100 rounded" loading="lazy" src="${
                                                item.thumbnail
                                            }" alt="undefined">
                                        </div>
                                        <div class="col-9">
                                            <h5 class="text-dark title_news"><a>${
                                                item.name
                                            }</a></h5>
                                            <span class="text-vd price fw-bold">Giá: ${
                                                item.discount > 0
                                                    ? TGNT.formatMoney(
                                                          item.price -
                                                              (item.price *
                                                                  item.discount) /
                                                                  100
                                                      ) +
                                                      '&nbsp; <del class="text-muted">' +
                                                      TGNT.formatMoney(
                                                          item.price
                                                      ) +
                                                      "</del>"
                                                    : TGNT.formatMoney(
                                                          item.price
                                                      )
                                            }</span>
                                        </div>
                                    </div>
                                `);
                            });
                        }
                    }
                    TGNT.cancelSearch();
                },

                error: function () {
                    $searchHeader.html(`
                        <span id="result_api">Có lỗi xảy ra với API. Vui lòng thử lại!</span>
                    `);
                },
            });
        };

        const debounce = function (fn, delay = 1000) {
            let timerId;
            return function (...args) {
                clearTimeout(timerId);
                timerId = setTimeout(() => fn(...args), delay);
            };
        };

        const onInput = debounce(makeAPICall, 500);

        $searchOn.on("input", function () {
            const searchValue = $(this).val();
            onInput(searchValue);
            $searchHeader.html(`
                <div class="p-2">
                    <span id="result_api">Kết quả cho '${searchValue}'</span>
                </div>
                <div class="p-2">
                    <a href="#" id="search-close" class="text-muted">Hủy tìm kiếm</a>
                </div>
            `);
            $searchOut.toggle(!!searchValue);
            $searchClose.toggle(!!searchValue);
            TGNT.cancelSearch();
        });
        function short_content(content) {
            return content.length > 100
                ? content.substring(0, 100) + "..."
                : content;
        }
        $searchClose.on("click", function () {
            $searchOn.val("");
            $searchClose.hide();
            $searchOut.hide();
            $searchHeader.empty();
            $searchResult.empty();
        });
    });
})(jQuery);
