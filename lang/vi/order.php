<?php

return [
    'status' => [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao hàng',
        'delivered' => 'Giao hàng thành công',
        'cancelled' => 'Đã hủy',
    ],

    'payment_status' => [
        'pending' => 'Chưa thanh toán',
        'completed' => 'Đã thanh toán',
        'return' => 'Hoàn tiền',
    ],

    'payment_status_select' => [
        0 => [
            'id' => 'pending',
            'name' => 'Đang chờ',
        ],
        1 => [
            'id' => 'completed',
            'name' => 'Đã hoàn thành',
        ],
        2 => [
            'id' => 'failed',
            'name' => 'Thất bại',
        ],
        3 => [
            'id' => 'refunded',
            'name' => 'Hoàn tiền',
        ]
    ],

    'sort' => [
        0 => [
            'id' => 'id,desc',
            'name' => 'Từ mới đến cũ'
        ],
        1 => [
            'id' => 'id,asc',
            'name' => 'Từ cũ đến mới'
        ]
        ],

    'statusFilter' => [
        0 => [
            'id' => 'pending',
            'name' => 'Chờ xử lý'
        ],
        1 => [
            'id' => 'processing',
            'name' => 'Đang xử lý'
        ],
        2 => [
            'id' => 'shipped',
            'name' => 'Đã giao cho đơn vị vận chuyển'
        ],
        3 => [
            'id' => 'delivered',
            'name' => 'Đã nhận hàng'
        ],
        4 => [
            'id' => 'cancelled',
            'name' => 'Đã hủy'
        ]
    ],

    'actions' => [
        0 => [
            'id' => 'delete',
            'name' => 'Xóa'
        ],
    ]
    
];
