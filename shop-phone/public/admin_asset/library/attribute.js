(function ($) {
    "use strict";
    var TGNT = {};

    TGNT.add_attribute_value = () => {
        $(document).on("click", ".add_attribute_value", function () {
            $(".attribute_value_container").append(TGNT.renderHTML());
        });

        $(document).on("click", ".remove_attribute_value", function () {
            $(this).closest(".input-group").remove();
        });

        $(document).on("input", ".attribute_input", function () {
            let currentValue = $(this).val().trim();
            let isDuplicate = false;

            $(".attribute_input")
                .not(this)
                .each(function () {
                    if ($(this).val().trim() === currentValue) {
                        isDuplicate = true;
                        return false;
                    }
                });

            if (isDuplicate) {
                $(this).addClass("is-invalid");
                $(this).next(".error-message").remove();
                $('[type="submit"][name="send"]').attr("disabled", true);
            } else {
                $(this).removeClass("is-invalid");
                $('[type="submit"][name="send"]').attr("disabled", false);
            }
        });
    };

    TGNT.checkOldValue = () => {
        if (typeof attribute_values !== "undefined" && attribute_values) {
            attribute_values.forEach((value) => {
                $(".attribute_value_container").append(TGNT.renderHTML(value));
            });
        }
    };

    TGNT.renderHTML = (data = null) => {
        let html = "";
        html += `
        <div class="input-group animate__animated animate__fadeInDown animate__faster mb-3">
                                        <input type="text" class="form-control attribute_input"
                                            placeholder="" name="attribute_value[][]"  value="${
                                                data ?? ""
                                            }"> 
                                            <button class="btn btn-outline-danger remove_attribute_value" type="button"><i class="ti ti-trash"></i></button>
                                    </div>
           `;
        return html;
    };

    $(document).ready(function () {
        TGNT.add_attribute_value();
        TGNT.checkOldValue();
    });
})(jQuery);
