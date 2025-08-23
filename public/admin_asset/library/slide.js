(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    const MAX_SLIDES = 3;

    TGNT.addSlide = () => {
        $(".add-slide").on("click", function () {
            if ($(".slide-item").length < MAX_SLIDES) {
                $(".slide-list").append(TGNT.htmlSelect(collections));
                TGNT.updateState();
                TGNT.setupSelect2();
            }
            TGNT.toggleAddButton();
        });
    };

    TGNT.htmlSelect = (data) => {
        let html = `
            <tr class="slide-item">
                <td>

                </td>
                <td class="text-center">
                    <button type="button" class="remove-slide btn btn-outline-danger d-inline-flex justify-content-center">
                        <i class="ti ti-trash f-18 me-2"></i> Xóa
                    </button>
                </td>
            </tr>`;
        return html;
    };

    TGNT.updateState = () => {
        // let selectedIds = [];
        // $(".collection-select").each(function () {
        //     let value = $(this).val();
        //     if (value) {
        //         selectedIds.push(value);
        //     }
        // });

        // $(".collection-select").each(function () {
        //     let currentValue = $(this).val();
        //     $(this)
        //         .find("option")
        //         .each(function () {
        //             if (
        //                 selectedIds.includes($(this).val()) &&
        //                 $(this).val() !== currentValue
        //             ) {
        //                 $(this).attr("disabled", true);
        //             } else {
        //                 $(this).attr("disabled", false);
        //             }
        //         });
        // });
    };

    TGNT.removeSlide = () => {
        $(".slide-list").on("click", ".remove-slide", function () {
            $(this).closest(".slide-item").remove();
            TGNT.updateState();
            TGNT.toggleAddButton();
        });
    };

    TGNT.toggleAddButton = () => {
        if (
            $(".slide-item").length >= MAX_SLIDES ||
            collections.length == $(".slide-item").length
        ) {
            $(".add-slide").attr("disabled", true);
        } else {
            $(".add-slide").attr("disabled", false);
        }
    };

    TGNT.checkOnchange = () => {
        $(document).on("change", ".collection-select", function () {
            TGNT.updateState();
        });
    };

    TGNT.setupSelect2 = () => {
        $(".select2").select2();
    };

    $(document).ready(function () {
        TGNT.addSlide();
        TGNT.removeSlide();
        TGNT.toggleAddButton();
        TGNT.checkOnchange();
        TGNT.updateState();
    });
})(jQuery);
