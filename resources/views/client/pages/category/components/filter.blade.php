<div class="card card-body mb-4 border-0">
    <div class="row">
        <div class="bar p-0 d-flex justify-content-end align-items-center flex-wrap">
            <form method="get" class="d-xxl-flex d-md-flex d-block justify-content-end align-items-center gap-4">
                <div class="mb-sm-2">
                    <select name="is_featured" id="is_featured" class="form-select filter-option">
                        <option selected="" value="0">Sản phẩm</option>
                        <option value="1">Sản phẩm nổi bật</option>
                        {{-- <option value="2">Sản phẩm không nổi bật</option> --}}
                    </select>
                </div>
                <div class="mb-sm-2">
                    <select name="sort" id="sort" class="form-select filter-option">
                        <option selected="" value="0">Sắp xếp theo</option>
                        <option value="view,desc">Sản phẩm phổ biến nhất</option>
                        <option value="price,desc">Từ giá cao đến thấp</option>
                        <option value="price,asc">Từ giá thấp đến cao</option>
                        <option value="name,asc">Tên từ A - Z</option>
                        <option value="name,desc">Tên từ Z - A</option>
                    </select>
                </div>
                @foreach (getAttributeCategory() as $item)
                    <div class="mb-sm-2">
                        <select name="attribute_id" id="attribute_id-{{ $item->id }}" class="form-select filter-option">
                            <option selected="" value="0">{{ $item->name }}</option>
                            @foreach ($item->attributes as $value)
                                <option value="{{ $value->id }}">{{ $value->value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                {{-- <div class="mb-sm-2">
                    <select name="perpage" id="perpage" class="form-select filter-option">
                        <option selected="" value="0">12 sản phẩm</option>
                        <option value="16">16 sản phẩm</option>
                        <option value="20">20 sản phẩm</option>
                        <option value="24">24 sản phẩm</option>
                        <option value="28">28 sản phẩm</option>
                    </select>
                </div> --}}
                <div class="mb-sm-2">
                    <div class="input-group">
                        <input style="max-width: 500px;" name="q" id="keyword" class="form-control rounded" type="text"
                            placeholder="Tìm kiếm sản phẩm..." />
                        <span class="input-group-append">
                            <button type="button"
                                class="btn bg-white border border-start-0 ms-n10 rounded-0 rounded-end">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-search">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    /* mobile  */
    @media (max-width: 767px) {
        .mb-sm-2 {
            margin-bottom: 0.5rem !important;
        }
    }
</style>