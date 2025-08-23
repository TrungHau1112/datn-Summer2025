<!-- Blade Template -->
@if (isset($orders) && count($orders))
    @foreach ($orders as $key => $order)
        <tr class="animate__animated animate__fadeIn {{ $order->status == "delivered" && $order->payment_status == "completed" ? 'bg-blue-100' : ''}}">
            <td>
                <div class="form-check {{ $order->status == "delivered" && $order->payment_status == "completed" ? 'hidden' : ''}}">
                    <input class="form-check-input input-primary input-checkbox checkbox-item" type="checkbox"
                        id="customCheckbox{{ $order->id }}" value="{{ $order->id }}"
                        {{ $order->status == "delivered" && $order->payment_status == "completed" ? 'disabled' : ''}}>
                    <label class="form-check-label" for="customCheckbox{{ $order->id }}"></label>
                </div>
            </td>
            <td>{{ $key+1 }}</td>
            <td><a href="{{ route('order.edit',  ['id' => $order->id]) }}"  class="btn_link {{ $order->status == "delivered" && $order->payment_status == "completed" ? 'disabled_row' : ''}}">{{ $order->code }}</a></td>
            <td>{{ number_format($order->total, 0, '.', '.') }}</td>
            <td>{{ $order->paymentMethod->name }}</td>
            <td>
                <select name="payment_status" data-id="{{ $order->id }}" class="form-select select_status" {{ $order->status == "delivered" && $order->payment_status == "completed" ? 'disabled' : ''}}>
                    @foreach (__('order.payment_status') as $key => $item)
                        <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </td>            
            <td>
                <select name="status" data-id="{{ $order->id }}" class="form-select select_status" {{ $order->status == "delivered" && $order->payment_status == "completed" ? 'disabled' : ''}}>
                    @foreach (__('order.status') as $key => $value)
                        <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>{{ changeDateFormat($order->created_at) }}</td>
            <td class="text-center table-actions ">
                <ul class="list-inline me-auto mb-0">
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Xem">
                        <a href="{{ route('order.show', ['id' => $order->id]) }}"
                            class="avtar avtar-xs btn-link-success btn-pc-default">
                            <i class="ti ti-eye f-18"></i>
                        </a>
                    </li>
                    <li class="list-inline-item align-bottom btn_edit {{$order->status == "delivered" && $order->payment_status == "completed" ? 'disabled_row' : ''}}" data-bs-toggle="tooltip" title="Chỉnh sửa">
                        <a href="{{ route('order.edit', ['id' => $order->id, 'page' => request()->get('page', 1)]) }}"
                            class="avtar avtar-xs btn-link-success btn-pc-default">
                            <i class="ti ti-edit-circle f-18"></i>
                        </a>
                    </li>
                    <x-delete :id="$order->id" :model="ucfirst($config['model'])" :class=" $order->status == 'delivered' && $order->payment_status == 'completed' ? 'disabled_row btn_delete' : 'btn_delete' " />
                </ul>
            </td>
        </tr>
    @endforeach
    <tr>
        <td>
            <div class="card-footer">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="100" class="text-center">Không có dữ liệu</td>
    </tr>
@endif
<style>
    .disabled_row {
        pointer-events: none; 
        opacity: 0.5;
    }
</style>