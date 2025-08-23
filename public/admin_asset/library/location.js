(function ($) {
    "use strict";
    var TGNT = {};
    TGNT.getLocation = () => {
        $(document).on("change", ".location", function () {
            let _this = $(this);
            let option = {
                data: {
                    location_id: _this.val(), // id của tỉnh thành phố
                },
                target: _this.attr("data-target"), //xác định được sẽ lấy dữ liệu cho select nào
            };
            $("." + option.target).val(0);
            TGNT.sendDataTogetLocation(option);
        });
    };

    TGNT.sendDataTogetLocation = (option) => {
        $.ajax({
            url: "/ajax/getLocation",
            type: "GET",
            data: option,
            dataType: "json",
            success: function (res) {
                
                $("." + option.target).html(res.html); //đổ dữ liệu vào select

                if (district_id != "" && option.target == "districts") {
                    $(".districts").val(district_id).trigger("change");
                }

                if (ward_id != "" && option.target == "wards") {
                    $(".wards").val(ward_id).trigger("change");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Lỗi: " + textStatus + " " + errorThrown);
            },
        });
    };

    //check xem có tỉnh thành phố được chọn hay ko
    TGNT.loadLocation = () => {
        if (typeof province_id !== 'undefined' && province_id) {
            $(".province").val(province_id).trigger("change");
        }
    };

    $(document).ready(function () {
        TGNT.getLocation();
        TGNT.loadLocation();
    });
})(jQuery);
