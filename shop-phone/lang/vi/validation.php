<?php

return [
    'accepted' => ':attribute phải được chấp nhận.',
    'accepted_if' => ':attribute phải được chấp nhận khi :other là :value.',
    'active_url' => ':attribute không phải là một URL hợp lệ.',
    'after' => ':attribute phải là một ngày sau :date.',
    'after_or_equal' => ':attribute phải là một ngày bằng hoặc sau :date.',
    'alpha' => ':attribute chỉ được chứa chữ cái.',
    'alpha_dash' => ':attribute chỉ được chứa chữ cái, số, dấu gạch ngang và gạch dưới.',
    'alpha_num' => ':attribute chỉ được chứa chữ cái và số.',
    'array' => ':attribute phải là một mảng.',
    'ascii' => ':attribute chỉ được chứa các ký tự ASCII.',
    'before' => ':attribute phải là một ngày trước :date.',
    'before_or_equal' => ':attribute phải là một ngày bằng hoặc trước :date.',
    'between' => [
        'array' => ':attribute phải có từ :min đến :max phần tử.',
        'file' => ':attribute phải có kích thước từ :min đến :max kilobytes.',
        'numeric' => ':attribute phải có giá trị từ :min đến :max.',
        'string' => ':attribute phải có từ :min đến :max ký tự.',
    ],
    'boolean' => ':attribute phải là true hoặc false.',
    'can' => ':attribute chứa giá trị không hợp lệ.',
    'confirmed' => ':attribute không khớp với xác nhận.',
    'contains' => ':attribute thiếu giá trị bắt buộc.',
    'current_password' => 'Mật khẩu hiện tại không chính xác.',
    'date' => ':attribute không phải là ngày hợp lệ.',
    'date_equals' => ':attribute phải là ngày bằng với :date.',
    'date_format' => ':attribute không khớp với định dạng :format.',
    'decimal' => ':attribute phải có :decimal số thập phân.',
    'declined' => ':attribute phải bị từ chối.',
    'declined_if' => ':attribute phải bị từ chối khi :other là :value.',
    'different' => ':attribute và :other phải khác nhau.',
    'digits' => ':attribute phải có :digits chữ số.',
    'digits_between' => ':attribute phải có từ :min đến :max chữ số.',
    'dimensions' => ':attribute có kích thước hình ảnh không hợp lệ.',
    'distinct' => ':attribute có giá trị trùng lặp.',
    'doesnt_end_with' => ':attribute không được kết thúc bằng một trong các giá trị sau: :values.',
    'doesnt_start_with' => ':attribute không được bắt đầu bằng một trong các giá trị sau: :values.',
    'email' => ':attribute phải là một địa chỉ email hợp lệ.',
    'ends_with' => ':attribute phải kết thúc bằng một trong các giá trị sau: :values.',
    'enum' => 'Giá trị đã chọn cho :attribute không hợp lệ.',
    'exists' => ':attribute đã chọn không hợp lệ.',
    'extensions' => ':attribute phải có một trong các phần mở rộng sau: :values.',
    'file' => ':attribute phải là một tệp.',
    'filled' => ':attribute phải có giá trị.',
    'gt' => [
        'array' => ':attribute phải có nhiều hơn :value phần tử.',
        'file' => ':attribute phải lớn hơn :value kilobytes.',
        'numeric' => ':attribute phải lớn hơn :value.',
        'string' => ':attribute phải lớn hơn :value ký tự.',
    ],
    'gte' => [
        'array' => ':attribute phải có ít nhất :value phần tử.',
        'file' => ':attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'numeric' => ':attribute phải lớn hơn hoặc bằng :value.',
        'string' => ':attribute phải lớn hơn hoặc bằng :value ký tự.',
    ],
    'hex_color' => ':attribute phải là một màu hexadecimal hợp lệ.',
    'image' => ':attribute phải là một hình ảnh.',
    'in' => ':attribute đã chọn không hợp lệ.',
    'in_array' => ':attribute phải tồn tại trong :other.',
    'integer' => ':attribute phải là số nguyên.',
    'ip' => ':attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4' => ':attribute phải là một địa chỉ IPv4 hợp lệ.',
    'ipv6' => ':attribute phải là một địa chỉ IPv6 hợp lệ.',
    'json' => ':attribute phải là một chuỗi JSON hợp lệ.',
    'lowercase' => ':attribute phải là chữ thường.',
    'lt' => [
        'array' => ':attribute phải có ít hơn :value phần tử.',
        'file' => ':attribute phải nhỏ hơn :value kilobytes.',
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'string' => ':attribute phải ít hơn :value ký tự.',
    ],
    'lte' => [
        'array' => ':attribute không được có nhiều hơn :value phần tử.',
        'file' => ':attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'numeric' => ':attribute phải nhỏ hơn hoặc bằng :value.',
        'string' => ':attribute phải nhỏ hơn hoặc bằng :value ký tự.',
    ],
    'mac_address' => ':attribute phải là một địa chỉ MAC hợp lệ.',
    'max' => [
        'array' => ':attribute không được có nhiều hơn :max phần tử.',
        'file' => ':attribute không được lớn hơn :max kilobytes.',
        'numeric' => ':attribute không được lớn hơn :max.',
        'string' => ':attribute không được lớn hơn :max ký tự.',
    ],
    'mimes' => ':attribute phải là một tệp có định dạng: :values.',
    'mimetypes' => ':attribute phải là một tệp có định dạng: :values.',
    'min' => [
        'array' => ':attribute phải có ít nhất :min phần tử.',
        'file' => ':attribute phải có ít nhất :min kilobytes.',
        'numeric' => ':attribute phải có ít nhất :min.',
        'string' => ':attribute phải có ít nhất :min ký tự.',
    ],
    'not_in' => ':attribute đã chọn không hợp lệ.',
    'numeric' => ':attribute phải là số.',
    'required' => ':attribute là bắt buộc.',
    'unique' => ':attribute đã tồn tại.',

    /*
    |--------------------------------------------------------------------------
    | Thuộc tính xác thực tùy chỉnh
    |--------------------------------------------------------------------------
    |
    | Những dòng thông báo sau đây được sử dụng để thay thế thuộc tính
    | bằng các tên dễ hiểu hơn, giúp các thông báo dễ đọc hơn.
    |
    */

    'attributes' => [],
];
