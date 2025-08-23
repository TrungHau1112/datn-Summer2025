@if (isset($users) && count($users))
    @foreach ($users as $key => $user)
        <tr class="animate__animated animate__fadeIn">
            <td class="">
                @if ($user->id != auth()->id() && !$user->hasRole('Super Admin'))
                    <div class="form-check">
                        <input class="form-check-input input-primary input-checkbox checkbox-item alotest" type="checkbox"
                            id="customCheckbox{{ $user->id }}" value="{{ $user->id }}">
                        <label class="form-check-label" for="customCheckbox{{ $user->id }}"></label>
                    </div>
                @endif
            </td>
            <td>{{ $key + 1 }}</td>
            <td>
                <a href="https://ui-avatars.com/api/?background=random&name={{ $user->name }}" data-fancybox="gallery">
                    <img loading="lazy" width="50" class="rounded"
                        src="https://ui-avatars.com/api/?background=random&name={{ $user->name }}"
                        alt="{{ $user->name }}">
                </a>
            </td>
            <td>
                <div class="row-name text-primary">{{ $user->name }}</div>
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ changeDateFormat($user->created_at) }}</td>
            <td class="text-center">
                {{ $user->getRoleNames()->isempty() ? 'Khách' : $user->getRoleNames()->join(', ') }}
            </td>
            <td class="text-center">
                <div class="{{ $user->hasRole('Super Admin') ? 'hidden' : '' }}">
                    @if ($user->id != auth()->id())
                        <x-switchvip :value="$user" :model="ucfirst($config['model'])" />
                    @else
                        <span class="badge badge-success">-</span>
                    @endif
                </div>
            </td>
            <td class="text-center table-actions">
                {{-- @dd() --}}
                <div class="{{ $user->hasRole('Super Admin') ? 'hidden' : '' }}">
                    <ul class="list-inline me-auto mb-0">
                        <x-edit :id="$user->id" :model="$config['model']" />
                        @if ($user->id != auth()->id())
                            <x-delete :id="$user->id" :model="ucfirst($config['model'])" />
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    <tr class="animate__animated animate__fadeInDown animate__faster">
        <td colspan="100">
            {!! $users->links('pagination::bootstrap-4') !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif
