@if (isset($posts) && count($posts))
    @foreach ($posts as $key => $post)
        <tr class="animate__animated animate__fadeIn">
            <td class="text-center">
                <div class="form-check">
                    <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                        id="customCheckbox{{ $post->id }}" value="{{ $post->id }}">
                    <label class="form-check-label" for="customCheckbox{{ $post->id }}"></label>
                </div>
            </td>
            <td class="text-center">{{ $key + 1 }}</td>
            <td class="text-center">
                @if($post->thumbnail)
                    <a data-fancybox="gallery" href="{{ $post->thumbnail }}">
                        <img loading="lazy" width="60" height="60" class="rounded object-fit-cover" 
                             src="{{ $post->thumbnail }}" alt="{{ $post->title }}"
                             style="object-fit: cover;">
                    </a>
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-image text-muted"></i>
                    </div>
                @endif
            </td>
            <td>
                <div class="fw-bold text-truncate" style="max-width: 180px;" title="{{ $post->title }}">
                    {{ $post->title }}
                </div>
            </td>
            <td>
                <div class="text-muted text-truncate" style="max-width: 300px;" title="{{ $post->excerpt }}">
                    {{ $post->excerpt ?: 'Không có tóm tắt' }}
                </div>
            </td>
            <td class="text-center">
                <x-switchvip :value="$post" :model="ucfirst($config['model'])" />
            </td>
            <td class="text-center">
                <span class="badge bg-primary">{{ $post->user->name ?? 'ADMIN' }}</span>
            </td>
            <td class="text-center table-actions">
                <div class="btn-group" role="group">
                    <x-edit :id="$post->id" :model="$config['model']" />
                    <x-delete :id="$post->id" :model="ucfirst($config['model'])" />
                </div>
            </td>
        </tr>
    @endforeach
    <tr class="animate__animated animate__fadeIn">
        <td colspan="8" class="text-center">
            {!! $posts->links('pagination::bootstrap-4') !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="8" class="text-center py-4">
            <div class="text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p>Không có dữ liệu bài viết</p>
            </div>
        </td>
    </tr>
@endif