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

        const model = TGNT.getModel();
        $.ajax({
            type: "GET",
            url: `/${model}/getData`,
            data: { ...array, ...params },
            success: function (data) {
                $("#tbody").html(data);
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
        $("#keyword").on("input", function (e) {
            let _this = $(this);
            let keyword = _this.val();
            if (keyword !== undefined) {
                array['keyword'] = keyword;
            }
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                TGNT.fetchData({ keyword });
            }, 500);
        });
    };

    TGNT.filterForm = () => {
        $(".filter-option").on("change", function () {
            let key = $(this).attr("name");
            let value = $(this).val();
            if (key) {
                array[key] = value;
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

    $(document).ready(function () {
        TGNT.searchForm();
        TGNT.filterForm();
        TGNT.paginationForm();
        TGNT.fetchData();
})
})(jQuery);