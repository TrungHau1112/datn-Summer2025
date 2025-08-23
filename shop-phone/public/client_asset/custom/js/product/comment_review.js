(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    let user_id = "";

    TGNT.render = () => {
        $.ajax({
            url: "/san-pham/ajax/get-review",
            type: "GET",
            data: {
                product_id: product_id,
            },
            dataType: "json",
            success: function (res) {
                $(".tab_review_tgnt").html(res.data);
            },
            error: function (err) {
                console.log(err);
            },
        });
    };

    $(document).ready(function () {
        TGNT.render();
    });
})(jQuery);
