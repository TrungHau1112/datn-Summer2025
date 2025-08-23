@if (isset($comments) && count($comments))
    @foreach ($comments as $key => $comment)
        @if ($comment->parent_id == null)
            <tr class="animate__animated animate__fadeInDown animate__faster">
                <td class="">
                    <div class="form-check">
                        <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                            id="customCheckbox{{ $comment->id }}" value="{{ $comment->id }}">
                        <label class="form-check-label" for="ustomCheckbox{{ $comment->id }}"></label>
                    </div>
                </td>
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ $comment->user->avatar }}" data-fancybox="gallery">
                        <img loading="lazy" width="50" class="rounded" src="{{ $comment->user->avatar }}"
                            alt="{{ $comment->user->name }}">
                    </a>

                </td>

                <td>
                    {{ $comment->user->name }}
                </td>
                <td>
                    <span class="row-name">{{ $comment->content }}</span>
                </td>
                <td>
                    <a href="{{ route('client.collection.detail',$comment->collection->slug) }}">
                        {{ $comment->collection->name }}
                    </a>
                </td>
                <td>{{ changeDateFormat($comment->created_at) }}</td>
                @if ($comment->countReplies() == 0)
                    <td>Không có phản hồi nào</td>
                @else
                    <td>
                        <a href="{{ route('comment.reply', ['id' => $comment->id, 'page' => request()->get('page', 1)]) }}"
                            class="text-primary">
                            Có {{ $comment->countReplies() }} phản hồi</i>
                            </a< /td>
                @endif
                <td class="text-center table-actions">
                    <ul class="list-inline me-auto mb-0">
                        <x-delete :id="$comment->id" :model="ucfirst($config['model'])" />
                    </ul>
                </td>
            </tr>
        @endif
    @endforeach
@else
    <tr>
        <td colspan="9" class="text-center">Không có dữ liệu</td>
    </tr>
@endif
