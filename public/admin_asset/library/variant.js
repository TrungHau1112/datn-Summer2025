(function ($) {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();

    TGNT.setupProductVariant = () => {
        if ($(".turnOnVariant").length) {
            $(document).on("click", ".turnOnVariant", function () {
                let _this = $(this);
                let price = parseFloat($('input[name="price"]').val()) || 0;
                let sku = $('input[name="sku"]').val().trim();

                if (price <= 0 || sku === "") {
                    VDmessage.show(
                        "warning",
                        "Vui lòng nhập giá tiền sản phẩm và SKU cho sản phẩm để sử dụng tính năng này"
                    );
                    _this.prop("checked", false);
                    return false;
                }

                $(".variant-wrapper").toggleClass(
                    "hidden",
                    !_this.is(":checked")
                );
            });
        }
    };

    TGNT.addVariant = () => {
        if ($(".add-variant").length) {
            $(document).on("click", ".add-variant", function () {
                let html = TGNT.renderVariantItem(attributeCatalogue);
                $(".variant-body").append(html);
                $(".variantTable thead").html("");
                $(".variantTable tbody").html("");
                TGNT.checkMaxAttributeGroup(attributeCatalogue);
                TGNT.disabledAttributeCatalogueChoose();
                TGNT.checkAllData();
            });
        }
    };

    TGNT.renderVariantItem = (attributeCatalogue) => {
        let options = attributeCatalogue
            .map(
                (attribute) =>
                    `<option value="${attribute.id}">${attribute.name}</option>`
            )
            .join("");

        return `
            <div class="row mb-3 variant-item">
                <div class="col-lg-3">
                    <div class="attribute-catalogue">
                        <select name="attributeCatalogue[]" id="" class="choose-attribute niceSelect">
                            <option value="">Chọn Nhóm thuộc tính</option>
                            ${options}
                        </select>
                    </div>
                </div>
                <div class="col-lg-8">
                    <input type="text" name="" disabled class="fake-variant h-100 form-control">
                </div>
                <div class="col-lg-1">
                    <button type="button" class="h-100 w-100 remove-attribute btn btn-icon btn-danger">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        `;
    };

    TGNT.chooseVariantGroup = () => {
        $(document).on("change", ".choose-attribute", function () {
            let _this = $(this);
            let attributeCatalogueId = _this.val();
            if (attributeCatalogueId != 0) {
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(TGNT.select2Variant(attributeCatalogueId));
                $(".selectVariant").each(function (key, index) {
                    TGNT.getSelect2($(this));
                });
            } else {
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(
                        `<input type="text" name="attributeValue[${attributeCatalogueId}][]" disabled="" class="fake-variant form-control">`
                    );
            }

            TGNT.disabledAttributeCatalogueChoose();
        });
    };

    TGNT.createProductVariant = () => {
        $(document).on("change", ".selectVariant", function () {
            let _this = $(this);
            TGNT.createVariant();
            TGNT.checkAllData();
        });
    };

    TGNT.createVariant = () => {
        let attributes = [];
        let variants = [];
        let attributeTitle = [];

        $(".variant-item").each(function () {
            let _this = $(this);
            let attr = [];
            let attrVariant = [];

            const attributeCatalogueId = _this.find(".choose-attribute").val();
            const optionText = _this
                .find(".choose-attribute option:selected")
                .text();
            const attribute = $(".variant-" + attributeCatalogueId).select2(
                "data"
            );

            attribute.forEach((attrItem) => {
                let item = {};
                let itemVariant = {};

                item[optionText] = attrItem.text;
                itemVariant[attributeCatalogueId] = attrItem.id;

                attr.push(item);
                attrVariant.push(itemVariant);
            });

            attributeTitle.push(optionText);
            attributes.push(attr);
            variants.push(attrVariant);
        });

        let attributesNew = TGNT.generateVariants(attributes);
        let variantsNew = TGNT.generateVariants(variants);

        TGNT.createTableHeader(attributeTitle);
        let trClass = [];

        attributesNew.forEach((attribute, index) => {
            let $row = TGNT.createVariantRow(attribute, variantsNew[index]);
            let classModified =
                "tr-variant-" +
                Object.values(variantsNew[index])
                    .join(", ")
                    .replace(/, /g, "-");

            trClass.push(classModified);
            if (!$(`table.variantTable tbody tr.${classModified}`).length) {
                $("table.variantTable tbody").append($row);
            }
        });

        $("table.variantTable tbody tr").each(function () {
            const $row = $(this);
            const rowClasses = $row.attr("class");

            if (rowClasses) {
                const rowClassArray = rowClasses.split(" ");
                let shouldRemove = true;
                rowClassArray.forEach((item) => {
                    if (trClass.includes(item)) {
                        shouldRemove = false;
                    }
                });

                if (shouldRemove) {
                    $row.remove();
                }
            }
        });
    };

    TGNT.createVariantRow = (attributeItem, variantItem) => {
        let attributeString = Object.values(attributeItem).join(", ");
        let attributeId = Object.values(variantItem).join(", ");
        let classModified = attributeId.replace(/, /g, "-");
        let $row = $("<tr>").addClass(
            "variant-row tr-variant-" + classModified
        );
        let $td;
        $td = $("<td>").append(
            $("<span>")
                .addClass("img img-cover")
                .append(
                    $("<a>")
                        .attr(
                            "href",
                            "https://placehold.co/600x600?text=Thoi%20Trag%20\nQuan%20Ao"
                        )
                        .addClass("td-thumbnai-pre")
                        .attr("data-fancybox", "gallery")
                        .attr("data-caption", "")
                        .append(
                            $("<img>")
                                .attr("width", "50")
                                .addClass("td-thumbnail rounded")
                                .attr(
                                    "src",
                                    "https://placehold.co/600x600?text=Thoi%20Trag%20\nQuan%20Ao"
                                )
                                .attr("alt", "")
                        )
                )
        );
        $row.append($td);
        Object.values(attributeItem).forEach((value) => {
            $td = $("<td>").text(value);
            $row.append($td);
        });

        $td = $("<td>").addClass("hidden td-variant");
        let mainPrice = $('input[name="price"]').val();
        let mainSku = $('input[name="sku"]').val();
        let inputHiddenFields = [
            {
                name: "variant[sku][]",
                class: "variant_sku",
                value: mainSku + "-" + classModified,
            },
            {
                name: "variant[quantity][]",
                class: "variant_quantity",
                value: "0",
            },
            {
                name: "variant[price][]",
                class: "variant_price",
                value: mainPrice,
            },
            { name: "variant[albums][]", class: "variant_albums" },
            { name: "productVariantValue[name][]", value: attributeString },
            { name: "productVariantValue[id][]", value: attributeId },
        ];
        $.each(inputHiddenFields, function (_, field) {
            let $input = $("<input>")
                .attr("type", "text")
                .attr("name", field.name)
                .addClass(field.class);
            if (field.value) {
                $input.val(field.value);
            }
            $td.append($input);
        });

        $row.append($("<td>").addClass("td-quantity").text("0"))
            .append($("<td>").addClass("td-price").text(mainPrice))
            .append(
                $("<td>")
                    .addClass("td-sku")
                    .text(mainSku + "-" + classModified)
            )
            .append(
                $("<td>")
                    .addClass("table-actions text-center")
                    .append(
                        $("<ul>")
                            .addClass("list-inline me-auto mb-0")
                            .append(
                                $("<button>")
                                    .addClass(
                                        "btn btn-sm btn-primary btnUpdateVariant me-2"
                                    )
                                    .attr("type", "button")
                                    .append($("<i>").addClass("ti ti-edit"))
                                // $("<button>")
                                //     .addClass(
                                //         "btn btn-sm btn-danger btnDeleteVariant"
                                //     )
                                //     .attr("type", "button")
                                //     .append($("<i>").addClass("ti ti-trash"))
                            )
                    )
            )
            .append($td);
        return $row;
    };

    TGNT.deleteVariant = () => {
        $(document).on("click", ".btnDeleteVariant", function () {
            let _this = $(this);
            _this.parents(".variant-row").remove();
        });
    };

    TGNT.createTableHeader = (attributeTitle) => {
        let $thead = $("table.variantTable thead");
        $thead.addClass("animate__animated animate__fadeIn");

        let $row = $("<tr>").addClass("table-pri");
        $row.append($("<th>").text("Hình ảnh").attr("scope", "col"));

        attributeTitle.forEach((element) => {
            $row.append($("<th>").text(element));
        });

        $row.append($("<th>").text("Số lượng").attr("scope", "col"));
        $row.append($("<th>").text("Giá tiền").attr("scope", "col"));
        $row.append($("<th>").text("SKU").attr("scope", "col"));
        $row.append(
            $("<th>")
                // .text("Hành động")
                .attr("scope", "col")
                .addClass("text-center")
        );

        $thead.html($row);

        return $thead;
    };

    TGNT.generateVariants = (attributes) => {
        let results = [];
        function helper(current, depth) {
            if (depth === attributes.length) {
                results.push(current);
                return;
            }
            attributes[depth].forEach((item) => {
                helper({ ...current, ...item }, depth + 1);
            });
        }
        helper({}, 0);
        return results;
    };

    TGNT.updateVariant = () => {
        $(document).on("click", ".btnUpdateVariant", function () {
            let _this = $(this);
            let parents = _this.parents(".variant-row");
            let variantData = {};
            parents
                .find(".td-variant input[type=text][class^='variant_']")
                .each(function () {
                    let className = $(this).attr("class");
                    variantData[className] = $(this).val();
                    // console.log(className);
                });
            if ($(".updateVariantRow").length == 0) {
                parents.after(TGNT.renderUpdateVariantHtml(variantData));
                TGNT.sortui();
            }
        });
    };

    TGNT.renderUpdateVariantHtml = (variantData) => {
        let variantAlbums = variantData.variant_albums.split(",");
        let html = `
            <tr class="updateVariantRow animate__animated animate__fadeIn">
                <td colspan="10">
                    <div class="updateVariant card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Cập nhật thông tin phiên bản</span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-danger cancleUpdate">Hủy</button>
                                    <button type="button" class="btn btn-sm btn-primary saveUpdate">Lưu lại</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label text-left">Hình ảnh sản phẩm</label>
                                <div class="card">
                                    <div class="card-body pb-2">
                                        <div class="upload-list mt-2">
                                            <ul id="sortableVariant" class="albums-variant row list-unstyled clearfix sortui ui-sortable" style="margin-bottom:0 !important">
                                                <li class="col-xl-2 col-md-3 col-sm-6 mb-3 d-flex justify-content-center align-items-center">
                                                    <a style="font-size: 50px" class="upload-picture-variant text-primary" data-name="variant_albums">
                                                        <i class="fa-duotone fa-solid fa-cloud-arrow-up"></i>
                                                    </a>
                                                </li>`;
        if (variantAlbums != "") {
            variantAlbums.forEach((element) => {
                html += TGNT.variantAlbumList(element);
            });
        }
        html += `</ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row me-0">
                                <div class="col-lg-4">
                                    <div class="mb-3 position-relative">
                                        <label class="form-label" for="sku">SKU <span class="text-danger">*</span></label>
                                        <input readonly class="form-control" type="text" name="variant_sku" id="sku" value="${
                                            variantData.variant_sku
                                        }">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3 position-relative">
                                        <label class="form-label" for="quantity">Số lượng <span class="text-danger">*</span></label>
                                        <input class="form-control int" type="text" name="variant_quantity" id="quantity" value="${TGNT.addCommas(
                                            variantData.variant_quantity ?? 0
                                        )}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3 position-relative">
                                        <label class="form-label" for="variant_price">Giá tiền <span class="text-danger">*</span></label>
                                            <input type="text" name="variant_price" value="${TGNT.addCommas(
                                                variantData.variant_price
                                            )}" id="variant_price" class="form-control int">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>`;
        return html;
    };

    TGNT.variantAlbumList = (album) => {
        let html = "";
        html += `<li class="ui-state-default img_li_tgnt col-xl-2 col-md-3 col-sm-6 mb-3">
                    <div class="thumb img_albums_tgnt">
                        <span class="span image img-scaledown">
                            <a href="${album}" data-fancybox="gallery" data-caption="">
                                <img src="${album}" alt="" width="100%" class="img-thumbnail">
                            </a>
                            <input type="hidden" name="variant_albums[]" value="${album}">
                        </span>
                        <div class="btn_delete_albums_tgnt">
                            <button type="button" class="delete-image btn btn-sm btn-light-danger" title="Delete Image">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </li>`;
        return html;
    };

    TGNT.cancleVariantUpdate = () => {
        $(document).on("click", ".cancleUpdate", function () {
            let _this = $(this);
            _this.parents(".updateVariantRow").remove();
        });
    };

    TGNT.closeUpdateVariant = () => {
        $(".updateVariantRow").remove();
    };

    TGNT.saveVariantUpdate = () => {
        $(document).on("click", ".saveUpdate", function () {
            let variant = {
                sku: $('input[name="variant_sku"]').val(),
                quantity: $('input[name="variant_quantity"]').val(),
                price: $('input[name="variant_price"]').val(),
                discount: $('input[name="variant_discount"]').val(),
                albums: $('input[name="variant_albums[]"]')
                    .map(function () {
                        return $(this).val();
                    })
                    .get(),
            };
            $.each(variant, function (key, value) {
                $(".updateVariantRow")
                    .prev()
                    .find(".variant_" + key)
                    .val(value);
            });
            TGNT.previewVariantTr(variant);
            TGNT.closeUpdateVariant();
        });
    };

    TGNT.previewVariantTr = (variant) => {
        let option = {
            quantity: variant.quantity,
            price: variant.price,
            sku: variant.sku,
            discount: variant.discount,
        };
        $.each(option, function (key, value) {
            $(".updateVariantRow")
                .prev()
                .find(".td-" + key)
                .html(value);
        });
        $(".updateVariantRow")
            .prev()
            .find(".td-thumbnail")
            .attr("src", variant.albums[0]);
        $(".updateVariantRow")
            .prev()
            .find(".td-thumbnai-pre")
            .attr("href", variant.albums[0]);
    };

    TGNT.addCommas = (nStr) => {
        nStr = String(nStr);
        nStr = nStr.replace(/\./gi, "");
        let str = "";
        for (let i = nStr.length; i > 0; i -= 3) {
            let a = i - 3 < 0 ? 0 : i - 3;
            str = nStr.slice(a, i) + "." + str;
        }
        str = str.slice(0, str.length - 1);
        return str;
    };

    TGNT.getSelect2 = (object) => {
        $(object).select2({
            minimumInputLength: 1,
            placeholder:
                "Nhập tối thiểu 1 kí tự để tìm kiếm giá trị thuộc tính",
            ajax: {
                url: "/ajax/getAttributeValue",
                type: "GET",
                dataType: "json",
                deley: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        attribute_id: object.attr("data-catid"),
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.data,
                    };
                },
                cache: true,
            },
        });
    };

    TGNT.niceSelect = () => {
        $(".niceSelect").niceSelect();
    };

    TGNT.destroyNiceSelect = () => {
        if ($(".niceSelect").length) {
            $(".niceSelect").niceSelect("destroy");
        }
    };

    TGNT.disabledAttributeCatalogueChoose = () => {
        let id = [];
        $(".choose-attribute").each(function () {
            let _this = $(this);
            let selected = _this.find("option:selected").val();
            if (selected != 0) {
                id.push(selected);
            }
        });

        $(".choose-attribute").find("option").removeAttr("disabled");
        for (let i = 0; i < id.length; i++) {
            $(".choose-attribute")
                .find("option[value=" + id[i] + "]")
                .prop("disabled", true);
        }
        TGNT.destroyNiceSelect();
        TGNT.niceSelect();
        $(".choose-attribute").find("option:selected").removeAttr("disabled");
    };

    TGNT.checkMaxAttributeGroup = (attributeCatalogue) => {
        let variantItem = $(".variant-item").length;
        if (variantItem == 2) {
            $(".add-variant").remove();
        } else {
            $(".variant-foot").html(
                '<button type="button" class="btn btn-primary add-variant">Thêm phiên bản mới</button>'
            );
        }
    };

    TGNT.removeAttribute = () => {
        $(document).on("click", ".remove-attribute", function () {
            let _this = $(this);
            _this.parents(".variant-item").remove();
            TGNT.checkMaxAttributeGroup(attributeCatalogue);
            if ($(".variant-item").length == 0) {
                $(".variantTable thead").html("");
                $(".variantTable tbody").html("");
                return false;
            }
            TGNT.createVariant();
        });
    };

    TGNT.select2Variant = (attributeCatalogueId) => {
        let html = `
            <select 
                class="selectVariant variant-${attributeCatalogueId} form-control" 
                name="attributeValue[${attributeCatalogueId}][]" multiple 
                data-catid="${attributeCatalogueId}">
            </select>`;
        return html;
    };

    TGNT.uploadAlbumVariant = () => {
        $(document).on("click", ".upload-picture-variant", function (e) {
            TGNT.browseServerAlbumVariant($(this).attr("data-name"));
            e.preventDefault();
        });
    };

    TGNT.browseServerAlbumVariant = (data_name) => {
        var type = "Images";
        var finder = new CKFinder();

        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = "";
            for (var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html += `
                    <li class="ui-state-default img_li_tgnt col-xl-2 col-md-3 col-sm-6 mb-3">
                        <div class="thumb img_albums_tgnt">
                            <span class="span image img-scaledown">
                                <a href="${image}" data-fancybox="gallery" data-caption="">
                                    <img src="${image}" alt="${image}" width="100%" class="img-thumbnail">
                                </a>
                                <input type="hidden" name="${data_name}[]" value="${image}">
                            </span>
                            <div class="btn_delete_albums_tgnt">
                                <button type="button" class="delete-image btn btn-sm btn-light-danger" title="Delete Image">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </li>`;
            }
            $("#sortableVariant").append(html);
        };
        finder.popup();
    };

    TGNT.setupSelectMultiple = (callback) => {
        if ($(".selectVariant").length) {
            let count = $(".selectVariant").length;

            $(".selectVariant").each(function () {
                let _this = $(this);
                let attributeCatalogueId = _this.attr("data-catid");
                if (attributeValue != []) {
                    $.get(
                        "/ajax/loadAttributeValue",
                        {
                            attributeValue: attributeValue,
                            attributeCatalogueId: attributeCatalogueId,
                        },
                        function (data) {
                            data.data.forEach(function (item) {
                                var option = new Option(
                                    item.text,
                                    item.id,
                                    true,
                                    true
                                );
                                _this.append(option).trigger("change");
                            });
                            if (--count == 0 && callback) {
                                callback();
                            }
                        }
                    );
                }
                TGNT.getSelect2(_this);
                TGNT.checkMaxAttributeGroup(attributeCatalogue);
            });
        }
    };

    TGNT.productVariant = () => {
        variant = JSON.parse(atob(variant));
        console.log(variant);

        let newData = [];
        variant.sku.forEach((value, index) => {
            newData.push({
                sku: value,
                quantity: variant.quantity[index],
                price: variant.price[index],
                albums: variant.albums[index],
            });
        });
        console.log(newData);

        $(".variant-row").each(function (index) {
            let _this = $(this);
            let skuCell = _this.find(".td-sku").text().trim();
            let variantData = newData.find((item) => item.sku === skuCell);
            if (variantData) {
                _this.find(".td-quantity").text(variantData.quantity);
                _this.find(".td-price").text(variantData.price);
                _this.find(".td-sku").text(variantData.sku);
                let albumImages = variantData.albums.split(",");
                let firstImage =
                    albumImages.length > 0
                        ? albumImages[0]
                        : "https://placehold.co/600x600?text=Hinh%20Anh";

                _this.find(".td-thumbnail").attr("src", firstImage);
                _this.find(".td-thumbnai-pre").attr("href", firstImage);

                let inputHiddenFields = [
                    {
                        name: "variant[sku][]",
                        class: "variant_sku",
                        value: variantData.sku || "",
                    },
                    {
                        name: "variant[quantity][]",
                        class: "variant_quantity",
                        value: variantData.quantity || "0",
                    },
                    {
                        name: "variant[price][]",
                        class: "variant_price",
                        value: variantData.price || "0",
                    },
                    {
                        name: "variant[albums][]",
                        class: "variant_albums",
                        value: variantData.albums || "",
                    },
                ];

                inputHiddenFields.forEach((element) => {
                    let inputField = _this.find(
                        `input[name="${element.name}"]`
                    );
                    if (inputField.length) {
                        inputField.val(element.value);
                    } else {
                        _this.append(
                            `<input type="hidden" name="${element.name}" value="${element.value}">`
                        );
                    }
                });
            }
        });
    };

    TGNT.sortui = () => {
        $("#sortableVariant").sortable();
        $("#sortableVariant").disableSelection();
    };

    TGNT.checkAllData = () => {
        $(".choose-attribute").each(function () {
            if ($(this).val() == "") {
                $("button[type=submit]").prop("disabled", true);
            }
            $(".selectVariant").each(function () {
                if ($(this).val() == "") {
                    $("button[type=submit]").prop("disabled", true);
                } else {
                    $("button[type=submit]").prop("disabled", false);
                }
            });
        });
    };

    $(document).ready(function () {
        TGNT.setupProductVariant();
        TGNT.addVariant();
        TGNT.niceSelect();
        TGNT.chooseVariantGroup();
        TGNT.removeAttribute();
        TGNT.createProductVariant();
        TGNT.uploadAlbumVariant();
        TGNT.updateVariant();
        TGNT.cancleVariantUpdate();
        TGNT.saveVariantUpdate();
        TGNT.deleteVariant();
        TGNT.setupSelectMultiple(() => TGNT.productVariant());
        TGNT.checkAllData();
    });
})(jQuery);
