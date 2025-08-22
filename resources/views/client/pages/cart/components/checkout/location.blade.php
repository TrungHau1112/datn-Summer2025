<div class="row mb-3">
    {{-- Province --}}
    <div class="col-4 form-group">
        <label for="province_id" class="control-label">Tỉnh/Thành Phố: <span class="text-danger">*</span></label>
        <select name="province_id" id="province_id" class="form-control province select2_order location" data-target="districts" required>
            <option value="">Chọn tỉnh/thành phố</option>
            @foreach ($provinces as $province)
                <option value="{{ $province->code }}"
                    {{ old('province_id', $user->province_id ?? '') == $province->code ? 'selected' : '' }}>
                    {{ $province->name }}
                </option>
            @endforeach
        </select>
        @error('province_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- District --}}
    <div class="col-4 form-group">
        <label for="district_id" class="control-label">Quận/Huyện: <span class="text-danger">*</span></label>
        <select name="district_id" id="district_id" class="form-control select2_order districts location" data-target="wards" required>
            <option value="">Chọn quận/huyện</option>
            @foreach ($districts as $district)
                <option value="{{ $district->code }}"
                    {{ old('district_id', $user->district_id ?? '') == $district->code ? 'selected' : '' }}>
                    {{ $district->name }}
                </option>
            @endforeach
        </select>
        @error('district_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Ward --}}
    <div class="col-4 form-group">
        <label for="ward_id" class="control-label">Phường/Xã: <span class="text-danger">*</span></label>
        <select name="ward_id" id="ward_id" class="form-control select2_order wards" required>
            <option value="">Chọn phường/xã</option>
            @foreach ($wards as $ward)
                <option value="{{ $ward->code }}"
                    {{ old('ward_id', $user->ward_id ?? '') == $ward->code ? 'selected' : '' }}>
                    {{ $ward->name }}
                </option>
            @endforeach
        </select>
        @error('ward_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Script để JS lấy dữ liệu cũ nếu cần --}}
<script>
    var province_id = '{{ old('province_id', $user->province_id ?? '') }}';
    var district_id = '{{ old('district_id', $user->district_id ?? '') }}';
    var ward_id = '{{ old('ward_id', $user->ward_id ?? '') }}';
</script>
