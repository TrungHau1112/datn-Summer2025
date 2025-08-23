@props(['value_meta_title', 'value_meta_description', 'value_slug'])

<div class="card">
    <div class="card-header">
        Cấu hình SEO
    </div>
    <div class="card-body">
        <div class="form-group mt-3 mb-3">
            <label for="meta_title" class="d-flex justify-content-between">
                <div>Tiêu đề SEO</div>
                <small> (<span
                        class="count_meta_title">{{ $value_meta_title ? strlen($value_meta_title) : 0 }}</span>/60)</small>
            </label>
            <input value="{{ old('meta_title', $value_meta_title) ?? '' }}" type="text" class="form-control"
                id="meta_title" name="meta_title">
        </div>
        <div class="form-group mb-3">
            <label for="meta_description" class="d-flex justify-content-between">
                <div>Mô tả SEO</div>
                <small> (<span class="count_meta_description">
                        {{ $value_meta_description ? strlen($value_meta_description) : 0 }}</span>/160)</small>
            </label>
            <textarea rows="3" class="form-control" id="meta_description"
                name="meta_description">{{ old('meta_description', $value_meta_description) ?? '' }}</textarea>
        </div>


        <div class="form-group mb-3">
            <div class="mb-3">
                <label class="form-label" for="meta_description">Đường dẫn</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="sieu-thi-noi-that">{{ url('/url') }}/</span>
                    <input value="{{ $value_slug ?? '' }}" type="text" name="slug" class="form-control"
                        id="meta_description" aria-describedby="sieu-thi-noi-that">
                </div>
            </div>
        </div>
    </div>
</div>