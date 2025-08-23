<p class="fs-3 fw-bold border-top"><span class="count-comment">{{ $count_comments }} </span>
    Bình luận</p>
@if (Auth()->check())
    <form class="form-comment animate__animated animate__fadeIn mb-3">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <input type="hidden" name="parent_id" value="0">
        <input type="hidden" name="collection_id" value="{{ $collection->id }}">
        <div class="d-flex w-100 gap-3 align-items-center">
            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?background=random&name=AI' }}"
                alt="" class="rounded-circle" width="40" height="40">
            <input type="input" class="form__field" placeholder="Bình luận" name="content" id='content' />
            <button type="submit" class="btn btn-tgnt mt-2">Gửi</button>
        </div>
    </form>
@else
    <div class="d-flex w-100 gap-3 align-items-center mb-3">
        <img src="https://ui-avatars.com/api/?background=random&name=AI" alt="" class="rounded-circle"
            width="40" height="40">
        <input type="input" class="form__field" placeholder="Bạn cần phải đăng nhập mới có thể để lại ý kiến của mình"
            disabled />
        <a href="{{ route('client.auth.login') }}" class="btn btn-tgnt mt-2">Đăng nhập</a>
    </div>
@endif
@foreach ($comments as $comment)
    <div class="comment-item animate__animated animate__fadeIn border-bottom mb-4 w-100 position-relative"
        user-id="{{ $comment->user->id }}" id="comment-item-{{ $comment->id }}">
        <div class="d-flex w-100">
            <img src="{{ $comment->user->avatar }}" alt="" class="rounded-circle" width="40" height="40">
            <div class="ms-5 w-100">
                <h6 class="mb-1 text-tgnt fw-bold">{{ $comment->user->name }} <span class="text-muted"
                        style="font-size: 10px">{{ $comment->created_at }}</span>
                </h6>
                <p class="small m-0 me-2">
                    <span class="time-ago text-muted"></span>
                </p>
                <p class="m-0" id="content-{{ $comment->id }}">{{ $comment->content }}</p>
                @if (Auth()->check())
                    <div class="pt-1">
                        <button collection-id="{{ $comment->collection_id }}" parent-id="{{ $comment->id }}"
                            data-id="{{ $comment->id }}" user-id="{{ $comment->user_id }}"
                            parent_id="{{ $comment->parent_id }}" user-name="{{ $comment->user->name }}"
                            avatar="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?background=random&name=Agent' }}"
                            data-name="{{ $comment->user->name }}" data-status="parent"
                            class="btn-reply btn btn-link text-decoration-none fw-bold text-muted btnReply p-0"
                            style="font-size:12px">Phản hồi
                        </button>
                        @if ($comment->user->id == Auth::id())
                            <button data-id="{{ $comment->id }}" data-content="{{ $comment->content }}"
                                parent-id="{{ $comment->id }}" data-status="parent"
                                class="btn-edit btn btn-link text-muted text-decoration-none fw-bold  p-0"
                                style="font-size:12px">Chỉnh sửa
                            </button>
                            <p data-id="{{ $comment->id }}" data-status="parent"
                                class="remove-comment text-tgnt fw-bold p-0 position-absolute top-0 end-0"
                                style="font-size:12px;cursor: pointer;">X
                            </p>
                        @endif
                    </div>
                @endif
                <div class="reply" id="reply-{{ $comment->id }}" data-id="{{ $comment->id }}">
                    {{-- RENDER JS --}}
                </div>
                <div class="load-replies">
                    @if ($comment->children->count() > 0)
                        <button class="btn-load-replies btn btn-link text-decoration-none fw-bold text-tgnt p-0"
                            data-id="{{ $comment->id }}" data-current-limit="4" style="font-size:12px">
                            <i class="fa-solid fa-caret-down"></i>
                            <span>{{ $comment->children->count() }}<span> </span>phản hồi</span>
                        </button>
                    @endif
                </div>
                <div class="list-reply pt-3" id="list-reply-{{ $comment->id }}" data-limit="">
                    {{-- RENDER JS --}}
                </div>
            </div>
        </div>
    </div>
@endforeach
@if ($count_comment_current < $count_comments)
    <button class="load-more-comments btn btn-outline-tgnt mb-3" data-collection-id="{{ $collection->id }}">
        Tải thêm bình luận
    </button>
@endif
