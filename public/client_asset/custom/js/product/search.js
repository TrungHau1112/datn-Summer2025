(function ($) {
    "use strict";

    var TGNT = {};

    TGNT.search = function () {};

    TGNT.debounce = function (fn, delay = 1000) {
        let timerId;
        return function (...args) {
            clearTimeout(timerId);
            timerId = setTimeout(() => fn(...args), delay);
        };
    };
    TGNT.cancelSearch = () => {
        $(document).off("click", "#search_close").on("click", "#search_close", function (e) {
            e.preventDefault();
            $("#search_on").val("");
            $("#search_out").hide();
            $("#search_result").empty();
            $("#search_category").empty();
            $("#search_header").empty();
        });
    };

    TGNT.formatMoney = (money) => {
        return money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
    };
    TGNT.cateChild = function (child) {
        return `
                <a href="/danh-muc/${child.slug}" class="text-danger p-3 col-4 h-25" id="cateChildren-${child.id}">
                    ${child.name}
                </a>
        `;
    };

    $(document).ready(function () {
        TGNT.search();
        TGNT.cancelSearch();
        const $searchOn = $("#search_on");
        const $searchOut = $("#search_out");
        const $searchResult = $("#search_result");
        const $searchCategory = $("#search_category");
        const $searchHeader = $("#search_header");

        if ($searchOn.length === 0) {
            console.log("Search input not found");
            return;
        }

        const makeAPICall = (searchValue) => {
            $searchHeader.html(`
                <div>
                    <img src="https://i.gifer.com/ZKZg.gif" width="20"/>
                    <span id="result_api">Đang tìm kiếm '${searchValue}'...</span>
                </div>
                <div>
                    <a href="#" id="search_close" class="text-muted">Hủy</a>
                </div>
            `);
            $searchResult.empty();
            $searchCategory.empty();
            
            if (!searchValue || searchValue.trim() === '') {
                $searchOut.hide();
                return;
            }
            
            $.ajax({
                url: `/san-pham/ajax/search-product?q=${encodeURIComponent(searchValue)}`,
                method: "GET",
                dataType: "json",
                beforeSend: function() {
                    $searchOut.show();
                },
                success: function (response) {
                    if (!response.status) {
                        $searchHeader.html(`
                            <div>
                                <span id="result_api">Có lỗi xảy ra!</span>
                            </div>
                            <div>
                                <a href="#" id="search_close" class="text-muted">Đóng</a>
                            </div>
                        `);
                        return;
                    }

                    const { products, categories } = response.data;
                    const productItems = products || [];
                    const categoryItems = categories || [];
                    const resultsFound = productItems.length > 0 || categoryItems.length > 0;
                    
                    $searchHeader.html(`
                        <div>
                            <svg width="16" height="16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="magnifying-glass" class="svg-inline--fa fa-magnifying-glass" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"></path>
                            </svg>
                            <span id="result_api">${
                                resultsFound
                                    ? `Tìm thấy ${productItems.length} sản phẩm cho '${searchValue}'`
                                    : `Không có kết quả cho '${searchValue}'`
                            }</span>
                        </div>
                        <div>
                            <a href="#" id="search_close" class="text-muted">Đóng</a>
                        </div>
                    `);
                    
                    if (productItems.length > 0) {
                        $.each(productItems, function (_, item) {
                            const discountPrice = item.discount > 0 
                                ? item.price - (item.price * item.discount) / 100 
                                : item.price;
                            
                            $searchResult.append(`
                                <a class="text-dark row align-items-center pt-3 pb-3 border-bottom" href="/san-pham/${item.slug}">
                                    <div class="col-3">
                                        <img class="img-fluid w-100 rounded" loading="lazy" src="${item.thumbnail}" alt="${item.name}">
                                    </div>
                                    <div class="col-9">
                                        <h6 class="text-dark mb-1">${item.name}</h6>
                                        <p class="text-muted small mb-1">${short_content(item.short_content || "")}</p>
                                        <span class="text-primary fw-bold">
                                            ${TGNT.formatMoney(discountPrice)}₫
                                            ${item.discount > 0 ? `<del class="text-muted ms-2">${TGNT.formatMoney(item.price)}₫</del>` : ''}
                                        </span>
                                    </div>
                                </a>
                            `);
                        });
                    }
                    
                    if (categoryItems.length > 0) {
                        $searchCategory.append('<h6 class="mb-2">Danh mục liên quan:</h6>');
                        $.each(categoryItems, function (_, item) {
                            $searchCategory.append(`
                                <a href="/san-pham?category_id=${item.id}" class="badge bg-light text-dark me-2 mb-2 text-decoration-none">
                                    ${item.name}
                                </a>
                            `);
                        });
                    }
                    
                    if (!resultsFound) {
                        $searchResult.html(`
                            <div class="text-center py-4">
                                <p class="text-muted">Không tìm thấy sản phẩm nào</p>
                            </div>
                        `);
                    }
                    
                    TGNT.cancelSearch();
                },
                error: function (xhr, status, error) {
                    console.error('Search error:', error);
                    $searchHeader.html(`
                        <div>
                            <span id="result_api" class="text-danger">Có lỗi xảy ra. Vui lòng thử lại!</span>
                        </div>
                        <div>
                            <a href="#" id="search_close" class="text-muted">Đóng</a>
                        </div>
                    `);
                }
            });
        };

        const debounce = function (fn, delay = 500) {
            let timerId;
            return function (...args) {
                clearTimeout(timerId);
                timerId = setTimeout(() => fn(...args), delay);
            };
        };

        const onInput = debounce(makeAPICall, 500);

        $searchOn.on("input", function () {
            const searchValue = $(this).val().trim();
            
            if (searchValue === '') {
                $searchOut.hide();
                return;
            }
            
            onInput(searchValue);
            TGNT.cancelSearch();
        });

        // Click outside to close
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.header-search').length) {
                $searchOut.hide();
            }
        });

        function short_content(content) {
            return content && content.length > 80
                ? content.substring(0, 80) + "..."
                : content || "";
        }
    });
})(jQuery);
