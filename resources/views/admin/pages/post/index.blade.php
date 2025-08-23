@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />

    <div class="card">
        <div class="card-header">
            <x-filter :model="$config['model']" :createButton="[
                'label' => '',
                'route' => $config['model'] . '.create',
            ]" :options="[
                'actions' => generateSelect('Hành động', __('general.actions')),
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                'publish' => generateSelect('Trạng thái', __('general.publish')),
                'sort' => generateSelect('Sắp xếp', __('general.sort')),
            ]" />
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th width="60">STT</th>
                            <th width="100">Ảnh</th>
                            <th width="200">Tiêu đề</th>
                            <th>Tóm tắt</th>
                            <th width="120">Trạng thái</th>
                            <th width="120">Người tạo</th>
                            <th width="120" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @include('admin.pages.post.components.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Tối ưu hiệu suất cho bảng lớn
    const table = $('.table');
    const tbody = table.find('tbody');
    
    // Lazy loading cho ảnh
    const images = table.find('img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.each(function() {
            imageObserver.observe(this);
        });
    }
    
    // Smooth scroll cho table
    $('.table-responsive').on('scroll', function() {
        const scrollLeft = $(this).scrollLeft();
        const maxScroll = this.scrollWidth - this.clientWidth;
        
        if (scrollLeft > 0) {
            $(this).addClass('scrolled');
        } else {
            $(this).removeClass('scrolled');
        }
    });
    
    // Tooltip cho text bị cắt
    $('.text-truncate').each(function() {
        const text = $(this).text();
        const parent = $(this).parent();
        if (text.length > 50) {
            parent.attr('title', text);
        }
    });
    
    // Animation cho hover effect
    $('.table tbody tr').hover(
        function() {
            $(this).addClass('hover-effect');
        },
        function() {
            $(this).removeClass('hover-effect');
        }
    );
    
    // Responsive table helper
    function checkTableOverflow() {
        const table = $('.table');
        const container = $('.table-responsive');
        
        if (table.width() > container.width()) {
            container.addClass('has-overflow');
        } else {
            container.removeClass('has-overflow');
        }
    }
    
    // Check overflow on load and resize
    checkTableOverflow();
    $(window).on('resize', checkTableOverflow);
    
    // Auto-refresh data (optional)
    // setInterval(function() {
    //     // Refresh table data every 30 seconds
    //     location.reload();
    // }, 30000);
});
</script>
@endpush

@push('style')
<style>
    /* Full width cho trang quản lý bài viết */
    .pc-container {
        max-width: none !important;
        width: 100% !important;
    }
    
    .pc-content {
        padding: 1.5rem !important;
        max-width: none !important;
        width: 100% !important;
    }
    
    .card {
        width: 100%;
        max-width: none;
        margin: 0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
        border-radius: 8px 8px 0 0 !important;
    }
    
    .table-responsive {
        overflow-x: auto;
        min-width: 100%;
        border-radius: 0 0 8px 8px;
    }
    
    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 12px 8px;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .table th,
    .table td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 12px 8px;
        border-bottom: 1px solid #dee2e6;
    }
    
    /* Styling cho ảnh */
    .table img {
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .table img:hover {
        border-color: #007bff;
        transform: scale(1.05);
    }
    
    /* Styling cho badge */
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
    
    /* Styling cho buttons */
    .btn-group .btn {
        margin: 0 2px;
        border-radius: 4px;
    }
    
    /* Responsive cho mobile */
    @media (max-width: 768px) {
        .pc-content {
            padding: 1rem !important;
        }
        
        .table-responsive {
            font-size: 12px;
        }
        
        .table th,
        .table td {
            padding: 8px 4px;
        }
        
        .table th:nth-child(4),
        .table td:nth-child(4) {
            min-width: 150px;
            white-space: normal;
        }
        
        .table th:nth-child(5),
        .table td:nth-child(5) {
            min-width: 200px;
            white-space: normal;
        }
        
        .card-header {
            padding: 1rem;
        }
    }
    
    /* Tối ưu cho tablet */
    @media (min-width: 769px) and (max-width: 1024px) {
        .table th:nth-child(4),
        .table td:nth-child(4) {
            min-width: 180px;
        }
        
        .table th:nth-child(5),
        .table td:nth-child(5) {
            min-width: 250px;
        }
    }
    
    /* Animation cho loading */
    .animate__fadeIn {
        animation-duration: 0.5s;
    }
    
    /* Custom scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush
