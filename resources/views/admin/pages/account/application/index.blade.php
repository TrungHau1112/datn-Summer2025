@extends('admin.layout')

@section('template')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tài khoản ứng dụng</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tên ứng dụng</label>
                                <input type="text" class="form-control" value="Shop Phone Store" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phiên bản</label>
                                <input type="text" class="form-control" value="1.0.0" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Framework</label>
                                <input type="text" class="form-control" value="Laravel 11.x" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">PHP Version</label>
                                <input type="text" class="form-control" value="{{ PHP_VERSION }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Database</label>
                                <input type="text" class="form-control" value="MySQL" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Environment</label>
                                <input type="text" class="form-control" value="{{ app()->environment() }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <h5>Thông tin hệ thống</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Thông tin</th>
                                            <th>Giá trị</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Server Time</td>
                                            <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Timezone</td>
                                            <td>{{ config('app.timezone') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Locale</td>
                                            <td>{{ config('app.locale') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Debug Mode</td>
                                            <td>
                                                <span class="badge bg-{{ config('app.debug') ? 'warning' : 'success' }}">
                                                    {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
