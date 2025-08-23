@foreach ($comment_childs as $childComment)
    <div class="comment-item pb-3 animate__animated animate__fadeIn w-100 position-relative" id="comment-item-{{ $childComment->id }}">
        <div class="d-flex">
            <img src="{{ $childComment->user->avatar }}" alt="" class="rounded-circle" width="40" height="40">
            <div class="ms-5 w-100">
                <h6 class="mb-1 text-tgnt fw-bold">{{ $childComment->user->name }} <span class="text-muted"
                        style="font-size: 10px">{{ $childComment->created_at }}</span></h6>
                <p class="m-0" id="content-{{ $childComment->id }}">{!! $childComment->content !!}</p>
                @if (Auth()->check())
                    <div class="pt-1">
                        <button collection-id="{{ $comment->collection->id }}" parent-id="{{ $comment->id }}"
                            avatar="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?background=random&name=Agent' }}"
                            data-name="{{ $childComment->user->name }}" data-id="{{ $childComment->id }}"
                            user-id="{{ $childComment->user->id }}" data-status="child"
                            class="btn-reply btn btn-link text-decoration-none fw-bold text-muted btnReply p-0"
                            style="font-size:12px">Phản hồi
                        </button>
                        @if ($childComment->user->id == Auth::id())
                            <button data-id="{{ $childComment->id }}" data-content="{{ $childComment->content }}" parent-id="{{ $comment->id }}"
                                data-status="child"
                                class="btn-edit btn btn-link text-muted text-decoration-none fw-bold  p-0"
                                style="font-size:12px">Chỉnh sửa
                            </button>
                            <p data-id="{{ $childComment->id }}" data-status="child"
                                class="remove-comment text-tgnt fw-bold p-0 position-absolute top-0 end-0"
                                style="font-size:12px; cursor: pointer;">X
                            </p>
                        @endif
                    </div>
                @endif
                <div class="reply" id="reply-{{ $childComment->id }}" data-id="{{ $comment->id }}">

                </div>
            </div>
        </div>
    </div>
@endforeach
@if ($count_comment_childs_current < $count_comment_childs)
    <button class="btn-load-more-replies btn btn-link text-decoration-none fw-bold text-muted p-0"
        comment-id="{{ $comment->id }}" style="font-size:12px">
        Tải thêm bình luận
    </button>
@endif

