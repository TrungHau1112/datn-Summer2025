<tr class="animate__animated animate__fadeInDown animate__faster">
    <td class="">
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input input-checkbox checkbox-item" type="checkbox" value="{{ $reply->id }}"
                id="customCheckbox{{ $reply->id }}">
            <label for="customCheckbox{{ $reply->id }}" class="custom-control-label"></label>
        </div>
    </td>
    <td>{{ $reply->id }}</td>
    <td class="text-secondary">{{ html_entity_decode('&#9492;') . $char }}
        <a href="{{ $reply->user->avatar }}" data-fancybox="gallery">
            <img loading="lazy" width="50" class="rounded" src="{{ $reply->user->avatar }}"
                alt="{{ $reply->user->name }}">
        </a>

    </td>

    <td>
        {{ $reply->user->name }}
    </td>

    <td>
        <span class="row-name">{{ $reply->content }}</span>
    </td>
    <td>{{ $reply->collection->name }}</td>
    <td>{{ changeDateFormat($reply->created_at) }}</td>
    <td class="text-center table-actions">
        <ul class="list-inline me-auto mb-0">
            <x-delete :id="$reply->id" :model="ucfirst($config['model'])" />
        </ul>
    </td>
</tr>
@foreach ($reply->replies as $reply)
    @include('admin.pages.comment.components.comment', [
        'reply' => $reply,
        'char' => $char . html_entity_decode('&#9472;&#9472;&#9472;&#9472;'),
    ])
@endforeach
{{-- 
    ┖	&#9494; 
    →	&#8594;
--}}
