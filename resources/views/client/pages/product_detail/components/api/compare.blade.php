<form action="{{route('client.product.compare')}}" method="post">
    @csrf
    <div class="tab-compare container hidden">
        {{-- <div class="row" style="height: 100%;">
        <div class="col-md-5 col-6 align-self-center text-center border-end border-3">
            <div class="add-product item-1 d-flex flex-column w-100 position-relative">
            </div>
        </div>
        <div class="col-md-5 col-6 align-self-center text-center border-end border-3">
            <div class="add-product item-2 d-flex flex-column w-100 position-relative" data-bs-toggle="modal" data-bs-target="#compare"><i class="fa-solid fa-plus"></i> <span>Thêm sản
                    phẩm</span></div>
        </div>
        <div class="submit col-md-2 col-12 align-self-center text-center">
            <button class="btn btn-tgnt">So sánh</button>
        </div>
    </div> 
    <div class="tab-compare-close">Thu gọn</div> --}}


    </div>
</form>
    <!-- Modal -->
    <div class="modal fade" id="compare" tabindex="-1" aria-labelledby="compareLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="compareLabel">Chọn sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-block w-100" style="position: relative">
                        <form action="" method="get" class="header-search">
                            <div class="input-group justify-content-center">
                                <input style="max-width: 500px" id="search-on" name="abc"
                                    class="form-control rounded" type="text" placeholder="Tìm kiếm sản phẩm..." />
                                <span class="input-group-append">
                                    <button type="button"
                                        class="btn bg-white border border-start-0 ms-n10 rounded-0 rounded-end">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-search">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65">
                                            </line>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                            <div class="search-out card card-body p-0" id="search-out">
                                <div class="search-header d-flex justify-content-between" id="search-header">
                                    <!-- render status từ api -->
                                </div>
                                <div class="search-heading" id="search-heading">
                                    <!--  -->
                                </div>
                                <div class="search-result" id="search-result" style="overflow: scroll; height: 50vh">
                                    <!-- render bài viết từ api -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- <div class="modal-footer">
          <button type="button" class="btn btn-outline-tgnt" data-bs-dismiss="modal">Đóng</button>
        </div> --}}
            </div>
        </div>
    </div>
