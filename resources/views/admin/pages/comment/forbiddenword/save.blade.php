
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    <x-form :config="$config" :model="$forbiddenword ?? null">
        <div class="row">
            <!-- Cột bên trái chứa các thông tin cơ bản -->
            <div class="col-lg-9 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Thêm mới từ cấm
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary" role="alert">
                            <strong>Lưu ý:</strong> <span class="text-danger">(*)</span> là trường bắt buộc nhập
                        </div>
                        <x-input :label="'Từ cấm'" :name="'word'" :value="$forbiddenword->word ?? ''" :required="true" />
                            {{-- <div class="alert alert-secondary" role="alert">
                                <h5 class="alert-heading">Chọn hành động <span class="text-danger">*</span></h5>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="deleteComment" name="actions[]" value="delete">
                                    <label class="form-check-label" for="deleteComment">Xóa bình luận</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="banUser" name="actions[]" value="ban_user">
                                    <label class="form-check-label" for="banUser">Cấm người dùng bình luận 12 giờ</label>
                                </div>
                                @error('actions')
                                    <small class="error text-danger">*{{ $message }}</small>
                                @enderror
                            </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 mb-4">
                <div class="card" style="position: sticky; top: 0; z-index:100;">
                    <div class="card-header">
                        Hành động
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between" style="gap: 5%">
                            <button type="submit" name="send" value="send" class="btn btn-primary d-inline-flex justify-content-center" style="flex: 1">
                                <i data-feather="check-circle" class="me-1"></i>
                                Lưu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-form>

