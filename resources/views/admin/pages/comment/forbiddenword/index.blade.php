@extends('admin.layout')

@section('template')
    @include('admin.pages.comment.forbiddenword.save')
    <div class="card">
        <div class="card-header">
            <x-filter :model="$config['model']" :options="[
                'actions' => generateSelect('Hành động', __('general.actions')),
                'perpage' => generateSelect('10 hàng', __('general.perpage')),
                'sort' => generateSelect('Sắp xếp', __('general.sort')),
            ]" />
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>STT</th>
                            <th>Từ cấm</th>
                            {{-- <th>Xử lý</th> --}}
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"> {{-- phải có id tbody  --}}
                        {{-- js render data ở đây,  --}}
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">
         
        </div>
    </div>
    <input type="hidden" name="model" id="model" value="{{ ucfirst($config['model']) }}">
@endsection
