@props(['model'])

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
            <a href="{{ route($model.'.index') }}" class="btn btn-danger d-inline-flex d-inline-flex justify-content-center" style="flex: 1">
                <i data-feather="log-out" class="me-1"></i>
                Hủy</a>
        </div>
    </div>
</div>
