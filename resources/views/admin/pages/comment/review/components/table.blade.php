@if (isset($reviews) && count($reviews))
    @foreach ($reviews as $key => $review)
        <tr class="animate__animated animate__fadeIn">
            <td class="">
                <div class="form-check">
                    <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                        id="customCheckbox{{ $review->id }}" value="{{ $review->id }}">
                    <label class="form-check-label" for="ustomCheckbox{{ $review->id }}"></label>
                </div>
            </td>
            <td>{{ $key + 1 }}</td>
            <td>
                <a href="https://ui-avatars.com/api/?background=random&name={{ $review->user->name }}"
                    data-fancybox="gallery">
                    <img loading="lazy" width="50" class="rounded"
                        src="https://ui-avatars.com/api/?background=random&name={{ $review->user->name }}"
                        alt="{{ $review->user->name }}">
                </a>
            </td>

            <td>
                <a href="#{{ $review->user->name }}">{{ $review->user->name }}</a> <br>
                @if ($review->children->count())
                    <span class="badge bg-light-warning">Đã trả lời</span>
                @endif
            </td>

            <td class="text-wrap">
                <span class="row-name">{{ $review->content }}</span>
            </td>
            <td>
                <div class="review-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $review->rating)
                            <span class="star filled text-warning"><i class="fas fa-star"></i></span>
                        @else
                            <span class="star text-warning"><i class="far fa-star"></i></span>
                        @endif
                    @endfor
                </div>
            </td>
            <td><a
                    href="{{ route('client.product.detail', $review->product->slug) }}">{{ $review->product->name }}</a>
            </td>
            <td>{{ changeDateFormat($review->created_at) }}</td>
            <td class="text-center table-actions">
                <ul class="list-inline me-auto mb-0">
                    <li class="list-inline-item align-bottom">
                        <a href="{{ route('review.reply', $review->id) }}"
                            class="avtar avtar-xs btn-link-primary btn-pc-default">
                            <i class="ti ti-message f-18"></i>
                        </a>
                    </li>

                    <x-delete :id="$review->id" :model="ucfirst($config['model'])" />
                </ul>

            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9" class="text-center">Không có dữ liệu</td>
    </tr>
@endif

<!-- Modal -->

<script>
    // 
</script>
