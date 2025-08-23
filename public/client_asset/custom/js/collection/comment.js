(function () {
    "use strict";
    var TGNT = {};
    const VDmessage = new VdMessage();
    let limit = 5;
    TGNT.render = () => {
        let collection_slug = $(".collection_tgnt").data("slug");
        if (collection_slug) {
            $.ajax({
                url: `/bo-suu-tap/ajax/get-comments/${collection_slug}`,
                type: "GET",
                data: {
                    collection_slug: collection_slug,
                    limit: limit,
                },
                success: function (res) {
                    $(".comment-collection").html(res);
                },
                error: function (err) {
                    console.log(err);
                },
            });
        }
    };
    TGNT.loadComments = () => {
        $(document).on("click", ".load-more-comments", function () {
            limit += 5;
            TGNT.render();
        });
        $(document).on("click", ".btn-load-more-replies", function () {
            let comment_id = $(this).attr("comment-id");
            let limit = $(`#list-reply-${comment_id}`).attr("data-limit");
            limit += limit;
            TGNT.loadCommentReply(comment_id, limit);
        });
        $(document).on(
            "click",
            ".btn-load-replies, .btn-hide-replies",
            function () {
                let $this = $(this);
                let comment_id = $this.data("id");
                if ($this.hasClass("btn-load-replies")) {
                    $this
                        .removeClass("btn-load-replies")
                        .addClass("btn-hide-replies");
                    TGNT.loadCommentReply(comment_id, 5);
                    $(`#list-reply-${comment_id}`).attr("data-limit", 5);
                } else {
                    $this
                        .removeClass("btn-hide-replies")
                        .addClass("btn-load-replies");
                    $(`#list-reply-${comment_id}`).html(``);
                }
            }
        );
    };
    TGNT.loadCommentReply = (comment_id, limit) => {
        $.ajax({
            type: "GET",
            url: `/bo-suu-tap/ajax/get-replies/${comment_id}/${limit}`,
            success: function (response) {
                $(`#list-reply-${comment_id}`).html(response);
            },
            error: function (error) {
                console.error("Error:", error);
            },
        });
    };
    TGNT.commentSubmit = () => {
        $(document).on("submit", ".form-comment", function (e) {
            e.preventDefault();
            let _this = $(this);
            let data = new URLSearchParams(_this.serialize());
            const dataObject = {};
            data.forEach((value, key) => {
                dataObject[key] = value;
            });
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: "/bo-suu-tap/ajax/comment/store",
                data: dataObject,
                success: function (response) {
                    if (response != false) {
                        $(".comment-content").val("");
                        VDmessage.show("success", "Bình luận thành công");
                        TGNT.render();
                    } else {
                        VDmessage.show("error", "Chứa từ không hợp lệ");
                    }
                },
                error: function (error) {
                    console.error("Error:", error);
                },
            });
        });
    };
    TGNT.btnShowCommentReply = () => {
        $(document).on("click", ".btn-reply", function () {
            let _this = $(this);
            let id = _this.data("id");
            let collection_id = _this.attr("collection-id");
            let user_id = _this.attr("user-id");
            let name = _this.data("name");
            let parent_id = _this.attr("parent-id");
            let avatar = _this.attr("avatar");
            $(`#reply-${id}`).html(`
                <form class="form-reply animate__animated animate__fadeIn my-2" id="form-reply-${id}" data-id="${id}" collection-id="${collection_id}" parent-id="${parent_id}">
                        <input type="hidden" name="user_id" value="${user_id}">
                        <input type="hidden" name="parent_id" value="${parent_id}">
                        <input type="hidden" name="collection_id" value="${collection_id}">
                        <div class="d-flex w-100">
                            <img src="${avatar}" alt="" class="rounded-circle" width="40" height="40">
                            <div class="w-100">
                                <input type="input" class="form__field ps-1 ms-2" placeholder="Phản hồi" name="content" id='content' value="${name} "/>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="pe-2" style="transform: rotate(180deg);"><i class="fa-solid fa-reply"></i></div> 
                                <span class="ps-2" style="font-size:12px">Phản hồi <span class="fw-bold text-tgnt">${name}</span></span>
                            </div>
                            <div>
                                <button type="button" class="cancel-form btn btn-outline-tgnt mt-2" data-id="${id}">Huỷ</button>
                                <button type="submit" class="btn btn-tgnt mt-2">Trả lời</button>
                            </div>
                        </div>
                    </form>
                `);
        });
    };
    TGNT.btnShowCommentEdit = () => {
        $(document).on("click", ".btn-edit", function () {
            let _this = $(this);
            let id = _this.data("id");
            let content = _this.data("content");
            let parent_id = _this.attr("parent-id");
            let status = _this.data("status");
            $(`#content-${id}`).html(`
                <form class="form-edit animate__animated animate__fadeIn my-2" id="form-reply-${id}" data-id="${id}" parent-id="${parent_id}" data-status="${status}"> 
                        <div class="d-flex w-100">
                            <div class="w-100">
                                <input type="hidden" class="form__field ps-1 ms-2" placeholder="Phản hồi" name="id" id="" value="${id}"/>
                                <input type="input" class="form__field ps-1 ms-2" placeholder="Phản hồi" name="content" id="content" value="${content}"/>
                            </div>
                        </div>
                        <div class="d-flex text-end">
                            <div>
                                <button type="button" class="cancel-edit btn btn-outline-tgnt mt-2" data-id="${id}" data-content="${content}">Huỷ</button>
                                <button type="submit" class="btn btn-tgnt mt-2">Lưu</button>
                            </div>
                        </div>
                    </form>
                `);
        });
    };
    TGNT.cancelForm = () => {
        $(document).on("click", ".cancel-form", function () {
            let _this = $(this);
            let id = _this.data("id");
            $(`#form-reply-${id}`).remove();
        });
        $(document).on("click", ".cancel-edit", function () {
            let _this = $(this);
            let id = _this.data("id");
            let content = _this.data("content");
            $(`#content-${id}`).html(
                `<p class="m-0" id="content-${id}">${content}</p>`
            );
        });
    };
    TGNT.replySubmit = () => {
        $(document).on("submit", ".form-reply", function (e) {
            e.preventDefault();
            let _this = $(this);
            let id = _this.data("id");
            let parent_id = _this.attr("parent-id");
            let status = _this.data("status");
            let data = new URLSearchParams(_this.serialize());
            const dataObject = {};
            data.forEach((value, key) => {
                dataObject[key] = value;
            });
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: "/bo-suu-tap/ajax/comment/store",
                data: dataObject,
                success: function (response) {
                    if (response != false) {
                        VDmessage.show("success", "Phản hồi thành công");
                        if (status == "parent") {
                            TGNT.render();
                        } else {
                            TGNT.loadCommentReply(parent_id, 5);
                        }
                        $(`#form-reply-${id}`).remove();
                    } else {
                        VDmessage.show("error", "Chứa từ không hợp lệ");
                    }
                },
                error: function (error) {
                    console.error("Error:", error);
                },
            });
        });
    };
    TGNT.editSubmit = () => {
        $(document).on("submit", ".form-edit", function (e) {
            e.preventDefault();
            let _this = $(this);
            let id = _this.data("id");
            let parent_id = _this.attr("parent-id");
            let status = _this.data("status");
            let data = new URLSearchParams(_this.serialize());
            const dataObject = {};
            data.forEach((value, key) => {
                dataObject[key] = value;
            });
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: "/bo-suu-tap/ajax/comment/update",
                data: dataObject,
                success: function (response) {
                    VDmessage.show("success", "Chỉnh sửa thành công");
                    if (status == "parent") {
                        TGNT.render();
                    } else {
                        TGNT.loadCommentReply(parent_id, 5);
                    }
                },
                error: function (error) {
                    console.error("Error:", error);
                },
            });
        });
    };
    TGNT.removeComment = () => {
        $(document).on("click", ".remove-comment", function () {
            let id = $(this).data("id");
            let status = $(this).data("status");
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: `/bo-suu-tap/ajax/comment/remove`,
                data: {
                    id: id,
                },
                success: function (response) {
                    VDmessage.show("success", "Xoá thành công");
                    $(`#comment-item-${id}`).remove();
                    if (status == "parent") {
                        TGNT.render();
                    } else {
                        TGNT.loadCommentReply(parent_id, 5);
                    }
                },
                error: function (error) {
                    console.error("Error:", error);
                },
            });
        });
    };
    $(document).ready(function () {
        TGNT.render();
        TGNT.loadComments();
        TGNT.commentSubmit();
        TGNT.btnShowCommentReply();
        TGNT.btnShowCommentEdit();
        TGNT.replySubmit();
        TGNT.editSubmit();
        TGNT.cancelForm();
        TGNT.removeComment();
    });
})(jQuery);
