(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    let searchTimeout = "";

    let array = {
        perpage: 12,
        sort: "id,asc",
        publish: 1,
        keyword: null,
        filter: null,
        category_id: null,
        attribute_id: [],
        page: 1,
    };

    TGNT.searchForm = () => {
        $("#keyword").on("input", function () {
            clearTimeout(searchTimeout);
            array.keyword = $(this).val();
            array.page = 1;
            searchTimeout = setTimeout(() => TGNT.getData(array), 500);
        });
    };

    TGNT.selectForm = () => {
        $(".filter-option").on("change", function () {
            const fieldName = $(this).attr("name");
            const value = $(this).val();

            if (fieldName === "attribute_id") {
                const id = $(this).attr("id").split("-")[1];
                if (value != 0) {
                    array.attribute_id[id] = value;
                } else {
                    delete array.attribute_id[id];
                }
            } else {
                array[fieldName] = value;
            }
            array.page = 1;
            console.log(array);
            TGNT.getData(array);
        });
    };

    TGNT.getData = function (params = {}) {
        params.category_id = $("input[name='category_id']").val();
        const url_getProduct = $("input[name='url_getProduct']").val();
        $.ajax({
            url: url_getProduct,
            type: "GET",
            data: params,
            beforeSend: function () {
                $(".product_container").html(`
                            <div class="text-center" style="padding-top: 100px !important; padding-bottom: 100px !important">
                                    <div class="spinner-border text-tgnt" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                            </div>
                    `);
            },
            success: function (res) {
                console.log(res);
                $(".product_container").html(res.data);
                $(".product_container").html(res.data).addClass("show");
            },
        });
    };

    TGNT.paginationForm = () => {
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault();
            let page = $(this).attr("href").split("page=")[1];
            array.page = page;
            $(window).scrollTop(0);
            TGNT.getData(array);
        });
    };

    $(document).ready(function () {
        TGNT.searchForm();
        TGNT.selectForm();
        TGNT.paginationForm();
    });
})(jQuery);
