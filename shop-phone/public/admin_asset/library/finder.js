(function ($) {
    "use strict";
    var TGNT = {};

    TGNT.setupCkeditor = () => {
        if ($(".ck-editor")) {
            $(".ck-editor").each(function () {
                let editor = $(this);
                let elementId = editor.attr("id");
                let elementHeight = editor.attr("data-height");
                TGNT.ckeditor4(elementId, elementHeight);
            });
        }
    };

    TGNT.uploadAlbum = () => {
        $(document).on("click", ".upload-picture", function (e) {
            TGNT.browseServerAlbum($(this).attr("data-name"));
            e.preventDefault();
        });
    };

    TGNT.multipleUploadImageCkeditor = () => {
        $(document).on("click", ".multipleUploadImageCkeditor", function (e) {
            let object = $(this);
            let target = object.attr("data-target");
            TGNT.browseServerCkeditor(object, "Images", target);
            e.preventDefault();
        });
    };

    TGNT.ckeditor4 = (elementId, elementHeight) => {
        if (typeof elementHeight == "undefined") {
            elementHeight = 500;
        }
        CKEDITOR.replace(elementId, {
            height: elementHeight,
            removeButtons: "",
            entities: true,
            allowedContent: true,
            toolbarGroups: [
                {
                    name: "editing",
                    groups: ["find", "selection", "spellchecker", "undo"],
                },
                { name: "links" },
                { name: "insert" },
                { name: "forms" },
                { name: "tools" },
                { name: "document", groups: ["mode", "document", "doctools"] },
                { name: "others" },
                {
                    name: "basicstyles",
                    groups: [
                        "basicstyles",
                        "cleanup",
                        "colors",
                        "styles",
                        "indent",
                    ],
                },
                {
                    name: "paragraph",
                    groups: ["list", "", "blocks", "align", "bidi"],
                },
            ],
            removeButtons:
                "Save,NewPage,Pdf,Preview,Print,Find,Replace,CreateDiv,SelectAll,Symbol,Block,Button,Language",
            removePlugins: "exportpdf",
        });
    };

    TGNT.uploadImageToInput = () => {
        $(".upload-image").click(function () {
            let input = $(this);
            let type = input.attr("data-type");
            TGNT.setupCkFinder2(input, type);
        });
    };

    TGNT.uploadImageAvatar = () => {
        $(".image-target").click(function () {
            let input = $(this);
            let type = "Images";
            TGNT.browseServerAvatar(input, type);
        });
    };
    TGNT.uploadImageAvatarCus = () => {
        $(".image-target-cus").click(function () {
            let input = $(this);
            let type = "Images";
            TGNT.browseServerAvatarCus(input, type);
        });
    };

    TGNT.setupCkFinder2 = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.val(fileUrl);
        };
        finder.popup();
    };

    TGNT.browseServerAvatar = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.find("img").attr("src", fileUrl);
            object.siblings("input").val(fileUrl);
        };
        finder.popup();
    };
    TGNT.browseServerAvatarCus = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            $(".image-preview").attr("src", fileUrl);
            object.siblings("input").val(fileUrl);
            $("#point_value").val(
                $("#description_value").prop("outerHTML")
            );
        };
        finder.popup();
    };

    TGNT.browseServerCkeditor = (object, type, target) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();

        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = "";
            for (var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html += '<div class="image-content"><figure>';
                html += '<img src="' + image + '" alt="' + image + '">';
                html += "<figcaption>Nhập vào mô tả cho ảnh</figcaption>";
                html += "</figure></div>";
            }
            CKEDITOR.instances[target].insertHtml(html);
        };
        finder.popup();
    };

    TGNT.browseServerAlbum = (data_name) => {
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
                                <img src="${image}" alt="Image preview" width="100%" class="img-thumbnail">
                            </a>
                            <input type="hidden" name="${data_name}[]" value="${image}">
                        </span>
                        <div class="btn_delete_albums_tgnt">
                            <button type="button" class="delete-image btn btn-sm btn-light-danger" title="Delete Image">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </li>
            `;
            }
            $("#sortable").append(html);
        };
        finder.popup();
    };

    TGNT.deletePicture = () => {
        $(document).on("click", ".delete-image", function () {
            let _this = $(this);
            _this.parents(".ui-state-default").remove();
            if ($(".ui-state-default").length == 0) {
                console.log("không còn ảnh nào");

                // $('.click-to-upload').removeClass('hidden')
                // $('.upload-list').addClass('hidden')
            }
        });
    };

    $(document).ready(function () {
        TGNT.uploadImageToInput();
        TGNT.setupCkeditor();
        TGNT.uploadImageAvatar();
        TGNT.uploadImageAvatarCus();
        TGNT.multipleUploadImageCkeditor();
        TGNT.uploadAlbum();
        TGNT.deletePicture();
    });
})(jQuery);
