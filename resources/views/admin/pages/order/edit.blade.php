@extends('admin.layout')

@section('template')
    <x-breadcrumb :breadcrumb="$config['breadcrumb']" />
    
    {{-- ✅ Thông tin bom hàng --}}
    @php
        $cancelledCount = \App\Models\Order::where('phone', $order->phone)
            ->where('status', 'cancelled')
            ->count();
        $bomOrdersCount = \App\Models\Order::where('phone', $order->phone)
            ->where('is_bom', true)
            ->count();
    @endphp
    
    @if ($order->is_bom || $cancelledCount >= 2)
        <div class="alert {{ $order->is_bom ? 'alert-danger' : 'alert-warning' }} mb-3">
            <div class="d-flex align-items-center">
                <i class="ti ti-alert-triangle me-2"></i>
                <div>
                    @if ($order->is_bom)
                        <strong>🚨 ĐƠN HÀNG BOM!</strong>
                        <br>
                        <small>Khách hàng {{ $order->phone }} đã hủy {{ $cancelledCount }} lần. Tất cả đơn hàng của số điện thoại này đã được đánh dấu bom hàng.</small>
                    @else
                        <strong>⚠️ CẢNH BÁO!</strong>
                        <br>
                        <small>Khách hàng {{ $order->phone }} đã hủy {{ $cancelledCount }} lần. Cần đánh dấu bom hàng!</small>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Bảng tất cả đơn hàng của số điện thoại --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="ti ti-list me-2"></i>
                    Tất cả đơn hàng của số điện thoại {{ $order->phone }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Trạng thái</th>
                                <th>Bom hàng</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allOrders = \App\Models\Order::where('phone', $order->phone)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp
                            @foreach ($allOrders as $orderItem)
                                <tr class="{{ $orderItem->id == $order->id ? 'table-primary' : '' }}">
                                    <td>
                                        @if ($orderItem->id == $order->id)
                                            <strong>{{ $orderItem->code }} (Đơn hiện tại)</strong>
                                        @else
                                            <a href="{{ route('order.edit', ['id' => $orderItem->id]) }}">{{ $orderItem->code }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $orderItem->status == 'cancelled' ? 'danger' : 'success' }}">
                                            {{ __('order.status.' . $orderItem->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($orderItem->is_bom)
                                            <span class="badge bg-danger">⚠️ BOM</span>
                                        @else
                                            <span class="badge bg-success">✅ Bình thường</span>
                                        @endif
                                    </td>
                                    <td>{{ $orderItem->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <h4>Chỉnh sửa Đơn Hàng #{{ $order->id }}</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('order.update', ['id' => $order->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_name">Tên khách hàng:</label>
                            <input type="text" id="customer_name" name="name" class="form-control"
                                value="{{ $order->name }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_email">Email:</label>
                            <input type="text" id="customer_email" name="email" class="form-control"
                                value="{{ $order->email }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_phone">Phone:</label>
                            <input type="text" id="customer_phone" name="phone" class="form-control"
                                value="{{ $order->phone }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label for="customer_note">Note:</label>
                            <input type="text" id="customer_note" name="note" class="form-control"
                                value="{{ $order->note }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <!-- Phương Thức Thanh Toán -->
                        <div class="form-group mb-3">
                            <label for="payment_method_id" class="form-label">Phương Thức Thanh Toán:</label>
                            <select class="form-select" name="payment_method_id" id="payment_method_id">
                                @foreach ($paymentMethods as $item)
                                    <option {{ $item->id == $order->payment_method_id }} value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <!-- Trạng Thái -->
                        <div class="form-group">
                            <label for="status" class="form-label">Trạng Thái</label>
                            <select name="status" id="status" class="form-control js-choice-order">
                                @foreach (__('order.status') as $key => $value)
                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Trạng Thái Thanh Toán -->
                <div class="form-group mb-3">
                    <label for="payment_status" class="form-label required">Trạng Thái Thanh Toán:</label>
                    <select name="payment_status" id="payment_status" class="form-control">
                        @php
                            $paymentStatuses = __('order.payment_status');
                        @endphp

                        @if (is_array($paymentStatuses))
                            @foreach ($paymentStatuses as $key => $value)
                                <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        @else
                            <option value="">{{ $paymentStatuses }}</option>
                        @endif
                    </select>
                </div>

                <!-- Địa chỉ giao hàng -->
                @include('admin.pages.order.components.location')
                <div class="form-group mb-3">
                    <x-input :label="'Địa chỉ chi tiết'" name="address" :value="$address" :required="false" />
                </div>

                <!-- 🔁 Checkbox is_bom -->
                <div class="form-group mb-3">
                    @if ($order->is_bom)
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <strong>⚠️ CẢNH BÁO BOM HÀNG!</strong> 
                            Đơn hàng này đã được đánh dấu là bom hàng vì khách hàng đã hủy từ 2 lần trở lên.
                        </div>
                    @endif
                    
                    <div class="form-check">
                        <input type="checkbox" name="is_bom" value="1" class="form-check-input" 
                               id="is_bom_checkbox" {{ $order->is_bom ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_bom_checkbox">
                            <strong>Đánh dấu đơn bom hàng</strong>
                            <small class="text-muted d-block">(Khách hàng hủy từ 2 lần trở lên)</small>
                        </label>
                    </div>
                    
                    @php
                        $cancelledCount = \App\Models\Order::where('phone', $order->phone)
                            ->where('status', 'cancelled')
                            ->count();
                    @endphp
                    
                    @if ($cancelledCount > 0)
                        <small class="text-info">
                            <i class="ti ti-info-circle me-1"></i>
                            Số điện thoại {{ $order->phone }} đã hủy {{ $cancelledCount }} lần
                        </small>
                    @endif
                </div>

                {{-- Thêm sản phẩm --}}
                @include('admin.pages.order.components.add_product')
            </form>
        </div>
    </div>
    
    <script>
        // ✅ Tự động cập nhật checkbox bom hàng khi thay đổi trạng thái
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const isBomCheckbox = document.getElementById('is_bom_checkbox');
            const phoneInput = document.getElementById('customer_phone');
            
            function updateBomCheckbox() {
                const status = statusSelect.value;
                const phone = phoneInput.value;
                
                // Nếu status = cancelled, kiểm tra số lần hủy
                if (status === 'cancelled' && phone) {
                    // Gửi request để kiểm tra số lần hủy
                    fetch(`/admin/order/check-bom-hang?phone=${phone}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.cancelled_count >= 2) {
                                isBomCheckbox.checked = true;
                                isBomCheckbox.disabled = true;
                                
                                // Hiển thị thông báo
                                if (!document.getElementById('bom-warning')) {
                                    const warning = document.createElement('div');
                                    warning.id = 'bom-warning';
                                    warning.className = 'alert alert-warning mt-2';
                                    warning.innerHTML = `
                                        <i class="ti ti-alert-triangle me-2"></i>
                                        <strong>Tự động đánh dấu bom hàng!</strong> 
                                        Số điện thoại ${phone} đã hủy ${data.cancelled_count} lần.
                                    `;
                                    isBomCheckbox.parentNode.appendChild(warning);
                                }
                            } else {
                                isBomCheckbox.disabled = false;
                                const warning = document.getElementById('bom-warning');
                                if (warning) warning.remove();
                            }
                        });
                } else {
                    isBomCheckbox.disabled = false;
                    const warning = document.getElementById('bom-warning');
                    if (warning) warning.remove();
                }
            }
            
            // Lắng nghe sự kiện thay đổi trạng thái
            statusSelect.addEventListener('change', updateBomCheckbox);
            phoneInput.addEventListener('change', updateBomCheckbox);
            
            // Chạy lần đầu khi trang load
            updateBomCheckbox();
        });
    </script>
@endsection
