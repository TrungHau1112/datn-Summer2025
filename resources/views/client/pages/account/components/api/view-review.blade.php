
<div class="modal-header">
    <h5 class="modal-title" id="modal_danhgiaTitle">Xem đánh giá của bạn</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-order-id="{{ $orderDetail_id }}"
        data-order-url="{{ route('client.account.get-order-detail') }}" data-bs-toggle="modal"
        data-bs-target="#orderDetail">
        <span aria-hidden="true"></span>
    </button>
</div>
<div class="modal-body">
    <div class=" py-3 mb-3">
        <h4 class="mb-3 p-0">Đánh giá chung</h4>
        <div class="d-flex justify-content-center">
            <small class="text-warning">
                @for ($i = 0; $i < floor($data->rating); $i++)
                    <i class="bi bi-star-fill"></i>
                @endfor
                @if ($data->rating - floor($data->rating) >= 0.5)
                    <i class="bi bi-star-half"></i>
                @endif
                @for ($i = 0; $i < 5 - ceil($data->rating); $i++)
                    <i class="bi bi-star"></i>
                @endfor
            </small>
        </div>
    </div>
    <div class=" py-3 mb-3">
        <h5>Nội dung đánh giá</h5>
        <textarea disabled name="content" type="text" class="form-control" rows="3"
            placeholder="Bạn thích hay không thích điểu gì? Trải nghiệm sản phẩm của bạn như thế nào?">{{ $data->content }}</textarea>
    </div>
</div>
