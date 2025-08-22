@if (isset($posts) && count($posts))
    @foreach ($posts as $key => $post)
        <tr class="animate__animated animate__fadeIn">
            <td class="">
                <div class="form-check">
                    <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                        id="customCheckbox{{ $post->id }}" value="{{ $post->id }}">
                    <label class="form-check-label" for="ustomCheckbox{{ $post->id }}"></label>
                </div>
            </td>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>
                <a data-fancybox="gallery" href="{{ $post->thumbnail }}">
                    <img loading="lazy" width="80" class="rounded" src="{{ $post->thumbnail }}" alt="{{ $post->title }}">
                </a>
            </td>

            <td>{{ $post->title }}</td>
            <td>{{ $post->excerpt }}</td>

            <td>
                <x-switchvip :value="$post" :model="ucfirst($config['model'])" />
            </td>
            <td>{{ $post->user->name }}</td>
            <td class="text-center table-actions">
                <ul class="list-inline me-auto mb-0">
                    <x-edit :id="$post->id" :model="$config['model']" />
                    <x-delete :id="$post->id" :model="ucfirst($config['model'])" />
                </ul>
            </td>
        </tr>
    @endforeach
    <tr class="animate__animated animate__fadeIn">
        <td colspan="100">
            {!! $posts->links('pagination::bootstrap-4') !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif