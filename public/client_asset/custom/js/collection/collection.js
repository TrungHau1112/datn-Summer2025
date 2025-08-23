(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    TGNT.renderJs = () => {
        $(".point").each((index, element) => {
            var popover = new bootstrap.Popover(
                document.getElementById(element.id),
                {
                    trigger: "focus",
                }
            );
        });
    };
    $(document).ready(function () {
        TGNT.renderJs();
    });
})(jQuery);
